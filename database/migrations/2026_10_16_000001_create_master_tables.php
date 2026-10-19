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
            $table->string('nomor_surat', 100)->nullable();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->foreignId('atasan_id')->nullable()->constrained('pegawai')->nullOnDelete();
            $table->string('jenis_absen', 100);
            $table->text('alasan');
            $table->text('keterangan')->nullable();
            $table->date('tanggal_lupa')->nullable();
            $table->date('tanggal_pengajuan')->nullable();
            $table->date('tanggal_absen')->nullable();
            $table->string('kota_surat', 50)->default('Makassar');
            $table->string('bukti_path')->nullable();
            $table->string('status', 30)->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });

        // Reports Table (Pajokka - Perjalanan Dinas)
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawai')->cascadeOnDelete();
            $table->foreignId('ppk_id')->nullable()->constrained('master_ppk')->nullOnDelete();
            $table->foreignId('master_ppk_id')->nullable()->constrained('master_ppk')->nullOnDelete();
            $table->string('nomor_surat_tugas', 100)->nullable();
            $table->string('nomor_surat')->nullable();
            $table->string('nomor_sppd', 100)->nullable();
            $table->date('tanggal_surat_tugas')->nullable();
            $table->date('tgl_mulai_dinas')->nullable();
            $table->date('tgl_selesai_dinas')->nullable();
            $table->date('tanggal_berangkat')->nullable();
            $table->date('tanggal_kembali')->nullable();
            $table->string('kota_tujuan', 150)->nullable();
            $table->string('tujuan')->nullable();
            $table->json('daftar_pelaksana')->nullable();
            $table->text('isi_pointer')->nullable();
            $table->text('pointer_substansi')->nullable();
            $table->text('maksud_perjalanan')->nullable();
            $table->date('tanggal_sppd')->nullable();
            $table->string('opsi_tiket', 10)->default('kkp');
            $table->string('status', 30)->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->decimal('total_biaya', 15, 2)->default(0);
            $table->unsignedInteger('revision_count')->default(0);
            $table->unsignedInteger('completeness_score')->default(0);
            $table->timestamps();
        });

        // Report Schedules Table
        Schema::create('report_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->date('tanggal');
            $table->text('uraian_kegiatan');
            $table->text('lokasi_keterangan')->nullable();
            $table->timestamps();
        });

        // Report Expenses Table (Pajokka)
        Schema::create('report_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->string('expense_type', 50)->nullable();
            $table->string('uraian')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('nominal', 15, 2)->default(0);
            $table->string('payment_method', 20)->default('kkp');
            $table->string('metode_pembayaran', 20)->default('Cash');
            $table->date('transaction_date')->nullable();
            $table->text('description')->nullable();
            $table->string('bukti_pengeluaran_path')->nullable();
            $table->timestamps();
        });

        // Report Attachments Table
        Schema::create('report_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->string('category', 50);
            $table->string('file_path');
            $table->integer('sort_order')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Report Revisions Table
        Schema::create('report_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->string('section', 50)->nullable();
            $table->text('notes');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
        });

        // Kop Surat Table
        Schema::create('kop_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kementerian')->default('KEMENTERIAN PEKERJAAN UMUM');
            $table->string('nama_badan')->default('BADAN PENGEMBANGAN SUMBER DAYA MANUSIA');
            $table->string('nama_unit_kerja')->default('BALAI PENGEMBANGAN KOMPETENSI PUPR WILAYAH VIII MAKASSAR');
            $table->string('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });

        // Cities
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('province')->nullable();
            $table->timestamps();
        });

        // Transportation Types
        Schema::create('transportation_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Business Processes
        Schema::create('business_processes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Pivot
        Schema::create('report_business_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->foreignId('business_process_id')->constrained('business_processes')->cascadeOnDelete();
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
