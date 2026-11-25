<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KopSurat extends Model
{
    protected $table = 'kop_surat';

    protected $fillable = [
        'logo_path',
        'nama_kementerian',
        'nama_badan',
        'nama_unit_kerja',
        'alamat',
        'telepon',
        'email',
        'website',
    ];

    /**
     * Resolve standardized letterhead text fields, replacing legacy PUPR/Pengayoman defaults.
     */
    public static function displayData(?self $kop): array
    {
        $unitKerja = str_ireplace('PUPR', 'PU', $kop->nama_unit_kerja ?? 'BALAI PENGEMBANGAN KOMPETENSI PU WILAYAH VIII MAKASSAR');
        $isLegacy = $kop && str_contains((string) $kop->alamat, 'Pengayoman');
        $alamat = (!$kop || $isLegacy || empty($kop->alamat)) ? 'Jalan Nuri No.19-Maksassar 90122' : $kop->alamat;
        $telepon = (!$kop || $isLegacy || empty($kop->telepon)) ? '0411-873749/854547' : $kop->telepon;
        $email = (!$kop || $isLegacy || empty($kop->email)) ? 'bapekom8makassar@pu.go.id' : $kop->email;

        return [
            'kementerian' => $kop->nama_kementerian ?? 'KEMENTERIAN PEKERJAAN UMUM',
            'badan' => $kop->nama_badan ?? 'BADAN PENGEMBANGAN SUMBER DAYA MANUSIA',
            'unit_kerja' => $unitKerja,
            'address_line' => "{$alamat}, Telephone {$telepon}, Email: {$email}",
        ];
    }
}
