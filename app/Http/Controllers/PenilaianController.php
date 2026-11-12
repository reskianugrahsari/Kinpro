<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Penilaian::with(['pegawai.unitKerja']);

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
                })->orWhere('predikat', 'like', "%{$search}%")
                  ->orWhere('masukan_atasan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tahun') && $request->tahun !== 'Semua') {
            $query->where('tahun', $request->tahun);
        }

        if ($request->filled('bulan') && $request->bulan !== 'Semua') {
            $query->where('bulan', $request->bulan);
        }

        if ($request->filled('predikat') && $request->predikat !== 'Semua' && $request->predikat !== 'Semua Predikat') {
            $query->where('predikat', 'like', "%{$request->predikat}%");
        }

        if ($request->filled('unit_kerja_id') && $request->unit_kerja_id !== 'Semua') {
            $query->whereHas('pegawai', function($pq) use ($request) {
                $pq->where('unit_kerja_id', $request->unit_kerja_id);
            });
        }

        $perPage = in_array((int)$request->input('per_page', 10), [5, 10, 25, 50]) ? (int)$request->input('per_page', 10) : 10;
        $penilaians = $query->latest()->paginate($perPage)->withQueryString();

        $allPenilaians = Penilaian::all();
        $totalEvaluasi = $allPenilaians->count() ?: 33;
        $rataRataSkor = $allPenilaians->count() ? round((float)$allPenilaians->avg('rata_rata'), 2) : 82.40;

        $sbCount = $allPenilaians->filter(fn($p) => str_contains($p->predikat, 'Sangat') || $p->predikat === 'A')->count();
        $bCount = $allPenilaians->filter(fn($p) => (str_contains($p->predikat, 'Baik') && !str_contains($p->predikat, 'Sangat')) || $p->predikat === 'B')->count();
        $cCount = $allPenilaians->filter(fn($p) => str_contains($p->predikat, 'Cukup') || $p->predikat === 'C')->count();
        $bpCount = $allPenilaians->filter(fn($p) => str_contains($p->predikat, 'Butuh') || str_contains($p->predikat, 'Kurang') || $p->predikat === 'D')->count();

        if ($allPenilaians->isEmpty()) {
            $sbCount = 16;
            $bCount = 12;
            $cCount = 4;
            $bpCount = 1;
        }

        $totalPredikat = max(1, $sbCount + $bCount + $cCount + $bpCount);
        $pctSB = round(($sbCount / $totalPredikat) * 100, 1);
        $pctBP = round(($bpCount / $totalPredikat) * 100, 1);

        $aspekAverages = [
            'disiplin' => $allPenilaians->count() ? round((float)$allPenilaians->avg('nilai_kedisiplinan'), 2) : 84.00,
            'kinerja' => $allPenilaians->count() ? round((float)$allPenilaians->avg('kinerja'), 2) : 81.00,
            'sikap' => $allPenilaians->count() ? round((float)$allPenilaians->avg('sikap'), 2) : 82.50,
            'kepemimpinan' => $allPenilaians->count() ? round((float)$allPenilaians->avg('kepemimpinan'), 2) : 79.00,
            'loyalitas' => $allPenilaians->count() ? round((float)$allPenilaians->avg('loyalitas'), 2) : 86.50,
            'it' => $allPenilaians->count() ? round((float)$allPenilaians->avg('it'), 2) : 74.20,
        ];

        $aspectLabels = [
            'disiplin' => 'Kedisiplinan',
            'kinerja' => 'Kinerja',
            'sikap' => 'Sikap',
            'kepemimpinan' => 'Kepemimpinan',
            'loyalitas' => 'Loyalitas',
            'it' => 'IT',
        ];

        $sortedAspects = $aspekAverages;
        arsort($sortedAspects);
        $topKey = array_key_first($sortedAspects);
        $lowKey = array_key_last($sortedAspects);

        $topAspect = [
            'key' => $topKey,
            'name' => $aspectLabels[$topKey] ?? 'Loyalitas',
            'score' => $sortedAspects[$topKey],
        ];

        $lowAspect = [
            'key' => $lowKey,
            'name' => $aspectLabels[$lowKey] ?? 'IT',
            'score' => $sortedAspects[$lowKey],
        ];

        $aspekDisplay = [
            ['key' => 'disiplin', 'label' => '1. Disiplin Kehadiran & Ketepatan Jam Kerja', 'score' => $aspekAverages['disiplin']],
            ['key' => 'kinerja', 'label' => '2. Kinerja & Output Target Realisasi SKP', 'score' => $aspekAverages['kinerja']],
            ['key' => 'sikap', 'label' => '3. Sikap Kerja, Etika & Integritas ASN', 'score' => $aspekAverages['sikap']],
            ['key' => 'kepemimpinan', 'label' => '4. Kepemimpinan & Inisiatif Solutif', 'score' => $aspekAverages['kepemimpinan']],
            ['key' => 'loyalitas', 'label' => '5. Loyalitas & Kepatuhan Instruksi Kedinasan', 'score' => $aspekAverages['loyalitas']],
            ['key' => 'it', 'label' => '6. Penguasaan Teknologi & Aplikasi e-Kinerja PUPR', 'score' => $aspekAverages['it']],
        ];

        $unitKerjas = \App\Models\UnitKerja::all();
        $pegawais = Pegawai::all();
        $totalPegawai = $pegawais->count() ?: 33;

        $years = Penilaian::select('tahun')->distinct()->pluck('tahun')->toArray();
        if (empty($years)) {
            $years = [2026, 2025, 2024];
        } else {
            if (!in_array(2026, $years)) $years[] = 2026;
            rsort($years);
        }

        $predikatCounts = [
            'SB' => $sbCount,
            'B' => $bCount,
            'C' => $cCount,
            'BP' => $bpCount,
        ];

        return view('penilaian.index', compact(
            'penilaians',
            'pegawais',
            'unitKerjas',
            'totalEvaluasi',
            'rataRataSkor',
            'predikatCounts',
            'pctSB',
            'pctBP',
            'topAspect',
            'lowAspect',
            'aspekDisplay',
            'years',
            'totalPegawai'
        ));
    }

    public function create()
    {
        $this->authorizeAdmin();
        $pegawais = Pegawai::with('unitKerja')->get();
        return view('penilaian.create', compact('pegawais'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'bulan' => 'required|string',
            'tahun' => 'required|integer|min:2000|max:2100',
            'nilai_kedisiplinan' => 'required|integer|min:0|max:100',
            'kinerja' => 'required|integer|min:0|max:100',
            'sikap' => 'required|integer|min:0|max:100',
            'kepemimpinan' => 'required|integer|min:0|max:100',
            'loyalitas' => 'required|integer|min:0|max:100',
            'it' => 'required|integer|min:0|max:100',
            'masukan_atasan' => 'nullable|string',
        ]);

        Penilaian::create($request->all());

        return redirect()->route('penilaian.index')->with('success', 'Penilaian kinerja berhasil disimpan.');
    }

    public function edit(Penilaian $penilaian)
    {
        $this->authorizeAdmin();
        $pegawais = Pegawai::all();
        return view('penilaian.edit', compact('penilaian', 'pegawais'));
    }

    public function update(Request $request, Penilaian $penilaian)
    {
        $this->authorizeAdmin();

        $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'bulan' => 'required|string',
            'tahun' => 'required|integer|min:2000|max:2100',
            'nilai_kedisiplinan' => 'required|integer|min:0|max:100',
            'kinerja' => 'required|integer|min:0|max:100',
            'sikap' => 'required|integer|min:0|max:100',
            'kepemimpinan' => 'required|integer|min:0|max:100',
            'loyalitas' => 'required|integer|min:0|max:100',
            'it' => 'required|integer|min:0|max:100',
            'masukan_atasan' => 'nullable|string',
        ]);

        $penilaian->update($request->all());

        return redirect()->route('penilaian.index')->with('success', 'Penilaian kinerja berhasil diperbarui.');
    }

    public function destroy(Penilaian $penilaian)
    {
        $this->authorizeAdmin();
        $penilaian->delete();
        return redirect()->route('penilaian.index')->with('success', 'Penilaian kinerja berhasil dihapus.');
    }

    public function rekap(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Administrator yang dapat melihat dan melakukan Rekap SKP.');
        }

        $tahun = (int) $request->input('tahun', 2026);
        $periode = $request->input('periode', 'tw3'); // tw1, tw2, tw3, tw4, tahunan

        $periodeMonths = match ($periode) {
            'tw1' => ['Januari', 'Februari', 'Maret'],
            'tw2' => ['April', 'Mei', 'Juni'],
            'tw3' => ['Juli', 'Agustus', 'September'],
            'tw4' => ['Oktober', 'November', 'Desember'],
            default => ['Juli', 'Agustus', 'September'],
        };

        $periodeTitle = match ($periode) {
            'tw1' => 'TRIWULAN I (TW1)',
            'tw2' => 'TRIWULAN II (TW2)',
            'tw3' => 'TRIWULAN III (TW3)',
            'tw4' => 'TRIWULAN IV (TW4)',
            'tahunan' => 'TAHUNAN',
            default => 'TRIWULAN III (TW3)',
        };

        // Ambil data penilaian aktual sesuai inputan di database
        $penilaiansQuery = Penilaian::with(['pegawai.unitKerja'])
            ->where('tahun', $tahun);

        if ($periode !== 'tahunan') {
            $penilaiansQuery->whereIn('bulan', $periodeMonths);
        }

        $penilaians = $penilaiansQuery->get();

        $grouped = $penilaians->groupBy('pegawai_id');

        $sangatBaikList = [];
        $baikList = [];
        $cukupList = [];
        $kurangList = [];

        foreach ($grouped as $pegId => $ratings) {
            $peg = $ratings->first()->pegawai;
            if (!$peg) continue;

            $avg = round((float) ($ratings->avg('rata_rata') ?: $ratings->first()->rata_rata), 2);
            $lastPred = trim((string) $ratings->last()->predikat);

            // Tentukan predikat berdasarkan rata-rata skor atau inputan
            if (str_contains(strtolower($lastPred), 'sangat') || $avg >= 85) {
                $predikat = 'Sangat Baik';
            } elseif (str_contains(strtolower($lastPred), 'baik') || $avg >= 75) {
                $predikat = 'Baik';
            } elseif (str_contains(strtolower($lastPred), 'cukup') || str_contains(strtolower($lastPred), 'butuh') || $avg >= 60) {
                $predikat = 'Cukup';
            } else {
                $predikat = 'Kurang';
            }

            $item = [
                'id' => $peg->id,
                'nama' => $peg->nama_lengkap ?? $peg->nama,
                'nip' => $peg->nip,
                'jabatan' => $peg->jabatan,
                'avg' => $avg,
                'predikat' => $predikat,
                'ratings_count' => $ratings->count(),
            ];

            if ($predikat === 'Sangat Baik') {
                $sangatBaikList[] = $item;
            } elseif ($predikat === 'Baik') {
                $baikList[] = $item;
            } elseif ($predikat === 'Cukup') {
                $cukupList[] = $item;
            } else {
                $kurangList[] = $item;
            }
        }

        // Susun urutan: Peringkat nilai rata-rata hasil penilaian tertinggi ke terendah, lalu hierarki pimpinan
        $sortFn = function ($a, $b) {
            if ($b['avg'] != $a['avg']) {
                return $b['avg'] <=> $a['avg'];
            }
            $rankA = 99;
            $rankB = 99;
            $jabA = strtolower($a['jabatan'] ?? '');
            $jabB = strtolower($b['jabatan'] ?? '');

            if (str_contains($jabA, 'kepala balai')) $rankA = 1;
            elseif (str_contains($jabA, 'subbag') || str_contains($jabA, 'tu')) $rankA = 2;
            elseif (str_contains($jabA, 'penyelenggara')) $rankA = 3;

            if (str_contains($jabB, 'kepala balai')) $rankB = 1;
            elseif (str_contains($jabB, 'subbag') || str_contains($jabB, 'tu')) $rankB = 2;
            elseif (str_contains($jabB, 'penyelenggara')) $rankB = 3;

            if ($rankA !== $rankB) {
                return $rankA <=> $rankB;
            }

            return strcmp($a['nama'], $b['nama']);
        };

        usort($sangatBaikList, $sortFn);
        usort($baikList, $sortFn);
        usort($cukupList, $sortFn);
        usort($kurangList, $sortFn);

        $countSB = count($sangatBaikList);
        $countB = count($baikList);
        $countC = count($cukupList);
        $countK = count($kurangList);

        $totalEvaluasi = $countSB + $countB + $countC + $countK;
        $totalPegawaiDinilai = $totalEvaluasi;
        $totalPegawaiBalai = Pegawai::count() ?: max(1, $totalEvaluasi);

        $pctSB = $totalEvaluasi > 0 ? number_format(($countSB / $totalEvaluasi) * 100, 1, ',', '.') : '0,0';
        $pctB = $totalEvaluasi > 0 ? number_format(($countB / $totalEvaluasi) * 100, 1, ',', '.') : '0,0';
        $pctPenilaian = $totalPegawaiBalai > 0 ? number_format(($totalEvaluasi / $totalPegawaiBalai) * 100, 0) : '100';

        $years = [2026, 2025, 2024];

        $kepalaBalai = Pegawai::where('jabatan', 'like', '%Kepala Balai%')
            ->orWhere('nama', 'like', '%Widyanto Hendro Saputro%')
            ->first();

        return view('penilaian.rekap', compact(
            'tahun',
            'periode',
            'periodeTitle',
            'totalPegawaiDinilai',
            'totalPegawaiBalai',
            'sangatBaikList',
            'baikList',
            'cukupList',
            'kurangList',
            'countSB',
            'countB',
            'countC',
            'countK',
            'pctSB',
            'pctB',
            'pctPenilaian',
            'years',
            'kepalaBalai'
        ));
    }

    private function authorizeAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
    }
}
