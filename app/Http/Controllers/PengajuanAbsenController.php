<?php

namespace App\Http\Controllers;

use App\Models\PengajuanAbsen;
use App\Models\Pegawai;
use App\Models\MasterPpk;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengajuanAbsenController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = PengajuanAbsen::with(['pegawai.unitKerja', 'atasan'])->latest();

        if ($user->role !== 'admin') {
            $pegawai = Pegawai::where('user_id', $user->id)->first();
            $query->where('pegawai_id', $pegawai ? $pegawai->id : 0);
        } else {
            if ($request->filled('status') && $request->status !== 'all' && $request->status !== '') {
                $query->where('status', $request->status);
            }
            if ($request->filled('jenis') && $request->jenis !== 'all' && $request->jenis !== '') {
                $query->where('jenis_absen', 'like', "%{$request->jenis}%");
            }
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereHas('pegawai', function($pq) use ($search) {
                        $pq->where('nama', 'like', "%{$search}%")
                          ->orWhere('nama_lengkap', 'like', "%{$search}%")
                          ->orWhere('nip', 'like', "%{$search}%");
                    })->orWhere('alasan', 'like', "%{$search}%")
                      ->orWhere('jenis_absen', 'like', "%{$search}%");
                });
            }
        }

        $allAbsen = PengajuanAbsen::all();
        $totalAll = $allAbsen->count();
        $pendingCount = $allAbsen->where('status', 'pending')->count();
        $approvedCount = $allAbsen->where('status', 'approved')->count();
        $rejectedCount = $allAbsen->where('status', 'rejected')->count();

        $perPage = in_array((int)$request->input('per_page', 10), [5, 10, 25, 50]) ? (int)$request->input('per_page', 10) : 10;
        $pengaduans = $query->paginate($perPage)->withQueryString();
        return view('absen.index', compact('pengaduans', 'totalAll', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    public function create()
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('absen.index')->with('error', 'Administrator hanya bertugas memverifikasi pengajuan absen. Pengajuan hanya dapat dilakukan oleh pegawai.');
        }

        $pegawais = Pegawai::with('unitKerja')->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'nama' => $p->nama,
                'nip' => $p->nip ?? '-',
                'pangkat_gol' => $p->pangkat_gol ?? '-',
                'jabatan' => $p->jabatan ?? '-',
                'bagian' => $p->unitKerja?->nama ?? 'Balai Wil. VIII Makassar',
                'atasan_id' => $p->atasan_id ?? null,
            ];
        });

        $userPegawai = auth()->user()->pegawai;

        return view('absen.create', compact('pegawais', 'userPegawai'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('absen.index')->with('error', 'Administrator tidak dapat mengajukan surat lupa absen.');
        }

        $pegawai = Pegawai::where('user_id', $user->id)->first();
        $pegawaiId = $request->pegawai_id ?? ($pegawai ? $pegawai->id : null);
        if (!$pegawaiId) {
            return back()->with('error', 'Data pegawai tidak ditemukan.');
        }

        $validated = $request->validate([
            'pegawai_id' => 'nullable|exists:pegawai,id',
            'atasan_id' => 'nullable|exists:pegawai,id',
            'jenis_absen' => 'required|string|max:100',
            'alasan' => 'required|string',
            'tanggal_lupa' => 'nullable|date',
            'tanggal_pengajuan' => 'nullable|date',
            'tanggal_absen' => 'nullable|date',
            'kota_surat' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string',
            'bukti_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $tglLupa = $validated['tanggal_lupa'] ?? ($validated['tanggal_absen'] ?? now()->format('Y-m-d'));
        $tglPengajuan = $validated['tanggal_pengajuan'] ?? now()->format('Y-m-d');

        $buktiPath = null;
        if ($request->hasFile('bukti_path')) {
            $file = $request->file('bukti_path');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->move(public_path('uploads/absen'), $filename);
            $buktiPath = 'uploads/absen/' . $filename;
        }

        $absen = PengajuanAbsen::create([
            'pegawai_id' => $pegawaiId,
            'atasan_id' => $validated['atasan_id'] ?? null,
            'jenis_absen' => $validated['jenis_absen'],
            'alasan' => $validated['alasan'],
            'keterangan' => $validated['keterangan'] ?? $validated['alasan'],
            'tanggal_lupa' => $tglLupa,
            'tanggal_absen' => $tglLupa,
            'tanggal_pengajuan' => $tglPengajuan,
            'kota_surat' => $validated['kota_surat'] ?? 'Makassar',
            'bukti_path' => $buktiPath,
            'status' => 'pending',
        ]);

        return redirect()->route('absen.index')->with('success', 'Pengajuan lupa absen berhasil dikirim.');
    }

    public function show(PengajuanAbsen $absen)
    {
        $absen->load(['pegawai.unitKerja', 'atasan']);
        return view('absen.show', compact('absen'));
    }

    public function updateStatus(Request $request, PengajuanAbsen $absen)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|string',
            'catatan_admin' => 'nullable|string',
        ]);

        $absen->update([
            'status' => $validated['status'],
            'catatan_admin' => $validated['catatan_admin'] ?? null,
        ]);

        return back()->with('success', 'Status pengajuan absen berhasil diperbarui.');
    }

    public function destroy(PengajuanAbsen $absen)
    {
        $user = auth()->user();
        if ($user->role !== 'admin' && $absen->status !== 'pending') {
            abort(403);
        }

        $absen->delete();
        return redirect()->route('absen.index')->with('success', 'Pengajuan absen berhasil dihapus.');
    }

    public function rekapBulanan(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Administrator yang dapat mengakses rekapitulasi ini.');
        }

        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));
        $search = $request->get('search');
        $jenisAbsen = $request->get('jenis_absen');

        $query = PengajuanAbsen::with(['pegawai.unitKerja'])
            ->whereYear('tanggal_lupa', $tahun);

        if (!empty($bulan)) {
            $query->whereMonth('tanggal_lupa', $bulan);
        }

        if (!empty($jenisAbsen)) {
            $query->where('jenis_absen', 'like', "%{$jenisAbsen}%");
        }

        if (!empty($search)) {
            $query->whereHas('pegawai', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $items = $query->get();

        // Rekap per pegawai
        $pegawaiData = [];
        foreach ($items as $item) {
            $pId = $item->pegawai_id;
            if (!isset($pegawaiData[$pId])) {
                $pegawaiData[$pId] = [
                    'nama' => $item->pegawai?->nama ?? 'Pegawai',
                    'nip' => $item->pegawai?->nip ?? '-',
                    'unit' => $item->pegawai?->unitKerja?->nama ?? 'Balai Wil. VIII',
                    'total_masuk' => 0,
                    'total_pulang' => 0,
                    'total_frekuensi' => 0,
                ];
            }

            if (str_contains(strtolower($item->jenis_absen), 'masuk') || str_contains(strtolower($item->jenis_absen), 'datang')) {
                $pegawaiData[$pId]['total_masuk']++;
            }
            if (str_contains(strtolower($item->jenis_absen), 'pulang')) {
                $pegawaiData[$pId]['total_pulang']++;
            }
            $pegawaiData[$pId]['total_frekuensi']++;
        }

        // Breakdown jenis lupa absen
        $totalMasuk = 0;
        $totalPulang = 0;
        foreach ($items as $item) {
            if (str_contains(strtolower($item->jenis_absen), 'masuk') || str_contains(strtolower($item->jenis_absen), 'datang')) {
                $totalMasuk++;
            }
            if (str_contains(strtolower($item->jenis_absen), 'pulang')) {
                $totalPulang++;
            }
        }
        $jenisBreakdown = [
            'masuk' => $totalMasuk,
            'pulang' => $totalPulang,
            'total' => count($items),
        ];

        return view('absen.rekap-bulanan', compact('pegawaiData', 'jenisBreakdown', 'bulan', 'tahun', 'search', 'jenisAbsen'));
    }
}
