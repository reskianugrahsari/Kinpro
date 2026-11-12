<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\Penilaian;
use Illuminate\Database\Seeder;

class PenilaianTw3Seeder extends Seeder
{
    public function run(): void
    {
        $sangatBaikNames = [
            'Widyanto Hendro Saputro',
            'Hasbiah Syam',
            'Sarnaeni B',
            'Dicky Mulyadi',
            'Risna',
            'Ravitasari',
            'Yusdiana Milu',
            'Desyana S Linthin',
            'Joni Rassing',
            'Rizal',
            'Juwarni R',
            'Aslinda',
            'Nurwahida Nasir',
            'Ilham Effendy',
            'Andi Diah Permata Wulan',
            'Antonius Kala Lino',
            'Muh. Idris',
            'Rusyandi',
            'Abd. Rahman',
        ];

        $baikNames = [
            'Wahyuni A',
            'Hanapi',
            'Yusniarti Arsyad',
            'Teddy Akhmad Fadillah',
            'Amrullah Basri Paembonan',
            'Jumakri',
            'Benyamin T',
            'Erwin Setyawan',
            'Sri Dewi Sartika',
            'Amiruddin',
            'Yohanis Belo',
            'Muhammad Naim',
            'Nursiyah',
            'Hamzah Nur',
        ];

        // Seed TW3 2026 (September 2026 / Triwulan III)
        $bulanList = ['Juli', 'Agustus', 'September'];

        foreach ($sangatBaikNames as $idx => $name) {
            $pegawai = Pegawai::where('nama', 'like', "%{$name}%")->first();
            if (!$pegawai) continue;

            foreach ($bulanList as $bIdx => $bln) {
                // Ensure average is >= 85 (e.g. 88 - 92)
                $base = 88 + (($idx + $bIdx) % 5);
                Penilaian::updateOrCreate(
                    [
                        'nip' => $pegawai->nip,
                        'bulan' => $bln,
                        'tahun' => 2026,
                    ],
                    [
                        'pegawai_id' => $pegawai->id,
                        'nilai_kedisiplinan' => $base + 1,
                        'kinerja' => $base + 2,
                        'sikap' => $base,
                        'kepemimpinan' => $base - 1,
                        'loyalitas' => $base + 2,
                        'it' => $base - 2,
                        'predikat' => 'Sangat Baik',
                        'masukan_atasan' => 'Kinerja dan capaian output SKP Triwulan III sangat memuaskan serta melampaui target balai.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        foreach ($baikNames as $idx => $name) {
            $pegawai = Pegawai::where('nama', 'like', "%{$name}%")->first();
            if (!$pegawai) continue;

            foreach ($bulanList as $bIdx => $bln) {
                // Ensure average is between 78 and 84
                $base = 80 + (($idx + $bIdx) % 4);
                Penilaian::updateOrCreate(
                    [
                        'nip' => $pegawai->nip,
                        'bulan' => $bln,
                        'tahun' => 2026,
                    ],
                    [
                        'pegawai_id' => $pegawai->id,
                        'nilai_kedisiplinan' => $base,
                        'kinerja' => $base + 1,
                        'sikap' => $base,
                        'kepemimpinan' => $base - 1,
                        'loyalitas' => $base + 2,
                        'it' => $base - 2,
                        'predikat' => 'Baik',
                        'masukan_atasan' => 'Target kinerja SKP Triwulan III tercapai sesuai ekspektasi pimpinan balai.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        echo "Penilaian TW3 2026 successfully seeded for all 33 employees.\n";
    }
}
