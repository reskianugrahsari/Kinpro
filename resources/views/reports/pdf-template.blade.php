<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perjalanan Dinas - {{ $report->nomor_surat_tugas }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 2cm 2cm 2cm 2cm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10.5pt;
            line-height: 1.4;
            color: #000;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px double #000;
            margin-bottom: 15px;
            padding-bottom: 5px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .instansi-title {
            text-align: center;
        }
        .instansi-kementerian {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .instansi-badan {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .instansi-unit {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 2px;
            margin-bottom: 3px;
        }
        .instansi-address {
            font-size: 8.5pt;
            font-weight: normal;
        }
        .title-doc {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 10px;
            margin-bottom: 15px;
            text-decoration: underline;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .meta-table td {
            padding: 3px 0;
            vertical-align: top;
            font-size: 11pt;
        }
        .meta-label {
            width: 250px;
        }
        .meta-sep {
            width: 10px;
        }
        .paragraph {
            text-align: justify;
            margin-bottom: 10px;
            text-indent: 40px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 15px;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 5px 7px;
            font-size: 10pt;
            vertical-align: top;
        }
        .data-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .day-header {
            font-weight: bold;
            margin-bottom: 3px;
        }
        .schedule-line {
            margin-bottom: 2px;
        }
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .ttd-table td {
            vertical-align: top;
            width: 50%;
        }
        .page-break {
            page-break-before: always;
        }
        .img-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .img-table td {
            width: 50%;
            vertical-align: top;
            padding: 6px;
            text-align: center;
            page-break-inside: avoid;
        }
        .img-box {
            border: 1px solid #d1d5db;
            padding: 6px;
            background-color: #f9fafb;
            text-align: center;
        }
        .img-box img {
            max-width: 100%;
            max-height: 220px;
            width: auto;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        .img-caption {
            font-size: 9pt;
            font-weight: bold;
            margin-top: 6px;
            color: #111827;
        }
    </style>
</head>
<body>

    <!-- Header / Kop Surat -->
    @if ($kop && $kop->logo_path && file_exists(storage_path('app/public/' . $kop->logo_path)))
        <div style="width: 100%; text-align: center; margin-bottom: 15px;">
            <img src="{{ storage_path('app/public/' . $kop->logo_path) }}" style="width: 100%; max-height: 120px; object-fit: contain;">
        </div>
    @else
        <table class="header-table">
            <tr>
                <td class="instansi-title">
                    <div class="instansi-kementerian">{{ $kop->nama_kementerian ?? 'KEMENTERIAN PEKERJAAN UMUM' }}</div>
                    <div class="instansi-badan">{{ $kop->nama_badan ?? 'BADAN PENGEMBANGAN SUMBER DAYA MANUSIA' }}</div>
                    <div class="instansi-unit">{{ $kop->nama_unit_kerja ?? 'BALAI PENGEMBANGAN KOMPETENSI PU WILAYAH VIII MAKASSAR' }}</div>
                    <div class="instansi-address">
                        {{ $kop->alamat ?? 'Jalan Nuri No. 19 Makassar 90122' }}
                        @if ($kop && $kop->telepon)
                            — Telepon {{ $kop->telepon }}
                        @endif
                    </div>
                    @if ($kop && ($kop->email || $kop->website))
                        <div class="instansi-address">
                            @if ($kop->email) Email: {{ $kop->email }} @endif
                            @if ($kop->website) | Website: {{ $kop->website }} @endif
                        </div>
                    @endif
                </td>
            </tr>
        </table>
    @endif

    <!-- Judul Dokumen -->
    <div class="title-doc">
        LAPORAN PELAKSANAAN PERJALANAN DINAS
    </div>

    <!-- Metadata Surat -->
    @php
        $pelaksanaList = $report->daftar_pelaksana ?? [];
        $tanggalSuratStr = $report->tanggal_surat_tugas ? $report->tanggal_surat_tugas->translatedFormat('d F Y') : '-';
        $periodeStr = $report->tgl_mulai_dinas->translatedFormat('d') . ' s.d ' . $report->tgl_selesai_dinas->translatedFormat('d F Y');
    @endphp

    <table class="meta-table">
        <tr>
            <td class="meta-label">Nomor Surat Tugas</td>
            <td class="meta-sep">:</td>
            <td>{{ $report->nomor_surat_tugas }}, tanggal {{ $tanggalSuratStr }}</td>
        </tr>
        <tr>
            <td class="meta-label">Periode Perjalanan Dinas</td>
            <td class="meta-sep">:</td>
            <td>{{ $periodeStr }}</td>
        </tr>
        <tr>
            <td class="meta-label">Kota Tujuan Perjalanan Dinas</td>
            <td class="meta-sep">:</td>
            <td>{{ $report->kota_tujuan }}</td>
        </tr>
        <tr>
            <td class="meta-label">Hal / Perihal Perjalanan Dinas</td>
            <td class="meta-sep">:</td>
            <td>{{ $report->isi_pointer ?? '-' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Nama Pelaksana Perjalanan Dinas</td>
            <td class="meta-sep">:</td>
            <td>
                @if (count($pelaksanaList) === 1)
                    {{ $pelaksanaList[0]['name'] }}
                @else
                    @foreach ($pelaksanaList as $idx => $p)
                        {{ $idx + 1 }}. {{ $p['name'] }}<br>
                    @endforeach
                @endif
            </td>
        </tr>
    </table>

    <!-- Paragraf Pembuka -->
    <p class="paragraph">
        Berdasarkan Surat Tugas Nomor {{ $report->nomor_surat_tugas }} bersama ini kami sampaikan laporan pelaksanaan perjalanan dinas sebagai berikut:
    </p>

    <!-- Tabel Jadwal Kegiatan -->
    @php
        // Group schedules by tanggal
        $groupedSchedules = $report->schedules->groupBy(function($s) {
            return $s->tanggal ? $s->tanggal->format('Y-m-d') : 'unknown';
        });
        $dayNumber = 0;
    @endphp

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 35px;">No</th>
                <th>Uraian Kegiatan</th>
                <th style="width: 180px;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupedSchedules as $date => $schedules)
                @php
                    $dayNumber++;
                    $firstSchedule = $schedules->first();
                    $dateObj = $firstSchedule->tanggal;
                    $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    $dayName = $dayNames[$dateObj->dayOfWeek];
                    $dateFormatted = $dateObj->translatedFormat('j F Y');
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $dayNumber }}</td>
                    <td>
                        <div class="day-header">{{ $dayName }}, {{ $dateFormatted }}</div>
                        {!! nl2br(e(str_replace('➢', '-', $firstSchedule->uraian_kegiatan))) !!}
                        @foreach ($schedules->slice(1) as $extra)
                            <br>{!! nl2br(e(str_replace('➢', '-', $extra->uraian_kegiatan))) !!}
                        @endforeach
                    </td>
                    <td>
                        {{ $firstSchedule->lokasi_keterangan ?? '-' }}
                        @foreach ($schedules->slice(1) as $extra)
                            @if ($extra->lokasi_keterangan)
                                <br>{{ $extra->lokasi_keterangan }}
                            @endif
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pointer Narasi Substansi (jika ada, sebagai paragraf lanjutan) -->
    @if ($report->pointer_substansi)
        <p class="paragraph">
            {!! nl2br(e($report->pointer_substansi)) !!}
        </p>
    @endif

    <!-- Tabel Rincian Biaya & Metode Pembayaran (Per-Item KKP & Cash) -->
    @if ($report->expenses->count() > 0)
        <div style="font-weight: bold; margin-top: 10px; margin-bottom: 5px; font-size: 10pt;">
            RINCIAN PENGELUARAN & METODE PEMBAYARAN:
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Komponen Biaya</th>
                    <th style="width: 90px;">Metode Bayar</th>
                    <th style="width: 110px;">Nominal (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($report->expenses as $idx => $exp)
                    <tr>
                        <td style="text-align: center;">{{ $idx + 1 }}</td>
                        <td>{{ $exp->expense_type }} @if($exp->description)<br><i style="font-size: 9pt; color: #555;">({{ $exp->description }})</i>@endif</td>
                        <td style="text-align: center; font-weight: bold;">{{ strtoupper($exp->payment_method) }}</td>
                        <td style="text-align: right; font-family: monospace;">{{ number_format($exp->amount, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #f9f9f9; font-weight: bold;">
                    <td colspan="3" style="text-align: right;">Subtotal KKP:</td>
                    <td style="text-align: right; font-family: monospace;">Rp {{ number_format($report->total_kkp, 0, ',', '.') }}</td>
                </tr>
                <tr style="background-color: #f9f9f9; font-weight: bold;">
                    <td colspan="3" style="text-align: right;">Subtotal Cash:</td>
                    <td style="text-align: right; font-family: monospace;">Rp {{ number_format($report->total_cash, 0, ',', '.') }}</td>
                </tr>
                <tr style="background-color: #e6f0fa; font-weight: bold;">
                    <td colspan="3" style="text-align: right;">TOTAL BIAYA DINAS:</td>
                    <td style="text-align: right; font-family: monospace;">Rp {{ number_format($report->total_cost, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    @endif

    <!-- Pengesahan / TTD -->
    <table class="ttd-table">
        <tr>
            <td style="text-align: center;">
                <br>
                Pejabat Pembuat Komitmen,
                <br><br><br><br>
                <strong><u>{{ $report->ppk->nama ?? '-' }}</u></strong><br>
                NIP. {{ $report->ppk->nip ?? '-' }}
            </td>
            <td style="text-align: center;">
                {{ $report->kota_tujuan }}, {{ $report->tanggal_sppd ? $report->tanggal_sppd->translatedFormat('d F Y') : $report->tgl_selesai_dinas->translatedFormat('d F Y') }}<br>
                Pelaksana Dinas,
                <br><br><br><br>
                <strong><u>{{ $report->daftar_pelaksana[0]['name'] ?? $report->user->name }}</u></strong><br>
                NIP. {{ $report->daftar_pelaksana[0]['nip'] ?? $report->user->nip }}
            </td>
        </tr>
    </table>

    <!-- Lampiran Berkas & Dokumentasi Gambar (Halaman Baru) -->
    @php
        $attachments = $report->attachments;
        $docPhotos = $attachments->filter(fn($a) => $a->category === 'dokumentasi')->take(8);
        $otherFiles = $attachments->filter(fn($a) => $a->category !== 'dokumentasi');
    @endphp

    @if ($otherFiles->count() > 0 || $docPhotos->count() > 0)
        <div class="page-break"></div>

        @if ($otherFiles->count() > 0)
            <div class="title-doc">
                LAMPIRAN BUKTI FISIK & STRUK PENGELUARAN
            </div>
            <table class="img-table">
                @php
                    $validFiles = $otherFiles->filter(function($att) {
                        return file_exists(storage_path('app/public/' . $att->file_path)) && 
                               in_array(strtolower(pathinfo($att->file_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']);
                    })->values();
                @endphp

                @foreach ($validFiles->chunk(2) as $rowFiles)
                    <tr>
                        @foreach ($rowFiles as $att)
                            <td>
                                <div class="img-box">
                                    <img src="{{ storage_path('app/public/' . $att->file_path) }}">
                                    <div class="img-caption">{{ $att->label }}</div>
                                </div>
                            </td>
                        @endforeach
                        @if ($rowFiles->count() === 1)
                            <td></td>
                        @endif
                    </tr>
                @endforeach
            </table>
        @endif

        @if ($docPhotos->count() > 0)
            <div class="title-doc" style="margin-top: 20px;">
                LAMPIRAN DOKUMENTASI KEGIATAN (MAKS 8 FOTO)
            </div>
            <table class="img-table">
                @php
                    $validDocs = $docPhotos->filter(function($att) {
                        return file_exists(storage_path('app/public/' . $att->file_path));
                    })->values();
                @endphp

                @foreach ($validDocs->chunk(2) as $rowDocs)
                    <tr>
                        @foreach ($rowDocs as $idx => $att)
                            <td>
                                <div class="img-box">
                                    <img src="{{ storage_path('app/public/' . $att->file_path) }}">
                                    <div class="img-caption">Dokumentasi Foto</div>
                                </div>
                            </td>
                        @endforeach
                        @if ($rowDocs->count() === 1)
                            <td></td>
                        @endif
                    </tr>
                @endforeach
            </table>
        @endif
    @endif

</body>
</html>
