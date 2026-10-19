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

    public function test_pegawai_card_shows_photo_or_avatar_fallback()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pegawai = Pegawai::create([
            'nama' => 'Budi Santoso',
            'nip' => '198501012010011001',
            'jabatan' => 'Analis Kepegawaian',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.pegawai.index'));
        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        // Initial fallback
        $response->assertSee('BU');
    }

    public function test_pegawai_filters_and_view_toggle()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $p1 = Pegawai::create([
            'nama' => 'Ahmad Kasim',
            'nip' => '199001012015011001',
            'jabatan' => 'Pranata Komputer',
        ]);
        $p2 = Pegawai::create([
            'nama' => 'Siti Rahma',
            'nip' => '199505052023212005', // PPPK format
            'jabatan' => 'Pengelola Keuangan',
        ]);

        // Search test
        $resSearch = $this->actingAs($admin)->get(route('admin.pegawai.index', ['search' => 'Ahmad']));
        $resSearch->assertStatus(200);
        $resSearch->assertSee('Ahmad Kasim');
        $resSearch->assertDontSee('Siti Rahma');

        // Status test
        $resStatus = $this->actingAs($admin)->get(route('admin.pegawai.index', ['status' => 'PPPK']));
        $resStatus->assertStatus(200);
        $resStatus->assertSee('Siti Rahma');
        $resStatus->assertDontSee('Ahmad Kasim');

        // List view mode test
        $resList = $this->actingAs($admin)->get(route('admin.pegawai.index', ['view' => 'list']));
        $resList->assertStatus(200);
        $resList->assertSee('Skor Kinerja');
    }
}
