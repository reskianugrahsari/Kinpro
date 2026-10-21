<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IzinController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Izin::with(['pegawai.unitKerja']);

        if ($user->role === 'pegawai') {
            $pegawai = Pegawai::where('user_id', $user->id)->first();
            if ($pegawai) {
                $query->where('pegawai_id', $pegawai->id);
            } else {
                $query->where('pegawai_id', 0);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('pegawai', function($pq) use ($search) {
                    $pq->where('nama', 'like', "%{$search}%")
                      ->orWhere('nip', 'like', "%{$search}%");
                })->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('jenis_izin', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all' && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_izin') && $request->jenis_izin !== 'all' && $request->jenis_izin !== '') {
            $query->where('jenis_izin', $request->jenis_izin);
        }

        if ($request->filled('bulan') && $request->bulan !== 'all' && $request->bulan !== '') {
            $query->whereMonth('tanggal_mulai', $request->bulan);
        }

        $perPage = in_array((int)$request->input('per_page', 10), [5, 10, 25, 50]) ? (int)$request->input('per_page', 10) : 10;
        $izins = $query->latest()->paginate($perPage)->withQueryString();
        
        $now = Carbon::now();
        $pendingCount = Izin::where('status', 'pending')->count();
        $approvedThisMonth = Izin::where('status', 'approved')
            ->whereYear('tanggal_mulai', $now->year)
            ->whereMonth('tanggal_mulai', $now->month)
            ->count();

        $todayActiveCount = Izin::where('status', 'approved')
            ->whereDate('tanggal_mulai', '<=', Carbon::today())
            ->whereDate('tanggal_selesai', '>=', Carbon::today())
            ->count();

        $pegawaiCount = Pegawai::count() ?: 1;
        $totalKuota = $pegawaiCount * 12;
        $terpakai = Izin::where('status', 'approved')->where('jenis_izin', 'cuti_tahunan')->count();
        $sisaKuotaBalaiPct = max(0, min(100, round((($totalKuota - $terpakai) / max(1, $totalKuota)) * 100)));

        $pegawai = Pegawai::where('user_id', $user->id)->first();
        $jumlahBulanIni = 0;
        $sisaKuota = 4;

        if ($pegawai) {
            $jumlahBulanIni = Izin::where('pegawai_id', $pegawai->id)
                ->whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->count();
            $sisaKuota = max(0, 4 - $jumlahBulanIni);
        }

        // Active leaves for calendar modal
        $calendarLeaves = Izin::with('pegawai')
            ->where('status', 'approved')
            ->whereYear('tanggal_mulai', $now->year)
            ->whereMonth('tanggal_mulai', $now->month)
            ->get();

        return view('izin.index', compact(
            'izins',
            'pendingCount',
            'approvedThisMonth',
            'todayActiveCount',
            'sisaKuotaBalaiPct',
            'jumlahBulanIni',
            'sisaKuota',
            'calendarLeaves'
        ));
    }

    public function create()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('izin.index')->with('error', 'Administrator hanya bertugas mengelola izin. Pengajuan hanya dapat dilakukan oleh pegawai.');
        }

        $pegawai = Pegawai::where('user_id', $user->id)->with('unitKerja')->first();
        if (!$pegawai) {
            return redirect()->route('izin.index')->with('error', 'Data pegawai tidak ditemukan.');
        }

        $jumlahBulanIni = Izin::where('pegawai_id', $pegawai->id)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();
        $sisaKuota = max(0, 4 - $jumlahBulanIni);

        return view('izin.create', compact('pegawai', 'jumlahBulanIni', 'sisaKuota'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('izin.index')->with('error', 'Administrator tidak dapat mengajukan izin.');
        }

        $pegawai = Pegawai::where('user_id', $user->id)->first();
        if (!$pegawai) {
            return redirect()->route('izin.index')->with('error', 'Data pegawai tidak ditemukan.');
        }

        $pegawaiId = $pegawai->id;

        $request->validate([
            'jenis_izin' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string',
            'dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Check quota (max 4x per month)
        $totalBulanIni = Izin::where('pegawai_id', $pegawaiId)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        if ($totalBulanIni >= 4) {
            return back()->withErrors(['jenis_izin' => 'Batas kuota pengajuan izin/cuti sudah habis (maksimal 4 kali per bulan).'])->withInput();
        }

        $dokumenPath = null;
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/izin'), $filename);
            $dokumenPath = 'uploads/izin/' . $filename;
        }

        Izin::create([
            'id_pegawai' => $pegawaiId,
            'pegawai_id' => $pegawaiId,
            'jenis_izin' => $request->jenis_izin,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan' => $request->keterangan,
            'dokumen' => $dokumenPath,
            'status' => 'pending',
        ]);

        return redirect()->route('izin.index')->with('success', 'Pengajuan izin/cuti berhasil dikirim.');
    }

    public function updateStatus(Request $request, Izin $izin)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan_admin' => 'nullable|string',
        ]);

        $izin->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        return redirect()->route('izin.index')->with('success', 'Status pengajuan izin berhasil diperbarui.');
    }

    public function destroy(Izin $izin)
    {
        $user = auth()->user();
        $pegawai = Pegawai::where('user_id', $user->id)->first();

        if ($user->role === 'pegawai' && (!$pegawai || $izin->pegawai_id !== $pegawai->id || $izin->status !== 'pending')) {
            abort(403);
        }

        $izin->delete();
        return redirect()->route('izin.index')->with('success', 'Pengajuan izin berhasil dibatalkan/dihapus.');
    }
}
