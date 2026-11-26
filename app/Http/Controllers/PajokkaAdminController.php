<?php

namespace App\Http\Controllers;

use App\Models\KopSurat;
use App\Models\MasterPpk;
use App\Models\Report;
use App\Models\ReportExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PajokkaAdminController extends Controller
{
    public function index(Request $request)
    {
        abort_if(! auth()->user()->isAdmin(), 403, 'Halaman ini khusus untuk Administrator.');

        $activeTab = $request->input('tab', 'laporan');
        if (! in_array($activeTab, ['laporan', 'master-ppk', 'kop-surat', 'rekap'], true)) {
            $activeTab = 'laporan';
        }

        // KPI Summary across all reports
        $totalCount = Report::count();
        $pendingCount = Report::whereIn('status', ['submitted', 'pending', 'under_review'])->count();
        $approvedCount = Report::where('status', 'approved')->count();
        $revisionCount = Report::whereIn('status', ['revision', 'revision_required', 'rejected'])->count();

        // Expense summary (schema-safe across amount/nominal/jumlah and payment_method/metode_pembayaran)
        $amountCol = Schema::hasColumn('report_expenses', 'amount')
            ? 'amount'
            : (Schema::hasColumn('report_expenses', 'nominal') ? 'nominal' : 'jumlah');
        $methodCol = Schema::hasColumn('report_expenses', 'payment_method')
            ? 'payment_method'
            : 'metode_pembayaran';

        $totalKkp = (float) ReportExpense::whereRaw("LOWER({$methodCol}) = 'kkp'")->sum($amountCol);
        $totalCash = (float) ReportExpense::whereRaw("LOWER({$methodCol}) IN ('cash', 'tunai')")->sum($amountCol);
        $totalAnggaran = (float) ReportExpense::sum($amountCol);

        // Filterable reports list for Tab 1 (Verifikasi & Daftar Laporan)
        $query = Report::with(['user.pegawai', 'ppk', 'masterPpk', 'pegawai', 'expenses']);

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('nomor_surat_tugas', 'like', "%{$term}%")
                    ->orWhere('nomor_surat', 'like', "%{$term}%")
                    ->orWhere('kota_tujuan', 'like', "%{$term}%")
                    ->orWhere('tujuan', 'like', "%{$term}%")
                    ->orWhere('isi_pointer', 'like', "%{$term}%")
                    ->orWhere('maksud_perjalanan', 'like', "%{$term}%")
                    ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$term}%"))
                    ->orWhereHas('pegawai', fn ($pq) => $pq->where('nama', 'like', "%{$term}%")->orWhere('nip', 'like', "%{$term}%"));
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'pending') {
                $query->whereIn('status', ['submitted', 'pending', 'under_review']);
            } elseif ($request->status === 'revision') {
                $query->whereIn('status', ['revision', 'revision_required', 'rejected']);
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('tujuan')) {
            $tujuan = $request->tujuan;
            $query->where(function ($q) use ($tujuan) {
                $q->where('kota_tujuan', 'like', "%{$tujuan}%")
                    ->orWhere('tujuan', 'like', "%{$tujuan}%");
            });
        }

        if ($request->filled('tahun')) {
            $query->whereYear('created_at', (int) $request->tahun);
        }

        $reports = $query->latest()->paginate(10)->withQueryString();

        $tujuanList = Report::query()
            ->selectRaw('COALESCE(NULLIF(kota_tujuan, ""), tujuan) as tujuan_nama')
            ->where(function ($q) {
                $q->whereNotNull('tujuan')->orWhereNotNull('kota_tujuan');
            })
            ->distinct()
            ->pluck('tujuan_nama')
            ->filter()
            ->sort()
            ->values();

        $tahunList = Report::query()
            ->selectRaw('YEAR(created_at) as yr')
            ->distinct()
            ->orderByDesc('yr')
            ->pluck('yr')
            ->filter()
            ->values();

        // Tab 2: Master PPK
        $ppkQuery = MasterPpk::query();
        if (Schema::hasColumn('master_ppk', 'is_active')) {
            $ppkQuery->orderByDesc('is_active');
        }
        $ppks = $ppkQuery->latest()->get();

        // Tab 3: Kop Surat
        $kop = KopSurat::first();
        $kopPreview = KopSurat::displayData($kop);

        // Tab 4: Rekap per Kota Tujuan & Status (computed in PHP to avoid MySQL ONLY_FULL_GROUP_BY)
        $rekapByTujuan = $activeTab === 'rekap'
            ? Report::with('expenses')
                ->get(['id', 'kota_tujuan', 'tujuan', 'total_cost', 'total_biaya'])
                ->groupBy(function ($r) {
                    $city = trim((string) ($r->kota_tujuan ?: $r->tujuan));
                    return $city !== '' ? $city : 'Lainnya';
                })
                ->map(function ($group, $tujuanNama) {
                    return (object) [
                        'tujuan_nama' => $tujuanNama,
                        'total_laporan' => $group->count(),
                        'total_biaya' => $group->sum(function ($r) {
                            if ((float) $r->total_cost > 0) {
                                return (float) $r->total_cost;
                            }
                            if ((float) $r->total_biaya > 0) {
                                return (float) $r->total_biaya;
                            }
                            return (float) $r->expenses->sum('amount');
                        }),
                    ];
                })
                ->sortByDesc('total_laporan')
                ->take(10)
                ->values()
            : collect();

        return view('admin.pajokka.index', compact(
            'activeTab',
            'reports',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'revisionCount',
            'totalKkp',
            'totalCash',
            'totalAnggaran',
            'tujuanList',
            'tahunList',
            'ppks',
            'kop',
            'kopPreview',
            'rekapByTujuan'
        ));
    }

    public function updateKopSurat(Request $request)
    {
        abort_if(! auth()->user()->isAdmin(), 403, 'Hanya Administrator yang dapat mengubah Kop Surat.');

        $validated = $request->validate([
            'nama_kementerian' => 'required|string|max:255',
            'nama_badan' => 'required|string|max:255',
            'nama_unit_kerja' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:100',
            'email' => 'nullable|string|max:150',
            'website' => 'nullable|string|max:255',
        ]);

        $kop = KopSurat::first();
        if ($kop) {
            $kop->update($validated);
        } else {
            KopSurat::create($validated);
        }

        return redirect()
            ->route('admin.pajokka.index', ['tab' => 'kop-surat'])
            ->with('success', 'Pengaturan Kop Surat laporan perjalanan dinas berhasil diperbarui.');
    }
}
