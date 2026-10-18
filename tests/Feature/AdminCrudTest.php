<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UnitKerja;
use App\Models\MasterPpk;
use App\Models\Pegawai;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_admin_can_access_unit_kerja_index()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get(route('admin.unit-kerja.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_store_unit_kerja()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->post(route('admin.unit-kerja.store'), [
            'kode' => 'UK-TEST-99',
            'nama' => 'Unit Kerja Pengujian',
        ]);
        $response->assertRedirect(route('admin.unit-kerja.index'));
        $this->assertDatabaseHas('unit_kerja', ['kode' => 'UK-TEST-99']);
    }

    public function test_admin_can_access_master_ppk_index()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get(route('admin.master-ppk.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_store_master_ppk()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->post(route('admin.master-ppk.store'), [
            'nama' => 'Ir. PPK Test',
            'nip' => '198001012005011001',
            'jabatan' => 'PPK Fisik',
        ]);
        $response->assertRedirect(route('admin.master-ppk.index'));
        $this->assertDatabaseHas('master_ppk', ['nip' => '198001012005011001']);
    }

    public function test_admin_can_access_pegawai_index()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get(route('admin.pegawai.index'));
        $response->assertStatus(200);
    }
}
