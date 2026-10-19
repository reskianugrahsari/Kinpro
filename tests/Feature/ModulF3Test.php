<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\PengajuanAbsen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModulF3Test extends TestCase
{
    use RefreshDatabase;

    public function test_pegawai_can_view_and_create_lupa_absen()
    {
        $user = User::factory()->create(['role' => 'pegawai']);
        $pegawai = Pegawai::create([
            'user_id' => $user->id,
            'nama' => 'Budi Pegawai',
            'nip' => '199001012020011001',
            'jabatan' => 'Staff',
        ]);

        $this->actingAs($user)
             ->get(route('absen.index'))
             ->assertStatus(200);

        $response = $this->actingAs($user)->post(route('absen.store'), [
            'tanggal_lupa' => now()->format('Y-m-d'),
            'jenis_absen' => 'tidak mengisi absensi masuk',
            'alasan' => 'lupa_check_in',
            'keterangan' => 'Terburu-buru saat masuk kerja',
        ]);

        $response->assertRedirect(route('absen.index'));
        $this->assertDatabaseHas('pengajuan_absen', [
            'pegawai_id' => $pegawai->id,
            'jenis_absen' => 'tidak mengisi absensi masuk',
            'alasan' => 'lupa_check_in',
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_review_and_filter_lupa_absen()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pegawai = Pegawai::create([
            'nama' => 'Ani Pegawai',
            'nip' => '199002022020012002',
            'jabatan' => 'Staff',
        ]);
        $absen = PengajuanAbsen::create([
            'pegawai_id' => $pegawai->id,
            'tanggal_lupa' => now()->format('Y-m-d'),
            'tanggal_absen' => now()->format('Y-m-d'),
            'jenis_absen' => 'tidak mengisi absensi pulang',
            'alasan' => 'lupa_check_out',
            'keterangan' => 'Lupa absen pulang',
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
             ->get(route('absen.index', ['status' => 'pending']))
             ->assertStatus(200)
             ->assertSee($pegawai->nama);

        $response = $this->actingAs($admin)->patch(route('absen.status', $absen), [
            'status' => 'approved',
            'catatan_admin' => 'Disetujui oleh HRD',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('pengajuan_absen', [
            'id' => $absen->id,
            'status' => 'approved',
            'catatan_admin' => 'Disetujui oleh HRD',
        ]);
    }

    public function test_naskah_dinas_a4_preview_can_be_accessed()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $pegawai = Pegawai::create([
            'nama' => 'Candra Pegawai',
            'nip' => '199003032020011003',
            'jabatan' => 'Staff',
        ]);
        $absen = PengajuanAbsen::create([
            'pegawai_id' => $pegawai->id,
            'tanggal_lupa' => now()->format('Y-m-d'),
            'tanggal_absen' => now()->format('Y-m-d'),
            'jenis_absen' => 'tidak mengisi absensi masuk',
            'alasan' => 'kendala_sistem',
            'keterangan' => 'Server down',
            'status' => 'approved',
        ]);

        $this->actingAs($user)
             ->get(route('absen.show', $absen))
             ->assertStatus(200)
             ->assertSee('Surat Permohonan Lupa Absen')
             ->assertSee($pegawai->nama);
    }

    public function test_rekap_bulanan_page_accessible()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
             ->get(route('absen.rekap-bulanan'))
             ->assertStatus(200)
             ->assertSee('Rekapitulasi Bulanan Lupa Absen');
    }
}
