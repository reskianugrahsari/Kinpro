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
            $totalPegawai = Pegawai::count();
            $totalUnitKerja = UnitKerja::count();
            $totalReports = class_exists(Report::class) ? Report::count() : 0;
            $totalAbsen = class_exists(PengajuanAbsen::class) ? PengajuanAbsen::count() : 0;

            $pendingIzinCount = class_exists(Izin::class) ? Izin::where('status', 'pending')->count() : 0;
            $pendingPengaduanCount = class_exists(Pengaduan::class) ? Pengaduan::where('status', 'pending')->count() : 0;
            $pendingAbsenCount = class_exists(PengajuanAbsen::class) ? PengajuanAbsen::where('status', 'pending')->count() : 0;
            $reviewDinasCount = class_exists(Report::class) ? Report::whereIn('status', ['submitted', 'draft'])->count() : 0;

            // Penilaian SKP count & stats
            $penilaianSelesaiCount = class_exists(Penilaian::class) ? Penilaian::count() : 28;

            // Query Pegawai with latest Penilaian & UnitKerja for table
            $pegawaiQuery = Pegawai::with(['unitKerja', 'user', 'penilaians' => function($q) {
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
