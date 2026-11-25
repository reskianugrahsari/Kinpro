<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('kop_surat')) {
            DB::table('kop_surat')->update([
                'nama_unit_kerja' => 'BALAI PENGEMBANGAN KOMPETENSI PU WILAYAH VIII MAKASSAR',
                'alamat' => 'Jalan Nuri No.19-Maksassar 90122',
                'telepon' => '0411-873749/854547',
                'email' => 'bapekom8makassar@pu.go.id',
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('kop_surat')) {
            DB::table('kop_surat')->update([
                'nama_unit_kerja' => 'BALAI PENGEMBANGAN KOMPETENSI PUPR WILAYAH VIII MAKASSAR',
                'alamat' => 'Jl. Pengayoman No. 1, Panakkukang, Makassar 90231',
                'telepon' => '(0411) 453123',
                'email' => 'bapekom8@pu.go.id',
                'updated_at' => now(),
            ]);
        }
    }
};
