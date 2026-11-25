<?php

namespace App\Services;

use App\Models\KopSurat;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PdfExportService
{
    public function generate(Report $report): Response
    {
        $report->load(['user', 'ppk', 'schedules', 'attachments', 'expenses']);
        $kop = KopSurat::first();
        $renderedOtherFiles = app(PdfAttachmentRenderer::class)->collectOtherAttachmentImages($report->attachments);

        $pdf = Pdf::loadView('reports.pdf-template', compact('report', 'kop', 'renderedOtherFiles'))
            ->setPaper('a4', 'portrait')
            ->setOption(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);

        $filename = 'Laporan_Dinas_' . str_replace('/', '_', $report->nomor_surat_tugas) . '.pdf';

        return $pdf->download($filename);
    }
}
