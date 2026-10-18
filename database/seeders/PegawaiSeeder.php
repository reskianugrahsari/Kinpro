<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitKerja;
use App\Models\MasterPpk;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Unit Kerja
        $unit1 = UnitKerja::firstOrCreate(['kode' => 'BKD'], ['nama' => 'Badan Kepegawaian Daerah']);
        $unit2 = UnitKerja::firstOrCreate(['kode' => 'DISKOMINFO'], ['nama' => 'Dinas Komunikasi dan Informatika']);

        // 2. Seed Master PPK
        MasterPpk::firstOrCreate(
            ['nip' => '198001012005011001'],
            [
                'nama' => 'Dr. H. PPK Utama, M.Si',
                'jabatan' => 'Kepala Dinas / Pejabat Pembuat Komitmen',
            ]
        );

        // 3. Seed Pegawai & User accounts
        $userPegawai = User::firstOrCreate(
            ['email' => 'pegawai1@kinpro.test'],
            [
                'name' => 'Ahmad Pegawai, S.Kom',
                'role' => 'pegawai',
                'password' => Hash::make('password'),
            ]
        );

        Pegawai::firstOrCreate(
            ['nip' => '199205122019031005'],
            [
                'user_id' => $userPegawai->id,
                'nama' => 'Ahmad Pegawai, S.Kom',
                'jabatan' => 'Pranata Komputer Ahli Pertama',
                'pangkat_gol' => 'Penata Muda / III/a',
                'unit_kerja_id' => $unit2->id,
                'no_hp' => '081234567890',
            ]
        );
    }
}
