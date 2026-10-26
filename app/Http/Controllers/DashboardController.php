<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\Report;
use App\Models\PengajuanAbsen;
use App\Models\Izin;
use App\Models\Pengaduan;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $periode = $request->input('periode', 'all'); // all, tw1, tw2, tw3, tw4, smt1, smt2, tahunan
            $tahun = (int) $request->input('tahun', date('Y'));
            $bulan = $request->input('bulan', 'all');

            // Map Periode to Months if specific periode is chosen
            $periodeMonths = [];
            if ($periode === 'tw1') {
                $periodeMonths = ['Januari', 'Februari', 'Maret'];
            } elseif ($periode === 'tw2') {
                $periodeMonths = ['April', 'Mei', 'Juni'];
            } elseif ($periode === 'tw3') {
                $periodeMonths = ['Juli', 'Agustus', 'September'];
            } elseif ($periode === 'tw4') {
                $periodeMonths = ['Oktober', 'November', 'Desember'];
            } elseif ($periode === 'smt1') {
                $periodeMonths = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];
            } elseif ($periode === 'smt2') {
                $periodeMonths = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            } elseif ($bulan !== 'all' && !empty($bulan)) {
                $periodeMonths = [$bulan];
            }

            $totalPegawai = Pegawai::count();
            $totalUnitKerja = UnitKerja::count();

            // Dynamic Report / Dinas filter
            $reportQuery = class_exists(Report::class) ? Report::query() : null;
            if ($reportQuery) {
                if ($tahun) {
                    $reportQuery->whereYear('created_at', $tahun);
                }
                if (!empty($periodeMonths)) {
                    $monthNumbers = [
                        'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
                        'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
                        'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
                    ];
                    $mNums = array_filter(array_map(fn($m) => $monthNumbers[$m] ?? null, $periodeMonths));
                    if (!empty($mNums)) {
                        $reportQuery->whereIn(\Illuminate\Support\Facades\DB::raw('MONTH(created_at)'), $mNums);
                    }
                }
            }
            $totalReports = $reportQuery ? (clone $reportQuery)->count() : 0;
            $reviewDinasCount = $reportQuery ? (clone $reportQuery)->whereIn('status', ['submitted', 'draft'])->count() : 0;

            // Dynamic Absen filter
            $absenQuery = class_exists(PengajuanAbsen::class) ? PengajuanAbsen::query() : null;
            if ($absenQuery) {
                if ($tahun) {
                    $absenQuery->whereYear('created_at', $tahun);
                }
                if (!empty($periodeMonths)) {
                    $monthNumbers = [
                        'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
                        'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
                        'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
                    ];
                    $mNums = array_filter(array_map(fn($m) => $monthNumbers[$m] ?? null, $periodeMonths));
                    if (!empty($mNums)) {
                        $absenQuery->whereIn(\Illuminate\Support\Facades\DB::raw('MONTH(created_at)'), $mNums);
                    }
                }
            }
            $totalAbsen = $absenQuery ? (clone $absenQuery)->count() : 0;
            $pendingAbsenCount = $absenQuery ? (clone $absenQuery)->where('status', 'pending')->count() : 0;

            // Dynamic Izin filter
            $izinQuery = class_exists(Izin::class) ? Izin::query() : null;
            if ($izinQuery) {
                if ($tahun) {
                    $izinQuery->whereYear('tanggal_mulai', $tahun);
                }
                if (!empty($periodeMonths)) {
                    $monthNumbers = [
                        'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
                        'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
                        'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
                    ];
                    $mNums = array_filter(array_map(fn($m) => $monthNumbers[$m] ?? null, $periodeMonths));
                    if (!empty($mNums)) {
                        $izinQuery->whereIn(\Illuminate\Support\Facades\DB::raw('MONTH(tanggal_mulai)'), $mNums);
                    }
                }
            }
            $pendingIzinCount = $izinQuery ? (clone $izinQuery)->where('status', 'pending')->count() : 0;

            // Dynamic Pengaduan filter
            $pengaduanQuery = class_exists(Pengaduan::class) ? Pengaduan::query() : null;
            if ($pengaduanQuery) {
                if ($tahun) {
                    $pengaduanQuery->whereYear('created_at', $tahun);
                }
                if (!empty($periodeMonths)) {
                    $monthNumbers = [
                        'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
                        'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
                        'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
                    ];
                    $mNums = array_filter(array_map(fn($m) => $monthNumbers[$m] ?? null, $periodeMonths));
                    if (!empty($mNums)) {
                        $pengaduanQuery->whereIn(\Illuminate\Support\Facades\DB::raw('MONTH(created_at)'), $mNums);
                    }
                }
            }
            $pendingPengaduanCount = $pengaduanQuery ? (clone $pengaduanQuery)->where('status', 'pending')->count() : 0;

            // Penilaian SKP count & stats
            $penilaianQuery = class_exists(Penilaian::class) ? Penilaian::query() : null;
            if ($penilaianQuery) {
                if ($tahun) {
                    $penilaianQuery->where('tahun', $tahun);
                }
                if (!empty($periodeMonths)) {
                    $penilaianQuery->whereIn('bulan', $periodeMonths);
                }
            }
            $penilaianSelesaiCount = $penilaianQuery ? (clone $penilaianQuery)->count() : 0;

            // Query Pegawai with latest Penilaian & UnitKerja for table
            $pegawaiQuery = Pegawai::with(['unitKerja', 'user', 'penilaians' => function($q) use ($tahun, $periodeMonths) {
                if ($tahun) {
                    $q->where('tahun', $tahun);
                }
                if (!empty($periodeMonths)) {
                    $q->whereIn('bulan', $periodeMonths);
                }
                $q->latest();
            }]);

            if ($request->filled('search')) {
                $search = $request->search;
                $pegawaiQuery->where(function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nip', 'like', "%{$search}%");
                });
            }

            if ($request->filled('unit_kerja_id')) {
                $pegawaiQuery->where('unit_kerja_id', $request->unit_kerja_id);
            }

            $pegawaiList = $pegawaiQuery->paginate(5)->withQueryString();
            $unitKerjaList = UnitKerja::all();

            return view('admin.dashboard', compact(
                'periode',
                'tahun',
                'bulan',
                'totalPegawai',
                'totalUnitKerja',
                'totalReports',
                'totalAbsen',
                'pendingIzinCount',
                'pendingPengaduanCount',
                'pendingAbsenCount',
                'reviewDinasCount',
                'penilaianSelesaiCount',
                'pegawaiList',
                'unitKerjaList'
            ));
        } else {
            $pegawai = Pegawai::where('user_id', $user->id)->first();
            $myReports = class_exists(Report::class) && $pegawai 
                ? Report::where('pegawai_id', $pegawai->id)->latest()->take(5)->get() 
                : collect();
            $myAbsen = class_exists(PengajuanAbsen::class) && $pegawai 
                ? PengajuanAbsen::where('pegawai_id', $pegawai->id)->latest()->take(5)->get() 
                : collect();
            $myIzin = class_exists(Izin::class) && $pegawai 
                ? Izin::where('pegawai_id', $pegawai->id)->latest()->take(5)->get() 
                : collect();
            $myPenilaian = class_exists(Penilaian::class) && $pegawai 
                ? Penilaian::where('pegawai_id', $pegawai->id)->latest()->first() 
                : null;

            $totalDinas = class_exists(Report::class) && $pegawai ? Report::where('pegawai_id', $pegawai->id)->count() : 0;
            $totalIzin = class_exists(Izin::class) && $pegawai ? Izin::where('pegawai_id', $pegawai->id)->count() : 0;
            $totalAbsen = class_exists(PengajuanAbsen::class) && $pegawai ? PengajuanAbsen::where('pegawai_id', $pegawai->id)->count() : 0;
            $totalPengaduan = class_exists(Pengaduan::class) && $pegawai ? Pengaduan::where('pegawai_id', $pegawai->id)->count() : 0;
            $myPengaduan = class_exists(Pengaduan::class) && $pegawai ? Pengaduan::where('pegawai_id', $pegawai->id)->latest()->take(5)->get() : collect();

            $skorKinerja = $myPenilaian ? $myPenilaian->rata_rata : 91.2;
            $predikatKinerja = $myPenilaian ? $myPenilaian->predikat : 'Sangat Baik';

            return view('pegawai.dashboard', compact(
                'pegawai', 
                'myReports', 
                'myAbsen', 
                'myIzin', 
                'myPengaduan',
                'myPenilaian', 
                'totalDinas', 
                'totalIzin', 
                'totalAbsen', 
                'totalPengaduan',
                'skorKinerja', 
                'predikatKinerja'
            ));
        }
    }
}
