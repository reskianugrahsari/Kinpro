<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\Report;
use App\Models\PengajuanAbsen;
use App\Models\Izin;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $totalPegawai = Pegawai::count();
            $totalUnitKerja = UnitKerja::count();
            $totalReports = class_exists(Report::class) ? Report::count() : 0;
            $totalAbsen = class_exists(PengajuanAbsen::class) ? PengajuanAbsen::count() : 0;

            $reportsByStatus = class_exists(Report::class) 
                ? Report::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status')->toArray()
                : [];

            $pegawaiPerUnit = UnitKerja::withCount('pegawai')->get();

            $recentReports = class_exists(Report::class) 
                ? Report::with('pegawai')->latest()->take(5)->get() 
                : collect();

            $recentAbsen = class_exists(PengajuanAbsen::class) 
                ? PengajuanAbsen::with('pegawai')->latest()->take(5)->get() 
                : collect();

            return view('admin.dashboard', compact(
                'totalPegawai',
                'totalUnitKerja',
                'totalReports',
                'totalAbsen',
                'reportsByStatus',
                'pegawaiPerUnit',
                'recentReports',
                'recentAbsen'
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
            $skorKinerja = $myPenilaian ? $myPenilaian->rata_rata : 91.2;
            $predikatKinerja = $myPenilaian ? $myPenilaian->predikat : 'Sangat Baik';

            return view('pegawai.dashboard', compact(
                'pegawai', 
                'myReports', 
                'myAbsen', 
                'myIzin', 
                'myPenilaian', 
                'totalDinas', 
                'totalIzin', 
                'totalAbsen', 
                'skorKinerja', 
                'predikatKinerja'
            ));
        }
    }
}
