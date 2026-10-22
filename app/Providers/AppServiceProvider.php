<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Izin;
use App\Models\Pengaduan;
use App\Models\PengajuanAbsen;
use App\Models\Report;
use App\Models\Pegawai;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        // Global layout notification counts & list
        View::composer('layouts.app', function ($view) {
            $user = Auth::user();
            if (!$user) {
                return;
            }

            $badgeCounts = [
                'izin' => 0,
                'pengaduan' => 0,
                'absen' => 0,
                'pajokka' => 0,
                'total' => 0,
            ];
            $notifications = collect();

            if ($user->role === 'admin') {
                $pendingIzin = Izin::with('pegawai')->where('status', 'pending')->latest()->take(5)->get();
                $pendingPengaduan = Pengaduan::with('pegawai')->where('status', 'pending')->latest()->take(5)->get();
                $pendingAbsen = PengajuanAbsen::with('pegawai')->where('status', 'pending')->latest()->take(5)->get();
                $pendingReports = Report::with('user')->whereIn('status', ['submitted', 'draft'])->latest()->take(5)->get();

                $badgeCounts['izin'] = Izin::where('status', 'pending')->count();
                $badgeCounts['pengaduan'] = Pengaduan::where('status', 'pending')->count();
                $badgeCounts['absen'] = PengajuanAbsen::where('status', 'pending')->count();
                $badgeCounts['pajokka'] = Report::whereIn('status', ['submitted', 'draft'])->count();
                $badgeCounts['total'] = $badgeCounts['izin'] + $badgeCounts['pengaduan'] + $badgeCounts['absen'] + $badgeCounts['pajokka'];

                foreach ($pendingIzin as $item) {
                    $notifications->push([
                        'title' => 'Pengajuan Izin / Cuti',
                        'desc' => ($item->pegawai?->nama ?? 'Pegawai') . ': ' . ($item->keterangan ?? 'Pengajuan izin'),
                        'time' => $item->created_at,
                        'url' => route('izin.index'),
                        'icon' => 'calendar_today',
                        'color' => 'text-amber-500 bg-amber-50 dark:bg-amber-950/60 dark:text-amber-400',
                    ]);
                }

                foreach ($pendingPengaduan as $item) {
                    $notifications->push([
                        'title' => 'Pengaduan Masuk',
                        'desc' => ($item->pegawai?->nama ?? 'Pegawai') . ': ' . ($item->judul ?? $item->isi_laporan ?? 'Pengaduan baru'),
                        'time' => $item->created_at,
                        'url' => route('pengaduan.index'),
                        'icon' => 'campaign',
                        'color' => 'text-rose-500 bg-rose-50 dark:bg-rose-950/60 dark:text-rose-400',
                    ]);
                }

                foreach ($pendingAbsen as $item) {
                    $notifications->push([
                        'title' => 'Klaim Lupa Absen',
                        'desc' => ($item->pegawai?->nama ?? 'Pegawai') . ': ' . ($item->jenis_absen ?? 'Lupa absen'),
                        'time' => $item->created_at,
                        'url' => route('absen.index'),
                        'icon' => 'schedule',
                        'color' => 'text-blue-500 bg-blue-50 dark:bg-blue-950/60 dark:text-blue-400',
                    ]);
                }

                foreach ($pendingReports as $item) {
                    $notifications->push([
                        'title' => 'Laporan Pajokka / SPPD',
                        'desc' => ($item->user?->name ?? 'Pegawai') . ': ' . ($item->nomor_surat_tugas ?? $item->kota_tujuan ?? 'Laporan dinas'),
                        'time' => $item->created_at,
                        'url' => route('pajokka.index'),
                        'icon' => 'flight_takeoff',
                        'color' => 'text-emerald-500 bg-emerald-50 dark:bg-emerald-950/60 dark:text-emerald-400',
                    ]);
                }
            } else {
                $pegawai = Pegawai::where('user_id', $user->id)->first();
                $pegawaiId = $pegawai?->id;

                if ($pegawaiId) {
                    $recentIzin = Izin::where('pegawai_id', $pegawaiId)->latest()->take(3)->get();
                    $recentAbsen = PengajuanAbsen::where('pegawai_id', $pegawaiId)->latest()->take(3)->get();
                    $recentPengaduan = Pengaduan::where('pegawai_id', $pegawaiId)->latest()->take(3)->get();
                    $recentReports = Report::where('pegawai_id', $pegawaiId)->orWhere('user_id', $user->id)->latest()->take(3)->get();

                    // Pending count for employee's own requests
                    $pendingCount = Izin::where('pegawai_id', $pegawaiId)->where('status', 'pending')->count()
                        + PengajuanAbsen::where('pegawai_id', $pegawaiId)->where('status', 'pending')->count()
                        + Pengaduan::where('pegawai_id', $pegawaiId)->where('status', 'pending')->count()
                        + Report::where(function($q) use ($pegawaiId, $user) {
                            $q->where('pegawai_id', $pegawaiId)->orWhere('user_id', $user->id);
                        })->whereIn('status', ['submitted', 'revision_required'])->count();

                    $badgeCounts['total'] = $pendingCount;

                    foreach ($recentIzin as $item) {
                        $isApproved = $item->status === 'approved';
                        $isRejected = $item->status === 'rejected';
                        $statusText = $isApproved ? 'disetujui' : ($isRejected ? 'ditolak' : 'sedang diproses');
                        $notifications->push([
                            'title' => 'Pengajuan Izin ' . ucfirst($item->jenis_izin ?? 'Cuti'),
                            'desc' => 'Status pengajuan Anda: ' . $statusText,
                            'time' => $item->updated_at ?? $item->created_at,
                            'url' => route('izin.index'),
                            'icon' => 'calendar_today',
                            'color' => $isApproved ? 'text-emerald-500 bg-emerald-50 dark:bg-emerald-950/60 dark:text-emerald-400' : ($isRejected ? 'text-rose-500 bg-rose-50 dark:bg-rose-950/60 dark:text-rose-400' : 'text-amber-500 bg-amber-50 dark:bg-amber-950/60 dark:text-amber-400'),
                        ]);
                    }

                    foreach ($recentAbsen as $item) {
                        $isApproved = $item->status === 'approved';
                        $isRejected = $item->status === 'rejected';
                        $statusText = $isApproved ? 'disetujui' : ($isRejected ? 'ditolak' : 'sedang diverifikasi');
                        $notifications->push([
                            'title' => 'Klaim Absen: ' . ($item->jenis_absen ?? 'Lupa Absen'),
                            'desc' => 'Status pengajuan Anda: ' . $statusText,
                            'time' => $item->updated_at ?? $item->created_at,
                            'url' => route('absen.index'),
                            'icon' => 'schedule',
                            'color' => $isApproved ? 'text-emerald-500 bg-emerald-50 dark:bg-emerald-950/60 dark:text-emerald-400' : ($isRejected ? 'text-rose-500 bg-rose-50 dark:bg-rose-950/60 dark:text-rose-400' : 'text-blue-500 bg-blue-50 dark:bg-blue-950/60 dark:text-blue-400'),
                        ]);
                    }

                    foreach ($recentReports as $item) {
                        $statusText = match($item->status) {
                            'approved' => 'disetujui (Approved)',
                            'revision_required' => 'butuh revisi',
                            'rejected' => 'ditolak',
                            default => 'dalam review',
                        };
                        $notifications->push([
                            'title' => 'Laporan Pajokka / SPPD',
                            'desc' => 'Status dokumen: ' . $statusText,
                            'time' => $item->updated_at ?? $item->created_at,
                            'url' => route('pajokka.index'),
                            'icon' => 'flight_takeoff',
                            'color' => $item->status === 'approved' ? 'text-emerald-500 bg-emerald-50 dark:bg-emerald-950/60 dark:text-emerald-400' : ($item->status === 'revision_required' ? 'text-amber-500 bg-amber-50 dark:bg-amber-950/60 dark:text-amber-400' : 'text-blue-500 bg-blue-50 dark:bg-blue-950/60 dark:text-blue-400'),
                        ]);
                    }
                }
            }

            $notifications = $notifications->sortByDesc('time')->values()->take(6);

            $view->with([
                'badgeCounts' => $badgeCounts,
                'headerNotifications' => $notifications,
            ]);
        });
    }
}
