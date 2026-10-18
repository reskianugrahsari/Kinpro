<?php

namespace App\Http\Controllers;

use App\Models\MasterPpk;
use Illuminate\Http\Request;

class MasterPpkController extends Controller
{
    public function index()
    {
        $ppks = MasterPpk::latest()->paginate(10);
        return view('admin.master-ppk.index', compact('ppks'));
    }

    public function create()
    {
        return view('admin.master-ppk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50',
            'jabatan' => 'required|string|max:255',
        ]);

        MasterPpk::create($request->all());

        return redirect()->route('admin.master-ppk.index')->with('success', 'Master PPK berhasil ditambahkan.');
    }

    public function edit(MasterPpk $masterPpk)
    {
        return view('admin.master-ppk.edit', compact('masterPpk'));
    }

    public function update(Request $request, MasterPpk $masterPpk)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50',
            'jabatan' => 'required|string|max:255',
        ]);

        $masterPpk->update($request->all());

        return redirect()->route('admin.master-ppk.index')->with('success', 'Master PPK berhasil diperbarui.');
    }

    public function destroy(MasterPpk $masterPpk)
    {
        $masterPpk->delete();
        return redirect()->route('admin.master-ppk.index')->with('success', 'Master PPK berhasil dihapus.');
    }
}
