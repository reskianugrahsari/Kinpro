<?php

namespace App\Http\Controllers;

use App\Models\RatingKomentar;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index(Request $request)
    {
        $pegawais = Pegawai::withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->paginate(12)
            ->withQueryString();

        return view('rating.index', compact('pegawais'));
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
