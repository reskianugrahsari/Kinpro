<?php

namespace App\Http\Controllers;

use App\Models\PengajuanAbsen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AbsenAdminController extends Controller
{
    public function index(Request $request)
    {
        abort_if(! auth()->user()->isAdmin(), 403, 'Halaman ini khusus untuk Administrator.');

        $activeTab = $request->input('tab', 'pengajuan');
        if (! in_array($activeTab, ['pengajuan', 'rekap', 'arsip'], true)) {
            $activeTab = 'pengajuan';
        }

        // KPI Summary across all PengajuanAbsen
        $totalAll = PengajuanAbsen::count();
        $pendingCount = PengajuanAbsen::where('status', 'pending')->count();
        $approvedCount = PengajuanAbsen::whereIn('status', ['approved', 'disetujui'])->count();
        $rejectedCount = PengajuanAbsen::whereIn('status', ['rejected', 'ditolak'])->count();

        $hasNama = Schema::hasColumn('pegawai', 'nama');
        $hasNamaLengkap = Schema::hasColumn('pegawai', 'nama_lengkap');

        $applySearch = function ($q, string $search) use ($hasNama, $hasNamaLengkap) {
            $q->where(function ($sub) use ($search, $hasNama, $hasNamaLengkap) {
                $sub->whereHas('pegawai', function ($pq) use ($search, $hasNama, $hasNamaLengkap) {
                    $pq->where('nip', 'like', "%{$search}%");
                    if ($hasNama) {
                        $pq->orWhere('nama', 'like', "%{$search}%");
                    }
                    if ($hasNamaLengkap) {
                        $pq->orWhere('nama_lengkap', 'like', "%{$search}%");
                    }
                })
                ->orWhere('alasan', 'like', "%{$search}%")
                ->orWhere('jenis_absen', 'like', "%{$search}%");
            });
        };

        // Tab 1: Verifikasi & Pengajuan
        $pengajuanQuery = PengajuanAbsen::with(['pegawai.unitKerja', 'atasan'])->latest();
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'approved') {
                $pengajuanQuery->whereIn('status', ['approved', 'disetujui']);
            } elseif ($request->status === 'rejected') {
                $pengajuanQuery->whereIn('status', ['rejected', 'ditolak']);
            } else {
                $pengajuanQuery->where('status', $request->status);
            }
        }
        if ($request->filled('jenis') && $request->jenis !== 'all') {
            $pengajuanQuery->where('jenis_absen', 'like', "%{$request->jenis}%");
        }
        if ($request->filled('search')) {
            $applySearch($pengajuanQuery, $request->search);
        }
        $pengajuans = $pengajuanQuery->paginate(10)->withQueryString();

        // Tab 2: Rekap Bulanan Lupa Absen
        $bulan = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));
        $jenisAbsen = $request->input('jenis_absen');

        $rekapItems = collect();
        $pegawaiRekap = [];
        $rekapSummary = [
            'total' => 0,
            'masuk' => 0,
            'pulang' => 0,
            'approved' => 0,
            'pending' => 0,
        ];

        if ($activeTab === 'rekap') {
            $rekapQuery = PengajuanAbsen::with(['pegawai.unitKerja'])
                ->whereRaw('YEAR(COALESCE(tanggal_lupa, tanggal_absen, created_at)) = ?', [(int) $tahun]);

            if (! empty($bulan) && $bulan !== 'all') {
                $rekapQuery->whereRaw('MONTH(COALESCE(tanggal_lupa, tanggal_absen, created_at)) = ?', [(int) $bulan]);
            }
            if (! empty($jenisAbsen)) {
                $rekapQuery->where('jenis_absen', 'like', "%{$jenisAbsen}%");
            }
            if ($request->filled('search')) {
                $applySearch($rekapQuery, $request->search);
            }

            $rekapItems = $rekapQuery->latest()->get();
            foreach ($rekapItems as $item) {
                $pId = $item->pegawai_id;
                if (! isset($pegawaiRekap[$pId])) {
                    $pegawaiRekap[$pId] = [
                        'nama' => $item->pegawai?->nama ?? 'Pegawai',
                        'nip' => $item->pegawai?->nip ?? '-',
                        'unit' => $item->pegawai?->unitKerja?->nama ?? 'Balai Wil. VIII Makassar',
                        'total_masuk' => 0,
                        'total_pulang' => 0,
                        'total_frekuensi' => 0,
                        'approved' => 0,
                        'pending' => 0,
                    ];
                }

                $lowerJenis = strtolower((string) $item->jenis_absen);
                if (str_contains($lowerJenis, 'masuk') || str_contains($lowerJenis, 'datang')) {
                    $pegawaiRekap[$pId]['total_masuk']++;
                    $rekapSummary['masuk']++;
                }
                if (str_contains($lowerJenis, 'pulang')) {
                    $pegawaiRekap[$pId]['total_pulang']++;
                    $rekapSummary['pulang']++;
                }
                $pegawaiRekap[$pId]['total_frekuensi']++;
                $rekapSummary['total']++;

                if (in_array($item->status, ['approved', 'disetujui'], true)) {
                    $pegawaiRekap[$pId]['approved']++;
                    $rekapSummary['approved']++;
                } else {
                    $pegawaiRekap[$pId]['pending']++;
                    $rekapSummary['pending']++;
                }
            }
        }

        // Tab 3: Arsip Surat Disetujui
        $arsipQuery = PengajuanAbsen::with(['pegawai.unitKerja', 'atasan'])
            ->whereIn('status', ['approved', 'disetujui'])
            ->latest();

        if ($activeTab === 'arsip' && $request->filled('search')) {
            $applySearch($arsipQuery, $request->search);
        }
        $arsips = $activeTab === 'arsip'
            ? $arsipQuery->paginate(10)->withQueryString()
            : collect();

        return view('admin.absen.index', compact(
            'activeTab',
            'totalAll',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'pengajuans',
            'bulan',
            'tahun',
            'jenisAbsen',
            'pegawaiRekap',
            'rekapSummary',
            'arsips'
        ));
    }

    public function export(Request $request)
    {
        abort_if(! auth()->user()->isAdmin(), 403);

        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $query = PengajuanAbsen::with(['pegawai.unitKerja', 'atasan'])->latest();
        if (! empty($tahun)) {
            $query->whereRaw('YEAR(COALESCE(tanggal_lupa, tanggal_absen, created_at)) = ?', [(int) $tahun]);
        }
        if (! empty($bulan) && $bulan !== 'all') {
            $query->whereRaw('MONTH(COALESCE(tanggal_lupa, tanggal_absen, created_at)) = ?', [(int) $bulan]);
        }

        $items = $query->get();
        $filename = 'rekap-lupa-absen-' . date('Y-m-d') . '.csv';

        return response()->stream(function () use ($items) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['No', 'Nama Pegawai', 'NIP', 'Tanggal Lupa', 'Jenis Lupa Absen', 'Alasan', 'Atasan', 'Status', 'Catatan Admin']);

            foreach ($items as $idx => $item) {
                $tgl = $item->tanggal_lupa ?? $item->tanggal_absen;
                fputcsv($handle, [
                    $idx + 1,
                    $item->pegawai?->nama ?? '-',
                    $item->pegawai?->nip ?? '-',
                    $tgl ? $tgl->format('Y-m-d') : '-',
                    $item->jenis_absen ?? '-',
                    $item->alasan ?? '-',
                    $item->atasan?->nama ?? 'Atasan Langsung',
                    strtoupper((string) $item->status),
                    $item->catatan_admin ?? '-',
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
