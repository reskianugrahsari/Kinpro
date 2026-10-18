<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use Illuminate\Http\Request;

class UnitKerjaController extends Controller
{
    public function index()
    {
        $unitKerjas = UnitKerja::withCount('pegawai')->latest()->paginate(10);
        return view('admin.unit-kerja.index', compact('unitKerjas'));
    }

    public function create()
    {
        return view('admin.unit-kerja.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:unit_kerja,kode',
        ]);

        UnitKerja::create($request->all());

        return redirect()->route('admin.unit-kerja.index')->with('success', 'Unit Kerja berhasil ditambahkan.');
    }

    public function edit(UnitKerja $unitKerja)
    {
        return view('admin.unit-kerja.edit', compact('unitKerja'));
    }

    public function update(Request $request, UnitKerja $unitKerja)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:unit_kerja,kode,' . $unitKerja->id,
        ]);

        $unitKerja->update($request->all());

        return redirect()->route('admin.unit-kerja.index')->with('success', 'Unit Kerja berhasil diperbarui.');
    }

    public function destroy(UnitKerja $unitKerja)
    {
        $unitKerja->delete();
        return redirect()->route('admin.unit-kerja.index')->with('success', 'Unit Kerja berhasil dihapus.');
    }
}
