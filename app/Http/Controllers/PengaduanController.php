<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Pengaduan::with(['pegawai.unitKerja'])->latest();

        if ($user->role !== 'admin') {
            $pegawai = Pegawai::where('user_id', $user->id)->first();
            $query->where('pegawai_id', $pegawai ? $pegawai->id : 0);
            $baseCountQuery = Pengaduan::where('pegawai_id', $pegawai ? $pegawai->id : 0);
        } else {
            $baseCountQuery = Pengaduan::query();
            if ($request->filled('status') && $request->status !== 'all' && $request->status !== 'Semua') {
                $query->where('status', $request->status);
            }
            if ($request->filled('jenis') && $request->jenis !== 'all' && $request->jenis !== '') {
                $query->where('jenis_laporan', $request->jenis);
            }
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereHas('pegawai', function($pq) use ($search) {
                        $pq->where('nama', 'like', "%{$search}%")
                          ->orWhere('nip', 'like', "%{$search}%");
                    })->orWhere('keterangan', 'like', "%{$search}%")
                      ->orWhere('jenis_laporan', 'like', "%{$search}%");
                });
            }
        }

        $totalAll = (clone $baseCountQuery)->count();
        $pendingCount = (clone $baseCountQuery)->where('status', 'pending')->count();
        $approvedCount = (clone $baseCountQuery)->where('status', 'approved')->count();
        $rejectedCount = (clone $baseCountQuery)->where('status', 'rejected')->count();

        $perPage = in_array((int)$request->input('per_page', 10), [5, 10, 25, 50]) ? (int)$request->input('per_page', 10) : 10;
        $pengaduans = $query->paginate($perPage)->withQueryString();

        $categories = Pengaduan::select('jenis_laporan')->distinct()->whereNotNull('jenis_laporan')->pluck('jenis_laporan')->toArray();

        return view('pengaduan.index', compact(
            'pengaduans',
            'totalAll',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'categories'
        ));
    }

    public function create()
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('pengaduan.index')->with('error', 'Administrator hanya bertugas memproses pengaduan. Pengaduan hanya dapat diajukan oleh pegawai.');
        }

        $pegawai = Pegawai::where('user_id', auth()->id())->with('unitKerja')->first();

        return view('pengaduan.create', compact('pegawai'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('pengaduan.index')->with('error', 'Administrator tidak dapat membuat pengaduan.');
        }

        $pegawai = Pegawai::where('user_id', $user->id)->first();
        if (!$pegawai) {
            return back()->with('error', 'Profil pegawai tidak ditemukan.');
        }
        $pegawaiId = $pegawai->id;

        $validated = $request->validate([
            'jenis_laporan' => 'required|string|max:255',
            'tanggal_kejadian' => 'required|date',
            'keterangan' => 'required|string',
            'bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/pengaduan'), $filename);
            $buktiPath = 'uploads/pengaduan/' . $filename;
        }

        Pengaduan::create([
            'pegawai_id' => $pegawaiId,
            'nip' => $pegawai->nip,
            'jenis_laporan' => $validated['jenis_laporan'],
            'tanggal_kejadian' => $validated['tanggal_kejadian'],
            'keterangan' => $validated['keterangan'],
            'bukti' => $buktiPath,
            'status' => 'pending',
        ]);

        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dikirim.');
    }

    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'alasan_penolakan' => 'nullable|string',
        ]);

        $pengaduan->update([
            'status' => $validated['status'],
            'alasan_penolakan' => $validated['alasan_penolakan'],
        ]);

        return back()->with('success', 'Status pengaduan berhasil diperbarui.');
    }

    public function destroy(Pengaduan $pengaduan)
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            $pegawai = Pegawai::where('user_id', $user->id)->first();
            if (!$pegawai || $pengaduan->pegawai_id !== $pegawai->id || $pengaduan->status !== 'pending') {
                abort(403);
            }
        }

        $pengaduan->delete();
        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dihapus.');
    }
}
