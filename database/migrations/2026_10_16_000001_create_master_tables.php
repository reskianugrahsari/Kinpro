<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Unit Kerja Table
        Schema::create('unit_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode')->unique();
            $table->timestamps();
        });

        // Master PPK Table (Pajokka)
        Schema::create('master_ppk', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nip');
            $table->string('jabatan');
            $table->timestamps();
        });

        // Pegawai Table (Unified Don't Forget & Pajokka & KinPro)
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nip')->unique();
            $table->string('nama');
            $table->string('jabatan')->nullable();
            $table->string('pangkat_gol')->nullable();
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerja')->nullOnDelete();
            $table->string('no_hp')->nullable();
            $table->timestamps();
        });

        // Pengajuan Absen Table (Don't Forget)
        Schema::create('pengajuan_absen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->date('tanggal_absen');
            $table->string('alasan');
            $table->text('keterangan');
            $table->string('bukti_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });

        // Reports Table (Pajokka - Perjalanan Dinas)
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->string('nomor_surat');
            $table->date('tanggal_berangkat');
            $table->date('tanggal_kembali');
            $table->string('tujuan');
            $table->text('maksud_perjalanan');
            $table->foreignId('master_ppk_id')->nullable()->constrained('master_ppk')->nullOnDelete();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->timestamps();
        });

        // Report Expenses Table (Pajokka)
        Schema::create('report_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->string('uraian');
            $table->decimal('nominal', 12, 2);
            $table->enum('metode_pembayaran', ['KKP', 'Cash'])->default('Cash');
            $table->string('bukti_pengeluaran_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_expenses');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('pengajuan_absen');
        Schema::dropIfExists('pegawai');
        Schema::dropIfExists('master_ppk');
        Schema::dropIfExists('unit_kerja');
    }
};
