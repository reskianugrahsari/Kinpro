<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UnitKerja;
use App\Models\MasterPpk;
use App\Models\Pegawai;
use App\Models\Penilaian;
use App\Models\Izin;
use App\Models\Pengaduan;
use App\Models\RatingKomentar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModulF2Test extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_all_f2_modules()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $unitKerja = UnitKerja::create(['nama' => 'Bagian Umum', 'kode' => 'BG_UMUM']);
        $masterPpk = MasterPpk::create(['nama' => 'PPK Test', 'nip' => '123456789', 'jabatan' => 'PPK']);
        $pegawai = Pegawai::create([
            'user_id' => $admin->id,
            'nip' => '987654321',
            'nama' => 'Admin Test',
            'unit_kerja_id' => $unitKerja->id,
        ]);

        $this->actingAs($admin)
             ->get('/dashboard')->assertStatus(200);

        $this->actingAs($admin)
             ->get('/admin/unit-kerja')->assertStatus(200);

        $this->actingAs($admin)
             ->get('/admin/master-ppk')->assertStatus(200);

        $this->actingAs($admin)
             ->get('/admin/pegawai')->assertStatus(200);

        $this->actingAs($admin)
             ->get('/penilaian')->assertStatus(200);

        $this->actingAs($admin)
             ->get('/izin')->assertStatus(200);

        $this->actingAs($admin)
             ->get('/pengaduan')->assertStatus(200);

        $this->actingAs($admin)
             ->get('/rating')->assertStatus(200);

        $this->actingAs($admin)
             ->get('/profile')->assertStatus(200);
    }
}
