<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RekapAndSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_rekap_page_can_be_rendered_for_authenticated_users(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $pegawai = \App\Models\Pegawai::create([
            'nama' => 'Test Pegawai',
            'nip' => '199001012020011001',
        ]);

        \App\Models\Penilaian::create([
            'pegawai_id' => $pegawai->id,
            'bulan' => 'Oktober',
            'tahun' => 2026,
            'nilai_kedisiplinan' => 90,
            'kinerja' => 85,
            'sikap' => 88,
            'kepemimpinan' => 80,
            'loyalitas' => 90,
            'it' => 85,
            'rata_rata' => 86.33,
        ]);

        $response = $this->actingAs($user)->get(route('rekap.index'));

        $response->assertStatus(200);
        $response->assertSee('Rekap & Statistik Gabungan', false);
    }

    public function test_rekap_export_csv_can_be_downloaded(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->get(route('rekap.export', 'csv'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'export',
        ]);
    }

    public function test_activity_log_records_login_and_logout(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $loginResponse = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $loginResponse->assertRedirect(route('dashboard', absolute: false));
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'login',
        ]);

        $logoutResponse = $this->actingAs($user)->post('/logout');
        $logoutResponse->assertRedirect('/');
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'logout',
        ]);
    }

    public function test_security_headers_are_present_in_response(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }
}
