<?php

namespace App\Services;

use App\Models\ReportAttachment;
use Illuminate\Support\Collection;

class PdfAttachmentRenderer
{
    /**
     * Resolve an available Python binary with PyMuPDF/OCR dependencies.
     */
    public static function resolvePythonBinary(): ?string
    {
        $configured = env('OCR_PYTHON_PATH');
        if ($configured && is_file($configured)) {
            return $configured;
        }

        $candidates = [
            base_path('.venv/Scripts/python.exe'),
            base_path('.venv/bin/python'),
            'C:\\Users\\Reski_Anugrah_Sari\\AppData\\Local\\Programs\\Python\\Python311\\python.exe',
            'C:\\Python311\\python.exe',
            'C:\\Python312\\python.exe',
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        $whereCmd = PHP_OS_FAMILY === 'Windows' ? 'where python 2>nul' : 'which python3 python 2>/dev/null';
        $found = trim((string) shell_exec($whereCmd));
        if ($found !== '') {
            foreach (preg_split('/\r\n|\r|\n/', $found) as $line) {
                $path = trim($line);
                if ($path !== '' && is_file($path) && ! str_contains($path, 'WindowsApps\\python.exe')) {
                    return $path;
                }
            }
        }

        return null;
    }

    /**
     * Convert non-documentation attachments (images and PDF pages) into renderable image entries.
     *
     * @return Collection<int, array{path: string, label: string}>
     */
    public function collectOtherAttachmentImages(Collection $attachments): Collection
    {
        return $attachments
            ->filter(fn (ReportAttachment $att) => $att->category !== 'dokumentasi')
            ->flatMap(fn (ReportAttachment $att) => $this->renderAttachmentToImages($att))
            ->values();
    }

    /**
     * Return image path(s) for a single attachment, rendering PDF pages to cached PNGs via PyMuPDF.
     *
     * @return array<int, array{path: string, label: string}>
     */
    public function renderAttachmentToImages(ReportAttachment $att): array
    {
        $fullPath = storage_path('app/public/' . $att->file_path);
        if (! is_file($fullPath)) {
            return [];
        }

        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
            return [[
                'path' => $fullPath,
                'label' => $att->label,
            ]];
        }

        if ($ext !== 'pdf') {
            return [];
        }

        $cacheKey = md5($fullPath . '|' . (@filemtime($fullPath) ?: 0) . '|' . (@filesize($fullPath) ?: 0));
        $cacheDir = storage_path('app/temp_pdf_pages/' . $cacheKey);

        $existingPages = $this->findRenderedPages($cacheDir);
        if (empty($existingPages)) {
            $this->renderPdfViaPython($fullPath, $cacheDir);
            $existingPages = $this->findRenderedPages($cacheDir);
        }

        if (empty($existingPages)) {
            return [];
        }

        $totalPages = count($existingPages);
        $results = [];
        foreach ($existingPages as $idx => $pagePath) {
            $results[] = [
                'path' => $pagePath,
                'label' => $totalPages > 1 ? "{$att->label} (Hal. " . ($idx + 1) . ')' : $att->label,
            ];
        }

        return $results;
    }

    /**
     * @return array<int, string>
     */
    private function findRenderedPages(string $cacheDir): array
    {
        if (! is_dir($cacheDir)) {
            return [];
        }

        $files = glob($cacheDir . DIRECTORY_SEPARATOR . 'page_*.png') ?: [];
        sort($files, SORT_NATURAL);

        return array_values(array_filter($files, fn ($f) => is_file($f) && filesize($f) > 0));
    }

    private function renderPdfViaPython(string $pdfPath, string $cacheDir): void
    {
        $pythonBin = self::resolvePythonBinary();
        if (! $pythonBin) {
            return;
        }

        if (! is_dir($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }

        $scriptPath = base_path('app/Services/pdf_to_images.py');
        if (! is_file($scriptPath)) {
            return;
        }

        $cmd = '"' . $pythonBin . '" "' . $scriptPath . '" "' . $pdfPath . '" "' . $cacheDir . '" 10 150 2>&1';
        @shell_exec($cmd);
    }
}
