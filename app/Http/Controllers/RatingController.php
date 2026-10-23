<?php

namespace App\Http\Controllers;

use App\Models\RatingKomentar;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::with(['unitKerja', 'ratings.penilai'])
            ->withCount('ratings')
            ->withAvg('ratings', 'rating');

        if ($request->filled('unit_id') && $request->unit_id !== 'all' && $request->unit_id !== 'Semua Unit') {
            $query->where('unit_kerja_id', $request->unit_id);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('nama_lengkap', 'like', "%{$s}%")
                  ->orWhere('nip', 'like', "%{$s}%")
                  ->orWhere('jabatan', 'like', "%{$s}%");
            });
        }

        $sort = $request->input('sort', 'highest');
        switch ($sort) {
            case 'reviews':
                $query->orderByDesc('ratings_count')->orderByDesc('ratings_avg_rating');
                break;
            case 'name':
                $query->orderBy('nama');
                break;
            case 'lowest':
                $query->orderBy('ratings_avg_rating')->orderBy('nama');
                break;
            case 'highest':
            default:
                $query->orderByDesc('ratings_avg_rating')->orderByDesc('ratings_count')->orderBy('nama');
                break;
        }

        $pegawais = $query->paginate(9)->withQueryString();

        $allRatings = RatingKomentar::all();
        $totalReviews = $allRatings->count();
        $avgBalai = $totalReviews > 0 ? round($allRatings->avg('rating'), 1) : 4.8;
        $totalPegawai = Pegawai::count();

        $topRatedPegawai = Pegawai::with(['unitKerja'])
            ->withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->orderByDesc('ratings_count')
            ->first();

        $unitKerjas = \App\Models\UnitKerja::where('id', '!=', 1)
            ->where('nama_unit', 'not like', '%Balai%')
            ->get();

        return view('rating.index', compact(
            'pegawais',
            'allRatings',
            'totalReviews',
            'avgBalai',
            'totalPegawai',
            'topRatedPegawai',
            'unitKerjas'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string',
        ]);

        RatingKomentar::create([
            'pegawai_id' => $validated['pegawai_id'],
            'penilai_id' => auth()->id(),
            'rating' => $validated['rating'],
            'komentar' => $validated['komentar'],
        ]);

        return back()->with('success', 'Rating dan komentar berhasil ditambahkan.');
    }

    public function destroy(RatingKomentar $rating)
    {
        if (auth()->user()->role !== 'admin' && $rating->penilai_id !== auth()->id()) {
            abort(403);
        }

        $rating->delete();
        return back()->with('success', 'Rating berhasil dihapus.');
    }
}
