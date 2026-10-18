<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/1999/xlink">
<head>
    <meta charset="utf-8">
    <title>SPPD - {{ $report->nomor_surat }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            color: #000000;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 14pt;
            text-transform: uppercase;
        }
        .header h1 {
            margin: 5px 0;
            font-size: 16pt;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 10pt;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 13pt;
            text-decoration: underline;
            margin: 20px 0 10px 0;
            text-transform: uppercase;
        }
        .nomor {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 20px;
        }
        table.content-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 11pt;
            border-collapse: collapse;
        }
        table.content-table td {
            padding: 5px;
            vertical-align: top;
        }
        table.content-table td.label {
            width: 180px;
            font-weight: bold;
        }
        table.expense-table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
            font-size: 10pt;
        }
        table.expense-table th, table.expense-table td {
            border: 1px solid #000000;
            padding: 6px;
            text-align: left;
        }
        table.expense-table th {
            background-color: #f2f2f2;
        }
        .signature {
            margin-top: 40px;
            float: right;
            text-align: center;
            width: 250px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Kementerian Pekerjaan Umum dan Perumahan Rakyat</h2>
        <h1>Surat Perjalanan Dinas (SPPD)</h1>
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
    </table>

    <br>
    <b>Rincian Pembiayaan & Metode Pembayaran (KKP / Cash):</b>
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
                <td><b>{{ $exp->metode_pembayaran }}</b></td>
            </tr>
            @endforeach
            <tr>
                <td colspan="2" style="text-align: right;"><b>Total Biaya:</b></td>
                <td colspan="2"><b>Rp {{ number_format($total, 0, ',', '.') }}</b></td>
            </tr>
        </tbody>
    </table>

    <div class="signature">
        <p>Jakarta, {{ \Carbon\Carbon::parse($report->created_at)->translatedFormat('d F Y') }}</p>
        <p>Pejabat Pembuat Komitmen,</p>
        <br><br><br>
        <p><b>{{ $report->masterPpk->nama ?? '-' }}</b></p>
        <p>NIP. {{ $report->masterPpk->nip ?? '-' }}</p>
    </div>
</body>
</html>
