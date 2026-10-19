<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Permohonan Lupa Absen - {{ $absen->pegawai->nama ?? 'Pegawai' }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: Arial, Tahoma, sans-serif;
            background-color: #f1f5f9;
            color: #000;
            margin: 0;
            padding: 20px 0;
        }
        .page {
            background: white;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 15mm 20mm 20mm 20mm;
            box-sizing: border-box;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .kop-surat img {
            width: 100%;
            height: auto;
            display: block;
            margin-bottom: 15px;
        }
        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            text-decoration: underline;
            text-transform: uppercase;
            margin: 20px 0 25px 0;
            letter-spacing: 0.5px;
        }
        .pembuka {
            font-size: 11pt;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .identitas-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11pt;
            margin-left: 20px;
            margin-bottom: 20px;
        }
        .identitas-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .identitas-table td.col-label {
            width: 160px;
        }
        .identitas-table td.col-colon {
            width: 20px;
            text-align: center;
        }
        .identitas-table td.col-val {
            font-weight: bold;
        }
        .isi-surat {
            font-size: 11pt;
            line-height: 1.8;
            text-align: justify;
            margin-bottom: 20px;
        }
        .penutup {
            font-size: 11pt;
            line-height: 1.6;
            margin-bottom: 40px;
        }
        .ttd-container {
            width: 100%;
            font-size: 11pt;
        }
        .ttd-date {
            text-align: right;
            margin-bottom: 25px;
        }
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        .ttd-table td {
            width: 50%;
            vertical-align: top;
        }
        .ttd-space {
            height: 90px;
        }
        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
        }
        .action-bar {
            width: 210mm;
            margin: 0 auto 15px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-print {
            background-color: #1769AA;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
        }
        .btn-back {
            background-color: #fff;
            color: #334155;
            border: 1px solid #cbd5e1;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: bold;
            border-radius: 8px;
            text-decoration: none;
        }
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            .action-bar {
                display: none !important;
            }
            .page {
                box-shadow: none !important;
                margin: 0 !important;
                width: 100% !important;
                min-height: 100vh !important;
                padding: 10mm 15mm 15mm 15mm !important;
            }
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <a href="{{ route('absen.index') }}" class="btn-back">&larr; Kembali ke Daftar Absen</a>
        <button onclick="window.print()" class="btn-print">&#128424; Cetak Dokumen (PDF/Print)</button>
    </div>

    <div class="page">
        <div>
            <!-- Kop Surat Resmi Balai Diklat Wil. VIII -->
            <div class="kop-surat">
                <img src="{{ asset('images/kop_surat.jpg') }}" alt="Kop Surat Resmi Balai Wilayah VIII">
            </div>

            <!-- Judul Surat Naskah Dinas -->
            <div class="judul">
                Surat Permohonan Izin / Pemberitahuan
            </div>

            <!-- Pembuka -->
            <div class="pembuka">
                Yang bertanda tangan di bawah ini:
            </div>

            <!-- Identitas Pemohon -->
            <table class="identitas-table">
                <tr>
                    <td class="col-label">Nama</td>
                    <td class="col-colon">:</td>
                    <td class="col-val">{{ $absen->pegawai->nama ?? $absen->pegawai->nama_lengkap ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="col-label">NIP</td>
                    <td class="col-colon">:</td>
                    <td class="col-val" style="font-family: monospace;">{{ $absen->pegawai->nip ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="col-label">Pangkat/Golongan</td>
                    <td class="col-colon">:</td>
                    <td class="col-val" style="font-weight: normal;">{{ $absen->pegawai->pangkat_gol ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="col-label">Jabatan</td>
                    <td class="col-colon">:</td>
                    <td class="col-val" style="font-weight: normal;">{{ $absen->pegawai->jabatan ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="col-label">Bagian</td>
                    <td class="col-colon">:</td>
                    <td class="col-val" style="font-weight: normal;">{{ $absen->pegawai->unitKerja->nama ?? 'Balai Pengembangan Kompetensi Wilayah VIII Makassar' }}</td>
                </tr>
            </table>

            @php
                $tglLupa = $absen->tanggal_lupa ?? $absen->tanggal_absen;
                $tglPengajuan = $absen->tanggal_pengajuan ?? ($absen->created_at ? $absen->created_at->format('Y-m-d') : now()->format('Y-m-d'));
            @endphp

            <!-- Isi Surat -->
            <div class="isi-surat">
                Dengan ini menyampaikan bahwa saya <strong><u>{{ $absen->jenis_absen }}</u></strong> pada hari <strong>{{ $absen->hari_lupa }}</strong>, tanggal <strong>{{ $tglLupa ? $tglLupa->translatedFormat('d F Y') : '-' }}</strong> dikarenakan <em>{{ $absen->alasan }}</em>.
            </div>

            <!-- Penutup -->
            <div class="penutup">
                Demikian permohonan/pemberitahuan ini saya sampaikan, atas perhatian Bapak/Ibu diucapkan terima kasih.
            </div>
        </div>

        <!-- Bagian Tanda Tangan 2 Kolom -->
        <div class="ttd-container">
            <div class="ttd-date">
                {{ $absen->kota_surat ?? 'Makassar' }}, {{ $tglPengajuan ? \Carbon\Carbon::parse($tglPengajuan)->translatedFormat('d F Y') : '-' }}
            </div>
            <table class="ttd-table">
                <tr>
                    <td>
                        <div>Pegawai Pemohon,</div>
                        <div class="ttd-space"></div>
                        <div class="ttd-nama">{{ $absen->pegawai->nama ?? $absen->pegawai->nama_lengkap ?? 'Pegawai Pemohon' }}</div>
                        <div style="font-family: monospace; font-size: 10pt; margin-top: 3px;">
                            {{ $absen->pegawai?->nip ? 'NIP. ' . $absen->pegawai->nip : '' }}
                        </div>
                    </td>
                    <td>
                        <div>Atasan Langsung,</div>
                        <div class="ttd-space"></div>
                        <div class="ttd-nama">{{ $absen->atasan?->nama ?? 'Atasan Langsung' }}</div>
                        <div style="font-family: monospace; font-size: 10pt; margin-top: 3px;">
                            {{ $absen->atasan?->nip ? 'NIP. ' . $absen->atasan->nip : '' }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>
