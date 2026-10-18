<?php

namespace App\Http\Controllers;

use App\Models\PengajuanAbsen;
use App\Models\Pegawai;
use App\Models\MasterPpk;
use Illuminate\Http\Request;

class PengajuanAbsenController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = PengajuanAbsen::with('pegawai.unitKerja')->latest();

        if ($user->role !== 'admin') {
            $pegawai = Pegawai::where('user_id', $user->id)->first();
            $query->where('pegawai_id', $pegawai ? $pegawai->id : 0);
        } else {
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('pegawai', function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nip', 'like', "%{$search}%");
                });
            }
        }

        $pengaduans = $query->paginate(10)->withQueryString();
        return view('absen.index', compact('pengaduans'));
    }

    public function create()
    {
        $pegawais = Pegawai::with('unitKerja')->get();
        $ppks = MasterPpk::all();
        return view('absen.create', compact('pegawais', 'ppks'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $pegawaiId = null;

        if ($user->role === 'admin' && $request->filled('pegawai_id')) {
            $pegawaiId = $request->pegawai_id;
        } else {
            $pegawai = Pegawai::where('user_id', $user->id)->first();
            if (!$pegawai) {
                return back()->with('error', 'Profil pegawai tidak ditemukan.');
            }
            $pegawaiId = $pegawai->id;
        }

        $validated = $request->validate([
            'tanggal_absen' => 'required|date',
            'alasan' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'bukti_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_path')) {
            $file = $request->file('bukti_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/absen'), $filename);
            $buktiPath = 'uploads/absen/' . $filename;
        }

        PengajuanAbsen::create([
            'pegawai_id' => $pegawaiId,
            'tanggal_absen' => $validated['tanggal_absen'],
            'alasan' => $validated['alasan'],
            'keterangan' => $validated['keterangan'],
            'bukti_path' => $buktiPath,
            'status' => 'pending',
        ]);

        return redirect()->route('absen.index')->with('success', 'Pengajuan lupa absen berhasil dikirim.');
    }

    public function show(PengajuanAbsen $absen)
    {
        $absen->load('pegawai.unitKerja');
        return view('absen.show', compact('absen'));
    }

    public function updateStatus(Request $request, PengajuanAbsen $absen)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'catatan_admin' => 'nullable|string',
        ]);

        $absen->update([
            'status' => $validated['status'],
            'catatan_admin' => $validated['catatan_admin'],
        ]);

        return back()->with('success', 'Status pengajuan absen berhasil diperbarui.');
    }

    public function destroy(PengajuanAbsen $absen)
    {
        $user = auth()->user();
        if ($user->role !== 'admin' && $absen->status !== 'pending') {
            abort(403);
        }

        $absen->delete();
        return redirect()->route('absen.index')->with('success', 'Pengajuan absen berhasil dihapus.');
    }
}
