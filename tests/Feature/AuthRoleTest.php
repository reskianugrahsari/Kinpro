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
}
