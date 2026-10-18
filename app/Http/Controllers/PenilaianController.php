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

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('pegawai', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $penilaians = $query->latest()->paginate(10);
        $pegawais = Pegawai::all();

        return view('penilaian.index', compact('penilaians', 'pegawais'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        $pegawais = Pegawai::all();
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
