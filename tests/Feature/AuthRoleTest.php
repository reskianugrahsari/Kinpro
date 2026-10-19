<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect('/dashboard');
    }

    public function test_pegawai_can_login_and_access_dashboard(): void
    {
        $pegawai = User::create([
            'name' => 'Pegawai Test',
            'email' => 'pegawai@test.com',
            'role' => 'pegawai',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'pegawai@test.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($pegawai);
        $response->assertRedirect('/dashboard');
    }

    public function test_admin_cannot_create_submissions_and_sees_review_menus_only(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin_role@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($admin)->get(route('izin.create'))
            ->assertRedirect(route('izin.index'))
            ->assertSessionHas('error');

        $this->actingAs($admin)->get(route('absen.create'))
            ->assertRedirect(route('absen.index'))
            ->assertSessionHas('error');

        $this->actingAs($admin)->get(route('pengaduan.create'))
            ->assertRedirect(route('pengaduan.index'))
            ->assertSessionHas('error');

        $this->actingAs($admin)->get(route('pajokka.create'))
            ->assertRedirect(route('pajokka.index'))
            ->assertSessionHas('error');

        // Check sidebar menu
        $dashboardResponse = $this->actingAs($admin)->get(route('dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Kelola Izin');
        $dashboardResponse->assertSee('Kelola Pengaduan');
        $dashboardResponse->assertSee('Kelola Lupa Absen');
        $dashboardResponse->assertSee('Review Perjalanan Dinas');
    }
}
