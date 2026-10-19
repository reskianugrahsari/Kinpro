<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Models\MasterPpk;
use App\Models\Report;
use App\Models\ReportAttachment;
use App\Models\ReportExpense;
use App\Models\ReportRevision;
use App\Models\ReportSchedule;
use App\Models\Pegawai;
use App\Models\User;
use App\Services\DocxExportService;
use App\Services\PdfExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['user', 'ppk', 'pegawai']);

        if (auth()->user()->isPegawai()) {
            $user = auth()->user();
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if ($user->pegawai) {
                    $q->orWhere('pegawai_id', $user->pegawai->id);
                }
                if ($user->name) {
                    $q->orWhereJsonContains('daftar_pelaksana', ['name' => $user->name])
                      ->orWhereJsonContains('daftar_pelaksana', [['name' => $user->name]]);
                }
                if ($user->nip) {
                    $q->orWhereJsonContains('daftar_pelaksana', ['nip' => $user->nip])
                      ->orWhereJsonContains('daftar_pelaksana', [['nip' => $user->nip]]);
                }
            });
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('nomor_surat_tugas', 'like', "%{$term}%")
                  ->orWhere('nomor_surat', 'like', "%{$term}%")
                  ->orWhere('kota_tujuan', 'like', "%{$term}%")
                  ->orWhere('tujuan', 'like', "%{$term}%")
                  ->orWhere('maksud_perjalanan', 'like', "%{$term}%")
                  ->orWhereHas('user', function ($uq) use ($term) {
                      $uq->where('name', 'like', "%{$term}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()->paginate(10)->withQueryString();

        return view('pajokka.index', compact('reports'));
    }

    public function create()
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('pajokka.index')->with('error', 'Administrator hanya bertugas mereview laporan perjalanan dinas. Pembuatan laporan hanya dilakukan oleh pegawai.');
        }

        $ppks = MasterPpk::where('is_active', true)->orWhereNull('is_active')->get();
        $pegawai = User::orderBy('name')->get();

        return view('pajokka.create', compact('ppks', 'pegawai'));
    }

    public function store(StoreReportRequest $request)
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('pajokka.index')->with('error', 'Administrator tidak dapat membuat laporan perjalanan dinas.');
        }

        DB::beginTransaction();
        try {
            $pelaksanaIds = $request->pelaksana ?? [auth()->id()];
            $pelaksanaData = User::whereIn('id', $pelaksanaIds)
                ->get(['name', 'email'])
                ->map(function ($u) {
                    return [
                        'name' => $u->name,
                        'nip' => $u->nip ?? ($u->pegawai?->nip ?? '-'),
                    ];
                })
                ->toArray();

            $pegawaiRecord = auth()->user()->pegawai;
            $pegawaiId = $pegawaiRecord ? $pegawaiRecord->id : null;

            $nomorSurat = $request->nomor_surat_tugas ?? $request->nomor_surat;
            $tglMulai = $request->tgl_mulai_dinas ?? $request->tanggal_berangkat;
            $tglSelesai = $request->tgl_selesai_dinas ?? $request->tanggal_kembali;
            $kotaTujuan = $request->kota_tujuan ?? $request->tujuan;

            $report = Report::create([
                'user_id' => auth()->id(),
                'pegawai_id' => $pegawaiId,
                'ppk_id' => $request->ppk_id ?? $request->master_ppk_id,
                'master_ppk_id' => $request->master_ppk_id ?? $request->ppk_id,
                'nomor_surat_tugas' => $nomorSurat,
                'nomor_surat' => $nomorSurat,
                'tanggal_surat_tugas' => $request->tanggal_surat_tugas,
                'tgl_mulai_dinas' => $tglMulai,
                'tgl_selesai_dinas' => $tglSelesai,
                'tanggal_berangkat' => $tglMulai,
                'tanggal_kembali' => $tglSelesai,
                'tanggal_sppd' => $request->tanggal_sppd ?? ($tglSelesai ? \Carbon\Carbon::parse($tglSelesai)->addDays(2)->format('Y-m-d') : null),
                'opsi_tiket' => $request->opsi_tiket ?? 'kkp',
                'kota_tujuan' => $kotaTujuan,
                'tujuan' => $kotaTujuan,
                'maksud_perjalanan' => $request->maksud_perjalanan ?? $request->isi_pointer,
                'daftar_pelaksana' => $pelaksanaData,
                'isi_pointer' => $request->isi_pointer,
                'pointer_substansi' => $request->pointer_substansi,
                'status' => 'submitted',
                'submitted_at' => now(),
                'completeness_score' => 100,
            ]);

            // Save schedules
            if ($request->has('schedules')) {
                foreach ($request->schedules as $schedule) {
                    if (!empty($schedule['tanggal']) && !empty($schedule['uraian_kegiatan'])) {
                        ReportSchedule::create([
                            'report_id' => $report->id,
                            'tanggal' => $schedule['tanggal'],
                            'uraian_kegiatan' => $schedule['uraian_kegiatan'],
                            'lokasi_keterangan' => $schedule['lokasi_keterangan'] ?? null,
                        ]);
                    }
                }
            }

            // Save expenses with per-item payment method (KKP / Cash)
            $totalCost = 0;
            if ($request->has('expenses')) {
                foreach ($request->expenses as $exp) {
                    $amount = (float) ($exp['amount'] ?? ($exp['nominal'] ?? 0));
                    $totalCost += $amount;
                    ReportExpense::create([
                        'report_id' => $report->id,
                        'expense_type' => $exp['expense_type'] ?? ($exp['uraian'] ?? 'Biaya'),
                        'uraian' => $exp['uraian'] ?? ($exp['expense_type'] ?? 'Biaya'),
                        'amount' => $amount,
                        'nominal' => $amount,
                        'payment_method' => strtolower($exp['payment_method'] ?? 'kkp'),
                        'metode_pembayaran' => $exp['metode_pembayaran'] ?? (strtoupper($exp['payment_method'] ?? 'kkp')),
                        'description' => $exp['description'] ?? null,
                    ]);
                }
            }
            $report->update(['total_cost' => $totalCost, 'total_biaya' => $totalCost]);

            // Single file attachments
            $singleCategories = [
                'spt',
                'tiket_pergi',
                'tiket_pulang',
                'taksi_rumah_ke_bandara',
                'taksi_bandara_ke_rumah',
                'taksi_bandara_tujuan_ke_hotel',
                'taksi_hotel_ke_bandara_tujuan',
            ];

            foreach ($singleCategories as $cat) {
                if ($request->hasFile($cat)) {
                    $path = $this->storeOptimizedFile($request->file($cat), "attachments/{$report->id}");
                    ReportAttachment::create([
                        'report_id' => $report->id,
                        'category' => $cat,
                        'file_path' => $path,
                        'uploaded_by' => auth()->id(),
                    ]);
                }
            }

            // Documentation photos (up to 8)
            if ($request->hasFile('dokumentasi')) {
                $order = 0;
                foreach ($request->file('dokumentasi') as $docFile) {
                    if ($order >= 8) break;
                    $path = $this->storeOptimizedFile($docFile, "attachments/{$report->id}/dokumentasi");
                    ReportAttachment::create([
                        'report_id' => $report->id,
                        'category' => 'dokumentasi',
                        'file_path' => $path,
                        'sort_order' => $order,
                        'uploaded_by' => auth()->id(),
                    ]);
                    $order++;
                }
            }

            DB::commit();

            return redirect()->route('pajokka.index')
                ->with('success', 'Laporan perjalanan dinas berhasil dibuat dan diajukan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan laporan: ' . $e->getMessage());
        }
    }

    public function show(Report $report)
    {
        $user = auth()->user();
        if ($user->isPegawai()) {
            $isParticipant = false;
            if (is_array($report->daftar_pelaksana)) {
                foreach ($report->daftar_pelaksana as $p) {
                    if (($p['name'] ?? '') === $user->name || ($p['nip'] ?? '') === $user->nip) {
                        $isParticipant = true;
                        break;
                    }
                }
            }
            if ($report->user_id !== $user->id && !$isParticipant && $report->pegawai_id !== ($user->pegawai?->id ?? null)) {
                abort(403);
            }
        }

        $report->load(['user', 'ppk', 'pegawai', 'schedules', 'attachments', 'expenses', 'revisions.requester']);

        return view('pajokka.show', compact('report'));
    }

    public function approve(Report $report)
    {
        if (auth()->user()->isPegawai()) {
            abort(403);
        }

        $report->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Laporan berhasil disetujui (Approved)!');
    }

    public function requestRevision(Request $request, Report $report)
    {
        if (auth()->user()->isPegawai()) {
            abort(403);
        }

        $request->validate([
            'notes' => 'required|string|min:5',
        ]);

        ReportRevision::create([
            'report_id' => $report->id,
            'requested_by' => auth()->id(),
            'section' => $request->section ?? 'Umum',
            'notes' => $request->notes,
            'created_at' => now(),
        ]);

        $report->update([
            'status' => 'revision_required',
            'revision_count' => $report->revision_count + 1,
        ]);

        return back()->with('success', 'Permintaan revisi berhasil dikirimkan ke pegawai!');
    }

    public function reject(Report $report)
    {
        if (auth()->user()->isPegawai()) {
            abort(403);
        }

        $report->update([
            'status' => 'rejected',
            'rejected_at' => now(),
        ]);

        return back()->with('success', 'Laporan telah ditolak (Rejected).');
    }

    public function updateStatus(Request $request, Report $report)
    {
        if (auth()->user()->isPegawai()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|string',
        ]);

        $report->update(['status' => $request->status]);

        return back()->with('success', 'Status laporan perjalanan dinas berhasil diperbarui.');
    }

    public function destroy(Report $report)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && $report->user_id !== $user->id) {
            abort(403);
        }

        foreach ($report->attachments as $att) {
            Storage::disk('public')->delete($att->file_path);
        }

        $report->delete();

        return redirect()->route('pajokka.index')->with('success', 'Laporan berhasil dihapus.');
    }

    public function exportPdf(Report $report, PdfExportService $service)
    {
        return $service->generate($report);
    }

    public function exportDocx(Report $report, DocxExportService $service)
    {
        return $service->generate($report);
    }

    private function storeOptimizedFile($file, $directory): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = uniqid() . '.' . $extension;

        if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
            try {
                $manager = ImageManager::usingDriver(Driver::class);
                $image = $manager->decodePath($file->getRealPath());

                if ($image->width() > 1600) {
                    $image->scale(1600);
                }

                $storagePath = storage_path("app/public/{$directory}");
                if (!file_exists($storagePath)) {
                    mkdir($storagePath, 0755, true);
                }

                $fullPath = "{$storagePath}/{$filename}";
                $image->save($fullPath, 75);

                return "{$directory}/{$filename}";
            } catch (\Throwable $e) {
                // Fallback to standard upload if image processing driver encounters issue
                return $file->storeAs($directory, $filename, 'public');
            }
        }

        return $file->storeAs($directory, $filename, 'public');
    }

    public function scanOcr(Request $request)
    {
        @set_time_limit(600);
        @ini_set('max_execution_time', '600');
        @ini_set('memory_limit', '1024M');

        try {
            $uploadError = $_FILES['image']['error'] ?? $_FILES['file']['error'] ?? UPLOAD_ERR_OK;
            if ($uploadError !== UPLOAD_ERR_OK && $uploadError !== UPLOAD_ERR_NO_FILE) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mengunggah file berkas (Error code: ' . $uploadError . ').'
                ], 422);
            }

            try {
                $request->validate([
                    'image' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',
                    'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',
                ]);
            } catch (\Illuminate\Validation\ValidationException $ve) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Format file tidak sesuai. Pastikan file berformat PDF, JPG, PNG, atau DOCX.'
                ], 422);
            }

            $file = $request->file('image') ?? $request->file('file');
            if (!$file || !$file->isValid()) {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Berkas tidak valid atau gagal diunggah.'
                ], 422);
            }

            $ext = strtolower($file->getClientOriginalExtension() ?: ($file->extension() ?: 'jpg'));
            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp', 'bmp'])) {
                $fileSize = $file->getSize();
                $imageInfo = @getimagesize($file->getRealPath());
                if ($fileSize < 15 * 1024 || ($imageInfo && ($imageInfo[0] < 500 || $imageInfo[1] < 500))) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Resolusi gambar terlalu rendah. Gunakan foto/scan minimal 1000px.',
                        'text' => '[ERR_LOW_RESOLUTION: Gambar terlalu kecil untuk dibaca OCR]',
                        'ocr_confidence' => 0.0
                    ]);
                }
            }

            $tempFileName = 'ocr_' . uniqid() . '.' . $ext;
            $tempDirectory = storage_path('app/temp_ocr');
            if (!file_exists($tempDirectory)) {
                mkdir($tempDirectory, 0755, true);
            }
            $tempFullPath = $tempDirectory . DIRECTORY_SEPARATOR . $tempFileName;
            $file->move($tempDirectory, $tempFileName);

            $winTempPath = str_replace('/', '\\', $tempFullPath);
            $pythonBin = 'C:\\Users\\Reski_Anugrah_Sari\\AppData\\Local\\Programs\\Python\\Python311\\python.exe';
            $scriptPath = str_replace('/', '\\', base_path('app/Services/ocr_extractor.py'));

            $command = '"' . $pythonBin . '" "' . $scriptPath . '" "' . $winTempPath . '" 2>&1';
            $rawOutput = trim(shell_exec($command) ?? '');

            if (file_exists($tempFullPath)) {
                @unlink($tempFullPath);
            }

            $parsed = null;
            $jsonStart = strpos($rawOutput, '{');
            $jsonEnd = strrpos($rawOutput, '}');
            if ($jsonStart !== false && $jsonEnd !== false && $jsonEnd > $jsonStart) {
                $jsonString = substr($rawOutput, $jsonStart, $jsonEnd - $jsonStart + 1);
                $parsed = json_decode($jsonString, true);
            }

            $extractedText = $parsed['text'] ?? '[ERR_OCR_UNREADABLE: Foto kurang jelas atau tidak memuat teks uraian]';
            $hasExtractedData = !empty($parsed['nomor_surat']) || !empty($parsed['tanggal_surat']) || !empty($parsed['tgl_mulai_dinas']) || !empty($parsed['kota_tujuan']) || !empty($parsed['kegiatan_list']);
            $ocrConfidence = $parsed['ocr_confidence'] ?? 1.0;
            $handwritingWarning = $ocrConfidence < 0.5;

            $message = $hasExtractedData 
                ? 'Berhasil mengekstraksi data berkas surat via OCR!' 
                : (!empty($extractedText) && strlen($extractedText) > 20 
                    ? 'Teks surat berhasil dibaca via OCR.' 
                    : 'Gagal mengekstraksi data. Berkas kurang jelas atau teks tidak terdeteksi.');

            return response()->json([
                'status' => $hasExtractedData || (strlen($extractedText) > 20) ? 'success' : 'error',
                'message' => $message,
                'text' => $extractedText,
                'nomor_surat' => $parsed['nomor_surat'] ?? null,
                'tanggal_surat' => $parsed['tanggal_surat'] ?? null,
                'hal_perihal' => $parsed['hal_perihal'] ?? $parsed['hal'] ?? null,
                'hal' => $parsed['hal'] ?? $parsed['hal_perihal'] ?? null,
                'pengirim_instansi' => $parsed['pengirim_instansi'] ?? null,
                'tujuan_kota' => $parsed['tujuan_kota'] ?? $parsed['kota_tujuan'] ?? null,
                'tempat_pelaksanaan' => $parsed['tempat_pelaksanaan'] ?? null,
                'tanggal_mulai_acara' => $parsed['tanggal_mulai_acara'] ?? $parsed['tgl_mulai_dinas'] ?? null,
                'tanggal_selesai_acara' => $parsed['tanggal_selesai_acara'] ?? $parsed['tgl_selesai_dinas'] ?? null,
                'estimasi_periode_dinas' => $parsed['estimasi_periode_dinas'] ?? null,
                'rundown_kegiatan' => $parsed['rundown_kegiatan'] ?? [],
                'poin_substansi_latar_belakang' => $parsed['poin_substansi_latar_belakang'] ?? [],
                'tgl_mulai_dinas' => $parsed['tgl_mulai_dinas'] ?? null,
                'tgl_selesai_dinas' => $parsed['tgl_selesai_dinas'] ?? null,
                'kota_tujuan' => $parsed['kota_tujuan'] ?? null,
                'kegiatan_list' => $parsed['kegiatan_list'] ?? [],
                'ocr_confidence' => $ocrConfidence,
                'handwriting_warning' => $handwritingWarning,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses berkas OCR: ' . $e->getMessage(),
                'text' => null
            ], 500);
        }
    }
}
