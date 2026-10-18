<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Perjalanan Dinas (SPPD) - {{ $report->nomor_surat }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f5f9;
            color: #0f2747;
            margin: 0;
            padding: 20px;
        }
        .sheet {
            background: white;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 20mm;
            box-sizing: border-box;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px double #0f2747;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
            color: #1769aa;
        }
        .header h1 {
            margin: 5px 0;
            font-size: 18px;
            text-transform: uppercase;
            color: #0f2747;
        }
        .header p {
            margin: 0;
            font-size: 12px;
            color: #475569;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            text-decoration: underline;
            margin: 20px 0 10px 0;
            text-transform: uppercase;
        }
        .nomor {
            text-align: center;
            font-size: 12px;
            margin-bottom: 25px;
        }
        .content-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 13px;
            border-collapse: collapse;
        }
        .content-table td {
            padding: 6px 4px;
            vertical-align: top;
        }
        .content-table td.label {
            width: 180px;
            font-weight: bold;
        }
        .expense-table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
            font-size: 12px;
        }
        .expense-table th, .expense-table td {
            border: 1px solid #cbd5e1;
            padding: 8px;
            text-align: left;
        }
        .expense-table th {
            background-color: #f8fafc;
            color: #0f2747;
        }
        .signature-section {
            margin-top: 40px;
            float: right;
            text-align: center;
            width: 250px;
            font-size: 13px;
        }
        .signature-space {
            height: 70px;
        }
        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn {
            background-color: #1769aa;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            display: inline-block;
            cursor: pointer;
            border: none;
            margin: 0 5px;
        }
        .btn:hover {
            background-color: #0f2747;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .sheet {
                box-shadow: none;
                margin: 0;
                width: 100%;
                padding: 10mm;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="{{ route('pajokka.index') }}" class="btn" style="background-color: #64748b;">&larr; Kembali</a>
        <button onclick="window.print()" class="btn">Cetak / Unduh PDF (A4)</button>
        <a href="{{ route('pajokka.docx', $report) }}" class="btn" style="background-color: #059669;">Unduh Dokumen DOCX</a>
    </div>

    <div class="sheet">
        <div class="header">
            <h2>Kementerian Pekerjaan Umum dan Perumahan Rakyat</h2>
            <h1>Surat Perjalanan Dinas (SPPD - Pajokka)</h1>
            <p>Jl. Pattimura No. 20, Kebayoran Baru, Jakarta Selatan</p>
        </div>

        <div class="title">Surat Perintah Perjalanan Dinas</div>
        <div class="nomor">Nomor: {{ $report->nomor_surat }}</div>

        <table class="content-table">
            <tr>
                <td class="label">Pegawai Pelaksana</td>
                <td>: {{ $report->pegawai->nama ?? '-' }} (NIP. {{ $report->pegawai->nip ?? '-' }})</td>
            </tr>
            <tr>
                <td class="label">Unit Kerja / Jabatan</td>
                <td>: {{ $report->pegawai->unitKerja->nama ?? '-' }} / {{ $report->pegawai->jabatan ?? 'Pegawai' }}</td>
            </tr>
            <tr>
                <td class="label">Maksud Perjalanan</td>
                <td>: {{ $report->maksud_perjalanan }}</td>
            </tr>
            <tr>
                <td class="label">Kota Tujuan</td>
                <td>: {{ $report->tujuan }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Pelaksanaan</td>
                <td>: {{ \Carbon\Carbon::parse($report->tanggal_berangkat)->translatedFormat('d F Y') }} s.d. {{ \Carbon\Carbon::parse($report->tanggal_kembali)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Pejabat Pembuat Komitmen</td>
                <td>: {{ $report->masterPpk->nama ?? '-' }} (NIP. {{ $report->masterPpk->nip ?? '-' }})</td>
            </tr>
            <tr>
                <td class="label">Status Verifikasi</td>
                <td>: <strong style="text-transform: uppercase; color: {{ $report->status === 'approved' ? '#059669' : ($report->status === 'rejected' ? '#e11d48' : '#d97706') }}">{{ $report->status }}</strong></td>
            </tr>
        </table>

        <h3 style="font-size: 13px; text-transform: uppercase; margin-top: 20px; border-bottom: 2px solid #0f2747; padding-bottom: 5px;">Rincian Pembiayaan & Metode Pembayaran (KKP / Cash)</h3>
        <table class="expense-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Uraian Pengeluaran</th>
                    <th>Nominal (Rp)</th>
                    <th>Metode Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($report->expenses as $i => $exp)
                @php $total += $exp->nominal; @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $exp->uraian }}</td>
                    <td>Rp {{ number_format($exp->nominal, 0, ',', '.') }}</td>
                    <td><span style="font-weight: bold; color: {{ $exp->metode_pembayaran === 'KKP' ? '#1769aa' : '#0f2747' }}">{{ $exp->metode_pembayaran }}</span></td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2" style="text-align: right; font-weight: bold;">Total Biaya:</td>
                    <td colspan="2" style="font-weight: bold;">Rp {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="signature-section">
            <p>Jakarta, {{ \Carbon\Carbon::parse($report->created_at)->translatedFormat('d F Y') }}</p>
            <p>Pejabat Pembuat Komitmen,</p>
            <div class="signature-space"></div>
            <p><strong>{{ $report->masterPpk->nama ?? '-' }}</strong></p>
            <p>NIP. {{ $report->masterPpk->nip ?? '-' }}</p>
        </div>
    </div>

</body>
</html>
