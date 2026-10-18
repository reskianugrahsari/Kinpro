<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_admin_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin Dashboard',
            'email' => 'admin_dash@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }

    public function test_pegawai_can_view_pegawai_dashboard(): void
    {
        $user = User::create([
            'name' => 'Pegawai Dashboard',
            'email' => 'pegawai_dash@test.com',
            'role' => 'pegawai',
            'password' => bcrypt('password'),
        ]);

        $unit = UnitKerja::create([
            'kode' => 'TST',
            'nama' => 'Test Unit',
        ]);

        Pegawai::create([
            'user_id' => $user->id,
            'nip' => '123456789012345678',
            'nama' => 'Pegawai Dashboard',
            'jabatan' => 'Staff',
            'pangkat_gol' => 'III/a',
            'unit_kerja_id' => $unit->id,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Pegawai');
        $response->assertSee('123456789012345678');
    }
}
