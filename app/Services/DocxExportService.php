<?php

namespace App\Services;

use App\Models\KopSurat;
use App\Models\Report;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use Symfony\Component\HttpFoundation\Response;

class DocxExportService
{
    public function generate(Report $report): Response
    {
        $report->load(['user', 'ppk', 'schedules', 'attachments', 'expenses']);
        $kop = KopSurat::first();

        $phpWord = new PhpWord();

        // Metadata properties
        $docInfo = $phpWord->getDocInfo();
        $docInfo->setCreator('BPSDM PU');
        $docInfo->setTitle('Laporan Perjalanan Dinas - ' . $this->sanitizeText($report->nomor_surat_tugas));

        // Default font (Arial as required)
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(10.5);

        $section = $phpWord->addSection([
            'marginTop' => 1134, // ~2cm
            'marginBottom' => 1134,
            'marginLeft' => 1134,
            'marginRight' => 1134,
        ]);

        // Kop Surat Header
        $logoFullPath = $kop && $kop->logo_path ? storage_path('app/public/' . $kop->logo_path) : null;
        if ($logoFullPath && file_exists($logoFullPath) && @getimagesize($logoFullPath)) {
            $headerTable = $section->addTable([
                'cellMarginBottom' => 100,
            ]);
            $headerTable->addRow();
            $cellHeader = $headerTable->addCell(9500);
            $cellHeader->addImage($logoFullPath, [
                'width' => 470,
                'height' => 70,
                'alignment' => Jc::CENTER,
            ]);
        } else {
            $headerTable = $section->addTable([
                'borderBottomSize' => 18,
                'borderBottomColor' => '000000',
                'cellMarginBottom' => 100,
            ]);
            $headerTable->addRow();
            $cellText = $headerTable->addCell(9500);
            $cellText->addText(
                $this->sanitizeText(mb_strtoupper($kop->nama_kementerian ?? 'KEMENTERIAN PEKERJAAN UMUM')),
                ['bold' => true, 'size' => 12],
                ['alignment' => Jc::CENTER]
            );
            $cellText->addText(
                $this->sanitizeText(mb_strtoupper($kop->nama_badan ?? 'BADAN PENGEMBANGAN SUMBER DAYA MANUSIA')),
                ['bold' => true, 'size' => 11],
                ['alignment' => Jc::CENTER]
            );
            $cellText->addText(
                $this->sanitizeText(mb_strtoupper($kop->nama_unit_kerja ?? 'BALAI PENGEMBANGAN KOMPETENSI PU WILAYAH VIII MAKASSAR')),
                ['bold' => true, 'size' => 10],
                ['alignment' => Jc::CENTER]
            );

            $addressLine = ($kop->alamat ?? 'Jalan Nuri No. 19 Makassar 90122');
            if ($kop && $kop->telepon) {
                $addressLine .= ' — Telepon ' . $kop->telepon;
            }
            $cellText->addText(
                $this->sanitizeText($addressLine),
                ['size' => 8.5],
                ['alignment' => Jc::CENTER]
            );

            if ($kop && ($kop->email || $kop->website)) {
                $contactParts = [];
                if ($kop->email) {
                    $contactParts[] = 'Email: ' . $kop->email;
                }
                if ($kop->website) {
                    $contactParts[] = 'Website: ' . $kop->website;
                }
                $cellText->addText(
                    $this->sanitizeText(implode(' | ', $contactParts)),
                    ['size' => 8.5],
                    ['alignment' => Jc::CENTER]
                );
            }
        }

        $section->addTextBreak(1);

        // Judul Dokumen
        $section->addText(
            $this->sanitizeText('LAPORAN PELAKSANAAN PERJALANAN DINAS'),
            ['bold' => true, 'size' => 12, 'underline' => 'single'],
            ['alignment' => Jc::CENTER]
        );
        $section->addTextBreak(1);

        // Metadata Surat
        $pelaksanaList = $report->daftar_pelaksana ?? [];
        $tanggalSurat = $report->tanggal_surat_tugas;
        $tanggalSuratStr = $tanggalSurat ? ($tanggalSurat instanceof \Carbon\Carbon ? $tanggalSurat->translatedFormat('d F Y') : \Carbon\Carbon::parse($tanggalSurat)->translatedFormat('d F Y')) : '-';
        
        $startDate = $report->tgl_mulai_dinas ?? $report->tanggal_berangkat;
        $endDate = $report->tgl_selesai_dinas ?? $report->tanggal_kembali;
        $periodeStr = ($startDate && $endDate)
            ? (($startDate instanceof \Carbon\Carbon ? $startDate->translatedFormat('d') : \Carbon\Carbon::parse($startDate)->translatedFormat('d')) . ' s.d ' . ($endDate instanceof \Carbon\Carbon ? $endDate->translatedFormat('d F Y') : \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y')))
            : '-';

        $nomorSurat = $report->nomor_surat_tugas ?? ($report->nomor_surat ?? '-');
        $kotaTujuan = $report->kota_tujuan ?? ($report->tujuan ?? '-');

        $metaTable = $section->addTable();
        $this->addMetaRow($metaTable, 'Nomor Surat Tugas', ': ' . $this->sanitizeText($nomorSurat) . ', tanggal ' . $tanggalSuratStr);
        $this->addMetaRow($metaTable, 'Periode Perjalanan Dinas', ': ' . $periodeStr);
        $this->addMetaRow($metaTable, 'Kota Tujuan Perjalanan Dinas', ': ' . $this->sanitizeText($kotaTujuan));
        $this->addMetaRow($metaTable, 'Hal / Perihal Perjalanan Dinas', ': ' . $this->sanitizeText($report->isi_pointer ?? ($report->maksud_perjalanan ?? '-')));

        // Pelaksana di metadata
        $metaTable->addRow();
        $metaTable->addCell(3800)->addText('Nama Pelaksana Perjalanan Dinas');
        $cellPelaksana = $metaTable->addCell(5800);
        if (count($pelaksanaList) === 1) {
            $cellPelaksana->addText(': ' . $this->sanitizeText($pelaksanaList[0]['name'] ?? ''));
        } else {
            foreach ($pelaksanaList as $idx => $p) {
                $prefix = $idx === 0 ? ': ' : '  ';
                $cellPelaksana->addText($prefix . ($idx + 1) . '. ' . $this->sanitizeText($p['name'] ?? ''));
            }
        }

        $section->addTextBreak(1);

        // Paragraf Pembuka
        $section->addText(
            $this->sanitizeText('Berdasarkan Surat Tugas Nomor ' . $report->nomor_surat_tugas . ' bersama ini kami sampaikan laporan pelaksanaan perjalanan dinas sebagai berikut:'),
            [],
            ['alignment' => Jc::BOTH, 'indentation' => ['firstLine' => 720]]
        );

        $section->addTextBreak(1);

        // Tabel Jadwal Kegiatan — 3 kolom (No, Uraian Kegiatan, Keterangan)
        $scheduleTable = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80]);
        $scheduleTable->addRow();
        $scheduleTable->addCell(500, ['bgColor' => 'F2F2F2'])->addText('No', ['bold' => true], ['alignment' => Jc::CENTER]);
        $scheduleTable->addCell(6500, ['bgColor' => 'F2F2F2'])->addText('Uraian Kegiatan', ['bold' => true], ['alignment' => Jc::CENTER]);
        $scheduleTable->addCell(2100, ['bgColor' => 'F2F2F2'])->addText('Keterangan', ['bold' => true], ['alignment' => Jc::CENTER]);

        // Group schedules by date
        $groupedSchedules = $report->schedules->groupBy(function ($s) {
            return $s->tanggal ? $s->tanggal->format('Y-m-d') : 'unknown';
        });

        $dayNumber = 0;
        $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        foreach ($groupedSchedules as $date => $schedules) {
            $dayNumber++;
            $firstSchedule = $schedules->first();
            $dateObj = $firstSchedule->tanggal;
            $dayName = $dayNames[$dateObj->dayOfWeek] ?? '';
            $dateFormatted = $dateObj->translatedFormat('j F Y');

            $scheduleTable->addRow();
            $scheduleTable->addCell(500)->addText($dayNumber, [], ['alignment' => Jc::CENTER]);

            $cellUraian = $scheduleTable->addCell(6500);
            $cellUraian->addText($this->sanitizeText($dayName . ', ' . $dateFormatted), ['bold' => true]);
            foreach ($schedules as $s) {
                $lines = explode("\n", str_replace(["\r\n", "\r", '➢'], ["\n", "\n", '-'], $s->uraian_kegiatan ?? ''));
                foreach ($lines as $line) {
                    $cleanLine = trim($line);
                    if ($cleanLine !== '') {
                        $cellUraian->addText($this->sanitizeText($cleanLine));
                    }
                }
            }

            $cellKet = $scheduleTable->addCell(2100);
            $keteranganParts = $schedules->pluck('lokasi_keterangan')->filter()->unique()->values();
            foreach ($keteranganParts as $ket) {
                $cellKet->addText($this->sanitizeText($ket));
            }
            if ($keteranganParts->isEmpty()) {
                $cellKet->addText('-');
            }
        }

        $section->addTextBreak(1);

        // Pointer Narasi Substansi (sebagai paragraf lanjutan)
        if ($report->pointer_substansi) {
            $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $report->pointer_substansi));
            foreach ($lines as $line) {
                $cleanLine = trim($line);
                if ($cleanLine !== '') {
                    $section->addText(
                        $this->sanitizeText($cleanLine),
                        [],
                        ['alignment' => Jc::BOTH, 'indentation' => ['firstLine' => 720]]
                    );
                }
            }
            $section->addTextBreak(1);
        }

        // Tabel Rincian Biaya & Metode Pembayaran (Per-Item KKP & Cash)
        if ($report->expenses->count() > 0) {
            $section->addText($this->sanitizeText('RINCIAN PENGELUARAN & METODE PEMBAYARAN:'), ['bold' => true, 'size' => 10]);
            $expTable = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80]);
            $expTable->addRow();
            $expTable->addCell(500, ['bgColor' => 'F2F2F2'])->addText('No', ['bold' => true], ['alignment' => Jc::CENTER]);
            $expTable->addCell(4500, ['bgColor' => 'F2F2F2'])->addText('Komponen Biaya', ['bold' => true], ['alignment' => Jc::CENTER]);
            $expTable->addCell(1800, ['bgColor' => 'F2F2F2'])->addText('Metode Bayar', ['bold' => true], ['alignment' => Jc::CENTER]);
            $expTable->addCell(2300, ['bgColor' => 'F2F2F2'])->addText('Nominal (Rp)', ['bold' => true], ['alignment' => Jc::CENTER]);

            foreach ($report->expenses as $idx => $exp) {
                $expTable->addRow();
                $expTable->addCell(500)->addText($idx + 1, [], ['alignment' => Jc::CENTER]);
                $cType = $expTable->addCell(4500);
                $cType->addText($this->sanitizeText($exp->expense_type));
                if ($exp->description) {
                    $cType->addText('(' . $this->sanitizeText($exp->description) . ')', ['italic' => true, 'size' => 9, 'color' => '555555']);
                }
                $expTable->addCell(1800)->addText(strtoupper($exp->payment_method), ['bold' => true], ['alignment' => Jc::CENTER]);
                $expTable->addCell(2300)->addText(number_format($exp->amount, 0, ',', '.'), [], ['alignment' => Jc::RIGHT]);
            }

            $expTable->addRow();
            $expTable->addCell(6800, ['gridSpan' => 3])->addText('Subtotal KKP (Kartu Kredit Govt):', ['bold' => true], ['alignment' => Jc::RIGHT]);
            $expTable->addCell(2300)->addText('Rp ' . number_format($report->total_kkp, 0, ',', '.'), ['bold' => true], ['alignment' => Jc::RIGHT]);

            $expTable->addRow();
            $expTable->addCell(6800, ['gridSpan' => 3])->addText('Subtotal Cash / Reimburse (Tunai):', ['bold' => true], ['alignment' => Jc::RIGHT]);
            $expTable->addCell(2300)->addText('Rp ' . number_format($report->total_cash, 0, ',', '.'), ['bold' => true], ['alignment' => Jc::RIGHT]);

            $expTable->addRow();
            $expTable->addCell(6800, ['gridSpan' => 3, 'bgColor' => 'E6F0FA'])->addText('GRAND TOTAL BIAYA DINAS:', ['bold' => true], ['alignment' => Jc::RIGHT]);
            $expTable->addCell(2300, ['bgColor' => 'E6F0FA'])->addText('Rp ' . number_format($report->total_cost, 0, ',', '.'), ['bold' => true], ['alignment' => Jc::RIGHT]);

            $section->addTextBreak(1);
        }

        // Pengesahan / TTD Table
        $ttdTable = $section->addTable();
        $ttdTable->addRow();

        $cellPpk = $ttdTable->addCell(4550);
        $cellPpk->addTextBreak(1);
        $cellPpk->addText('Pejabat Pembuat Komitmen,', [], ['alignment' => Jc::CENTER]);
        $cellPpk->addTextBreak(3);
        $ppkName = $report->ppk?->nama ?? ($report->masterPpk?->nama ?? '-');
        $ppkNip = $report->ppk?->nip ?? ($report->masterPpk?->nip ?? '-');
        $cellPpk->addText($this->sanitizeText($ppkName), ['bold' => true, 'underline' => 'single'], ['alignment' => Jc::CENTER]);
        $cellPpk->addText('NIP. ' . $this->sanitizeText($ppkNip), [], ['alignment' => Jc::CENTER]);

        $cellUser = $ttdTable->addCell(4550);
        $dateSppd = $report->tanggal_sppd ?? ($report->tgl_selesai_dinas ?? ($report->tanggal_kembali ?? now()));
        $sppdDateStr = $dateSppd instanceof \Carbon\Carbon ? $dateSppd->translatedFormat('d F Y') : \Carbon\Carbon::parse($dateSppd)->translatedFormat('d F Y');
        $cellUser->addText($this->sanitizeText($kotaTujuan) . ', ' . $sppdDateStr, [], ['alignment' => Jc::CENTER]);
        $cellUser->addText('Pelaksana Dinas,', [], ['alignment' => Jc::CENTER]);
        $cellUser->addTextBreak(3);
        $pelaksanaNama = $report->daftar_pelaksana[0]['name'] ?? ($report->user?->name ?? ($report->pegawai?->nama ?? 'Pelaksana'));
        $pelaksanaNip = $report->daftar_pelaksana[0]['nip'] ?? ($report->user?->nip ?? ($report->pegawai?->nip ?? '-'));
        $cellUser->addText($this->sanitizeText($pelaksanaNama), ['bold' => true, 'underline' => 'single'], ['alignment' => Jc::CENTER]);
        $cellUser->addText('NIP. ' . $this->sanitizeText($pelaksanaNip), [], ['alignment' => Jc::CENTER]);

        // Lampiran Berkas & Dokumentasi Gambar
        $attachments = $report->attachments;
        $docPhotos = $attachments->filter(fn($a) => $a->category === 'dokumentasi')->take(8);
        $otherFiles = $attachments->filter(fn($a) => $a->category !== 'dokumentasi');

        if ($otherFiles->count() > 0 || $docPhotos->count() > 0) {
            $section->addPageBreak();

            if ($otherFiles->count() > 0) {
                $section->addText($this->sanitizeText('LAMPIRAN BUKTI FISIK & STRUK PENGELUARAN'), ['bold' => true, 'size' => 12, 'underline' => 'single'], ['alignment' => Jc::CENTER]);
                $section->addTextBreak(1);

                $imgTable = $section->addTable();
                $colCount = 0;
                $row = null;

                foreach ($otherFiles as $att) {
                    $ext = strtolower(pathinfo($att->file_path, PATHINFO_EXTENSION));
                    $fullPath = storage_path('app/public/' . $att->file_path);
                    if (file_exists($fullPath) && in_array($ext, ['jpg', 'jpeg', 'png']) && @getimagesize($fullPath)) {
                        if ($colCount % 2 === 0) {
                            $row = $imgTable->addRow();
                        }
                        $cell = $row->addCell(4550);
                        $dim = $this->getProportionalDimensions($fullPath, 210, 150);
                        $cell->addImage($fullPath, [
                            'width' => $dim['width'],
                            'height' => $dim['height'],
                            'alignment' => Jc::CENTER,
                        ]);
                        $cell->addText($this->sanitizeText($att->label), ['bold' => true, 'size' => 9], ['alignment' => Jc::CENTER]);
                        $cell->addTextBreak(1);
                        $colCount++;
                    }
                }
            }

            if ($docPhotos->count() > 0) {
                $section->addTextBreak(1);
                $section->addText($this->sanitizeText('LAMPIRAN DOKUMENTASI KEGIATAN (MAKS 8 FOTO)'), ['bold' => true, 'size' => 12, 'underline' => 'single'], ['alignment' => Jc::CENTER]);
                $section->addTextBreak(1);

                $imgTableDoc = $section->addTable();
                $colCount = 0;
                $row = null;

                foreach ($docPhotos as $idx => $att) {
                    $fullPath = storage_path('app/public/' . $att->file_path);
                    if (file_exists($fullPath) && @getimagesize($fullPath)) {
                        if ($colCount % 2 === 0) {
                            $row = $imgTableDoc->addRow();
                        }
                        $cell = $row->addCell(4550);
                        $dim = $this->getProportionalDimensions($fullPath, 210, 150);
                        $cell->addImage($fullPath, [
                            'width' => $dim['width'],
                            'height' => $dim['height'],
                            'alignment' => Jc::CENTER,
                        ]);
                        $cell->addText('Dokumentasi Foto ' . ($idx + 1), ['bold' => true, 'size' => 9], ['alignment' => Jc::CENTER]);
                        $cell->addTextBreak(1);
                        $colCount++;
                    }
                }
            }
        }

        $safeSt = preg_replace('/[^A-Za-z0-9_\-]/', '_', $report->nomor_surat_tugas);
        $filename = 'Laporan_Dinas_' . $safeSt . '.docx';

        $tempPath = tempnam(sys_get_temp_dir(), 'docx_') . '.docx';
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

        if (ob_get_level()) {
            ob_end_clean();
        }

        $objWriter->save($tempPath);

        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0, no-store, no-cache, must-revalidate',
            'Pragma' => 'public',
        ])->deleteFileAfterSend(true);
    }

    private function addMetaRow($table, $label, $value, $bold = false): void
    {
        $row = $table->addRow();
        $row->addCell(3800)->addText($this->sanitizeText($label));
        $row->addCell(5800)->addText($this->sanitizeText($value), ['bold' => $bold]);
    }

    private function sanitizeText(?string $text): string
    {
        if ($text === null) {
            return '';
        }
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);
        return htmlspecialchars($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    private function getProportionalDimensions(string $path, int $maxWidth = 210, int $maxHeight = 150): array
    {
        $dimensions = @getimagesize($path);
        if (!$dimensions || $dimensions[0] <= 0 || $dimensions[1] <= 0) {
            return ['width' => $maxWidth, 'height' => $maxHeight];
        }

        $w = $dimensions[0];
        $h = $dimensions[1];

        $ratio = min($maxWidth / $w, $maxHeight / $h);
        return [
            'width' => (int) max(1, round($w * $ratio)),
            'height' => (int) max(1, round($h * $ratio)),
        ];
    }
}
