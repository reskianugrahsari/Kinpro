<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportExpense;
use App\Models\Pegawai;
use App\Models\MasterPpk;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Report::with(['pegawai.unitKerja', 'masterPpk', 'expenses'])->latest();

        if ($user->role !== 'admin') {
            $pegawai = Pegawai::where('user_id', $user->id)->first();
            $query->where('pegawai_id', $pegawai ? $pegawai->id : 0);
        }

        $reports = $query->paginate(10)->withQueryString();
        return view('pajokka.index', compact('reports'));
    }

    public function create()
    {
        $pegawais = Pegawai::with('unitKerja')->get();
        $ppks = MasterPpk::all();
        $unitKerjas = UnitKerja::all();
        return view('pajokka.create', compact('pegawais', 'ppks', 'unitKerjas'));
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
            'nomor_surat' => 'required|string|max:255',
            'tanggal_berangkat' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_berangkat',
            'tujuan' => 'required|string|max:255',
            'maksud_perjalanan' => 'required|string',
            'master_ppk_id' => 'required|exists:master_ppk,id',
            'expenses' => 'required|array|min:1',
            'expenses.*.uraian' => 'required|string',
            'expenses.*.nominal' => 'required|numeric|min:0',
            'expenses.*.metode_pembayaran' => 'required|in:KKP,Cash,Keduanya',
        ]);

        $report = Report::create([
            'pegawai_id' => $pegawaiId,
            'nomor_surat' => $validated['nomor_surat'],
            'tanggal_berangkat' => $validated['tanggal_berangkat'],
            'tanggal_kembali' => $validated['tanggal_kembali'],
            'tujuan' => $validated['tujuan'],
            'maksud_perjalanan' => $validated['maksud_perjalanan'],
            'master_ppk_id' => $validated['master_ppk_id'],
            'status' => 'draft',
        ]);

        foreach ($validated['expenses'] as $exp) {
            ReportExpense::create([
                'report_id' => $report->id,
                'uraian' => $exp['uraian'],
                'nominal' => $exp['nominal'],
                'metode_pembayaran' => $exp['metode_pembayaran'],
            ]);
        }

        return redirect()->route('pajokka.index')->with('success', 'Perjalanan dinas berhasil dibuat.');
    }

    public function show(Report $report)
    {
        $report->load(['pegawai.unitKerja', 'masterPpk', 'expenses']);
        return view('pajokka.show', compact('report'));
    }

    public function exportDocx(Report $report)
    {
        $report->load(['pegawai.unitKerja', 'masterPpk', 'expenses']);
        $filename = 'SPPD_' . str_replace(['/', '\\'], '_', $report->nomor_surat) . '.doc';
        $html = view('pajokka.docx', compact('report'))->render();

        return response($html, 200, [
            'Content-Type' => 'application/msword',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function updateStatus(Request $request, Report $report)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:draft,submitted,approved,revision,rejected',
        ]);

        $report->update(['status' => $validated['status']]);

        return back()->with('success', 'Status laporan perjalanan dinas berhasil diperbarui.');
    }

    public function destroy(Report $report)
    {
        $user = auth()->user();
        if ($user->role !== 'admin' && $report->status !== 'draft') {
            abort(403);
        }

        $report->expenses()->delete();
        $report->delete();

        return redirect()->route('pajokka.index')->with('success', 'Laporan perjalanan dinas berhasil dihapus.');
    }
}
