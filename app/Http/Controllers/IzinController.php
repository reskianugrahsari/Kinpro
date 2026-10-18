<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IzinController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Izin::with('pegawai');

        if ($user->role === 'pegawai') {
            $pegawai = Pegawai::where('user_id', $user->id)->first();
            if ($pegawai) {
                $query->where('pegawai_id', $pegawai->id);
            } else {
                $query->where('pegawai_id', 0);
            }
        }

        $izins = $query->latest()->paginate(10);
        
        $pegawai = Pegawai::where('user_id', $user->id)->first();
        $jumlahBulanIni = 0;
        $sisaKuota = 4;

        if ($pegawai) {
            $currentMonth = Carbon::now()->format('Y-m');
            $jumlahBulanIni = Izin::where('pegawai_id', $pegawai->id)
                ->whereRaw("strftime('%Y-%m', created_at) = ?", [$currentMonth])
                ->count();
            $sisaKuota = max(0, 4 - $jumlahBulanIni);
        }

        return view('izin.index', compact('izins', 'jumlahBulanIni', 'sisaKuota'));
    }

    public function create()
    {
        $user = auth()->user();
        $pegawai = Pegawai::where('user_id', $user->id)->first();
        
        if (!$pegawai && $user->role === 'pegawai') {
            return redirect()->route('izin.index')->with('error', 'Data pegawai tidak ditemukan.');
        }

        return view('izin.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $pegawai = Pegawai::where('user_id', $user->id)->first();

        if (!$pegawai && $user->role === 'pegawai') {
            return redirect()->route('izin.index')->with('error', 'Data pegawai tidak ditemukan.');
        }

        // If admin, they can pick pegawai_id or default to first
        $pegawaiId = $pegawai ? $pegawai->id : $request->pegawai_id;

        $request->validate([
            'jenis_izin' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string',
            'dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Check quota (max 4x per month)
        $currentMonth = Carbon::now()->format('Y-m');
        $totalBulanIni = Izin::where('pegawai_id', $pegawaiId)
            ->whereRaw("strftime('%Y-%m', created_at) = ?", [$currentMonth])
            ->count();

        if ($totalBulanIni >= 4) {
            return back()->withErrors(['jenis_izin' => 'Batas kuota pengajuan izin/cuti sudah habis (maksimal 4 kali per bulan).'])->withInput();
        }

        $dokumenPath = null;
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/izin'), $filename);
            $dokumenPath = 'uploads/izin/' . $filename;
        }

        Izin::create([
            'pegawai_id' => $pegawaiId,
            'jenis_izin' => $request->jenis_izin,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan' => $request->keterangan,
            'dokumen' => $dokumenPath,
            'status' => 'pending',
        ]);

        return redirect()->route('izin.index')->with('success', 'Pengajuan izin/cuti berhasil dikirim.');
    }

    public function updateStatus(Request $request, Izin $izin)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan_admin' => 'nullable|string',
        ]);

        $izin->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        return redirect()->route('izin.index')->with('success', 'Status pengajuan izin berhasil diperbarui.');
    }

    public function destroy(Izin $izin)
    {
        $user = auth()->user();
        $pegawai = Pegawai::where('user_id', $user->id)->first();

        if ($user->role === 'pegawai' && (!$pegawai || $izin->pegawai_id !== $pegawai->id || $izin->status !== 'pending')) {
            abort(403);
        }

        $izin->delete();
        return redirect()->route('izin.index')->with('success', 'Pengajuan izin berhasil dibatalkan/dihapus.');
    }
}
