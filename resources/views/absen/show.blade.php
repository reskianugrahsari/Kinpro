<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Naskah Dinas Lupa Absen - {{ $absen->pegawai->nama ?? 'Pegawai' }}</title>
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
            position: relative;
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
            width: 160px;
            font-weight: bold;
        }
        .signature-section {
            margin-top: 50px;
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
        <a href="{{ route('absen.index') }}" class="btn" style="background-color: #64748b; margin-right: 10px;">&larr; Kembali</a>
        <button onclick="window.print()" class="btn">Cetak / Unduh PDF (A4)</button>
    </div>

    <div class="sheet">
        <div class="header">
            <h2>Kementerian Pekerjaan Umum dan Perumahan Rakyat</h2>
            <h1>Sistem Informasi Kinerja Pegawai (KinPro)</h1>
            <p>Jl. Pattimura No. 20, Kebayoran Baru, Jakarta Selatan</p>
        </div>

        <div class="title">Surat Permohonan Lupa Absen (Don't Forget)</div>
        <div class="nomor">Nomor: DF/{{ date('Y', strtotime($absen->tanggal_absen)) }}/{{ str_pad($absen->id, 4, '0', STR_PAD_LEFT) }}</div>

        <p style="font-size: 13px; line-height: 1.6;">Yang bertanda tangan di bawah ini:</p>

        <table class="content-table">
            <tr>
                <td class="label">Nama Pegawai</td>
                <td>: {{ $absen->pegawai->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">NIP</td>
                <td>: {{ $absen->pegawai->nip ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Unit Kerja</td>
                <td>: {{ $absen->pegawai->unitKerja->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td>: {{ $absen->pegawai->jabatan ?? 'Pegawai' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Lupa Absen</td>
                <td>: {{ \Carbon\Carbon::parse($absen->tanggal_absen)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Alasan</td>
                <td>: {{ ucwords(str_replace('_', ' ', $absen->alasan)) }}</td>
            </tr>
            <tr>
                <td class="label">Keterangan Rinci</td>
                <td>: {{ $absen->keterangan }}</td>
            </tr>
            <tr>
                <td class="label">Status Verifikasi</td>
                <td>: <strong style="text-transform: uppercase; color: {{ $absen->status === 'approved' ? '#059669' : ($absen->status === 'rejected' ? '#e11d48' : '#d97706') }}">{{ $absen->status }}</strong></td>
            </tr>
            @if($absen->catatan_admin)
            <tr>
                <td class="label">Catatan Admin</td>
                <td>: {{ $absen->catatan_admin }}</td>
            </tr>
            @endif
        </table>

        <p style="font-size: 13px; line-height: 1.6;">
            Demikian surat permohonan lupa absen ini saya sampaikan dengan sebenar-benarnya untuk dapat dijadikan periksa dan diproses sebagaimana mestinya.
        </p>

        <div class="signature-section">
            <p>Jakarta, {{ \Carbon\Carbon::parse($absen->created_at)->translatedFormat('d F Y') }}</p>
            <p>Pemohon,</p>
            <div class="signature-space"></div>
            <p><strong>{{ $absen->pegawai->nama ?? '-' }}</strong></p>
            <p>NIP. {{ $absen->pegawai->nip ?? '-' }}</p>
        </div>
    </div>

</body>
</html>
