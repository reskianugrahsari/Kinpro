<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::with(['unitKerja', 'user', 'latestPenilaian'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'PPPK') {
                $query->where(function ($q) {
                    $q->where('pangkat_gol', 'like', '%PPPK%')
                      ->orWhere('nip', 'like', '____________21%');
                });
            } elseif ($request->status === 'PNS') {
                $query->where(function ($q) {
                    $q->where(function ($sq) {
                        $sq->whereNull('pangkat_gol')
                           ->orWhere('pangkat_gol', 'not like', '%PPPK%');
                    })->where('nip', 'not like', '____________21%');
                });
            }
        }

        if ($request->filled('jabatan')) {
            $query->where('jabatan', $request->jabatan);
        }

        $pegawais = $query->paginate(12)->appends($request->all());

        $jabatanList = Pegawai::whereNotNull('jabatan')
            ->where('jabatan', '!=', '')
            ->distinct()
            ->orderBy('jabatan')
            ->pluck('jabatan');

        return view('admin.pegawai.index', compact('pegawais', 'jabatanList'));
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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $fotoProfil = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fotoProfil = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->move(public_path('uploads'), $fotoProfil);
        }

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
            'foto_profil' => $fotoProfil,
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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $data = $request->only([
            'nama', 'nip', 'jabatan', 'pangkat_gol', 'unit_kerja_id', 'no_hp'
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->move(public_path('uploads'), $filename);
            $data['foto_profil'] = $filename;
        }

        $pegawai->update($data);

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
