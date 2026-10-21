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

    private function authorizeAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
    }
}
