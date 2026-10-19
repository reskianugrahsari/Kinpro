<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\MasterPpk;
use App\Models\Report;
use App\Models\ReportExpense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModulF4Test extends TestCase
{
    use RefreshDatabase;

    public function test_pajokka_index_can_be_rendered()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get(route('pajokka.index'));
        $response->assertStatus(200);
    }

    public function test_pegawai_can_create_pajokka_report()
    {
        $user = User::factory()->create(['role' => 'pegawai']);
        $unit = UnitKerja::create(['kode' => 'UK-01', 'nama' => 'Bagian Umum']);
        $pegawai = Pegawai::create([
            'user_id' => $user->id,
            'nama' => 'Test Pegawai Pajokka',
            'nip' => '199001012020011001',
            'jabatan' => 'Staff',
            'unit_kerja_id' => $unit->id,
        ]);
        $ppk = MasterPpk::create([
            'nama' => 'Test PPK',
            'nip' => '197001011995031001',
            'jabatan' => 'Pejabat Pembuat Komitmen',
        ]);

        $response = $this->actingAs($user)->post(route('pajokka.store'), [
            'nomor_surat' => 'SPPD/001/XI/2026',
            'tanggal_berangkat' => '2026-11-20',
            'tanggal_kembali' => '2026-11-22',
            'tujuan' => 'Bandung',
            'maksud_perjalanan' => 'Koordinasi Lapangan',
            'master_ppk_id' => $ppk->id,
            'expenses' => [
                [
                    'uraian' => 'Tiket Pesawat',
                    'nominal' => 1500000,
                    'metode_pembayaran' => 'KKP',
                ],
                [
                    'uraian' => 'Uang Harian',
                    'nominal' => 750000,
                    'metode_pembayaran' => 'Cash',
                ],
            ],
        ]);

        $response->assertRedirect(route('pajokka.index'));
        $this->assertDatabaseHas('reports', ['nomor_surat' => 'SPPD/001/XI/2026']);
        $this->assertDatabaseHas('report_expenses', ['uraian' => 'Tiket Pesawat']);
    }

    public function test_admin_can_update_report_status()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'pegawai']);
        $unit = UnitKerja::create(['kode' => 'UK-02', 'nama' => 'Bagian Keuangan']);
        $pegawai = Pegawai::create([
            'user_id' => $user->id,
            'nama' => 'Test Pegawai',
            'nip' => '199001012020011002',
            'jabatan' => 'Staff',
            'unit_kerja_id' => $unit->id,
        ]);
        $ppk = MasterPpk::create([
            'nama' => 'Test PPK',
            'nip' => '197001011995031002',
            'jabatan' => 'PPK',
        ]);

        $report = Report::create([
            'pegawai_id' => $pegawai->id,
            'nomor_surat' => 'SPPD/002/XI/2026',
            'tanggal_berangkat' => '2026-11-20',
            'tanggal_kembali' => '2026-11-22',
            'tujuan' => 'Surabaya',
            'maksud_perjalanan' => 'Monitoring',
            'master_ppk_id' => $ppk->id,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($admin)->patch(route('pajokka.status', $report), [
            'status' => 'approved',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reports', ['id' => $report->id, 'status' => 'approved']);
    }

    public function test_report_docx_export_works()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'pegawai']);
        $unit = UnitKerja::create(['kode' => 'UK-03', 'nama' => 'Bagian Perencanaan']);
        $pegawai = Pegawai::create([
            'user_id' => $user->id,
            'nama' => 'Test Pegawai',
            'nip' => '199001012020011003',
            'jabatan' => 'Staff',
            'unit_kerja_id' => $unit->id,
        ]);
        $ppk = MasterPpk::create([
            'nama' => 'Test PPK',
            'nip' => '197001011995031003',
            'jabatan' => 'PPK',
        ]);

        $report = Report::create([
            'pegawai_id' => $pegawai->id,
            'nomor_surat' => 'SPPD/003/XI/2026',
            'tanggal_berangkat' => '2026-11-20',
            'tanggal_kembali' => '2026-11-22',
            'tujuan' => 'Yogyakarta',
            'maksud_perjalanan' => 'Rapat Koordinasi',
            'master_ppk_id' => $ppk->id,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($admin)->get(route('pajokka.docx', $report));
        $response->assertStatus(200);
        $this->assertStringContainsString('word', $response->headers->get('Content-Type'));
    }
}
