<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawais = Pegawai::with(['unitKerja', 'user'])->latest()->paginate(10);
        return view('admin.pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        $unitKerjas = UnitKerja::all();
        return view('admin.pegawai.create', compact('unitKerjas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:pegawai,nip',
            'email' => 'required|email|unique:users,email',
            'jabatan' => 'nullable|string|max:255',
            'pangkat_gol' => 'nullable|string|max:255',
            'unit_kerja_id' => 'nullable|exists:unit_kerja,id',
            'no_hp' => 'nullable|string|max:50',
            'role' => 'required|in:admin,pegawai',
        ]);

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make('password123'),
            'role' => $request->role,
        ]);

        Pegawai::create([
            'user_id' => $user->id,
            'nama' => $request->nama,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'pangkat_gol' => $request->pangkat_gol,
            'unit_kerja_id' => $request->unit_kerja_id,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai & Akun berhasil ditambahkan (Password default: password123).');
    }

    public function edit(Pegawai $pegawai)
    {
        $unitKerjas = UnitKerja::all();
        return view('admin.pegawai.edit', compact('pegawai', 'unitKerjas'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:pegawai,nip,' . $pegawai->id,
            'jabatan' => 'nullable|string|max:255',
            'pangkat_gol' => 'nullable|string|max:255',
            'unit_kerja_id' => 'nullable|exists:unit_kerja,id',
            'no_hp' => 'nullable|string|max:50',
        ]);

        $pegawai->update($request->only([
            'nama', 'nip', 'jabatan', 'pangkat_gol', 'unit_kerja_id', 'no_hp'
        ]));

        if ($pegawai->user) {
            $pegawai->user->update(['name' => $request->nama]);
        }

        return redirect()->route('admin.pegawai.index')->with('success', 'Data Pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        if ($pegawai->user_id) {
            User::where('id', $pegawai->user_id)->delete();
        }
        $pegawai->delete();
        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai berhasil dihapus.');
    }
}
