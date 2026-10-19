<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Schema\Blueprint;

echo "--- Starting harmonization of kemenpu2 database ---\n";

// 1. Table users
if (Schema::hasTable('users')) {
    echo "Harmonizing table: users...\n";
    DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'karyawan'");
    
    if (!Schema::hasColumn('users', 'name')) {
        DB::statement("ALTER TABLE users ADD COLUMN name VARCHAR(100) NULL AFTER id");
        DB::statement("UPDATE users SET name = nama WHERE name IS NULL OR name = ''");
    }
    if (!Schema::hasColumn('users', 'remember_token')) {
        DB::statement("ALTER TABLE users ADD COLUMN remember_token VARCHAR(100) NULL AFTER password");
    }
    if (!Schema::hasColumn('users', 'email_verified_at')) {
        DB::statement("ALTER TABLE users ADD COLUMN email_verified_at TIMESTAMP NULL AFTER remember_token");
    }
    if (!Schema::hasColumn('users', 'updated_at')) {
        DB::statement("ALTER TABLE users ADD COLUMN updated_at TIMESTAMP NULL AFTER created_at");
        DB::statement("UPDATE users SET updated_at = created_at WHERE updated_at IS NULL");
    }
}

// 2. Table unit_kerja
if (!Schema::hasTable('unit_kerja')) {
    echo "Creating table: unit_kerja...\n";
    Schema::create('unit_kerja', function (Blueprint $table) {
        $table->increments('id');
        $table->string('nama_unit', 150);
        $table->string('singkatan', 50)->nullable();
        $table->string('kepala_unit', 100)->nullable();
        $table->string('nip_kepala', 50)->nullable();
        $table->timestamps();
    });

    DB::table('unit_kerja')->insert([
        [
            'nama_unit' => 'Balai Pengembangan Kompetensi PUPR Wilayah VIII Makassar',
            'singkatan' => 'Bapekom Wil. VIII',
            'kepala_unit' => 'Widyanto Hendro Saputro, ST, M.Si',
            'nip_kepala' => '19740612200812100',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama_unit' => 'Seksi Penyelenggaraan',
            'singkatan' => 'Seksi Penyelenggaraan',
            'kepala_unit' => 'Sarnaeni B,SP,M.T',
            'nip_kepala' => '198411032010122002',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama_unit' => 'Subbagian Umum dan Tata Usaha',
            'singkatan' => 'Subbag Umum & TU',
            'kepala_unit' => 'Wahyuni A, ST, MT',
            'nip_kepala' => '198107042009122001',
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);
}

// 3. Table pegawai
if (Schema::hasTable('pegawai')) {
    echo "Harmonizing table: pegawai...\n";
    if (!Schema::hasColumn('pegawai', 'user_id')) {
        DB::statement("ALTER TABLE pegawai ADD COLUMN user_id INT(11) NULL AFTER id_pegawai");
    }
    if (!Schema::hasColumn('pegawai', 'nama')) {
        DB::statement("ALTER TABLE pegawai ADD COLUMN nama VARCHAR(100) NULL AFTER user_id");
        DB::statement("UPDATE pegawai SET nama = nama_lengkap WHERE nama IS NULL OR nama = ''");
    }
    if (!Schema::hasColumn('pegawai', 'pangkat_gol')) {
        DB::statement("ALTER TABLE pegawai ADD COLUMN pangkat_gol VARCHAR(50) NULL AFTER jabatan");
    }
    if (!Schema::hasColumn('pegawai', 'unit_kerja_id')) {
        DB::statement("ALTER TABLE pegawai ADD COLUMN unit_kerja_id INT(11) NULL AFTER pangkat_gol");
        DB::statement("UPDATE pegawai SET unit_kerja_id = 1 WHERE unit_kerja_id IS NULL");
    }
    if (!Schema::hasColumn('pegawai', 'no_hp')) {
        DB::statement("ALTER TABLE pegawai ADD COLUMN no_hp VARCHAR(50) NULL AFTER unit_kerja_id");
    }
    if (!Schema::hasColumn('pegawai', 'created_at')) {
        DB::statement("ALTER TABLE pegawai ADD COLUMN created_at TIMESTAMP NULL AFTER tanggal_dibuat");
        DB::statement("UPDATE pegawai SET created_at = tanggal_dibuat WHERE created_at IS NULL");
    }
    if (!Schema::hasColumn('pegawai', 'updated_at')) {
        DB::statement("ALTER TABLE pegawai ADD COLUMN updated_at TIMESTAMP NULL AFTER created_at");
        DB::statement("UPDATE pegawai SET updated_at = created_at WHERE updated_at IS NULL");
    }

    // Link or create user accounts for all pegawai
    $pegawais = DB::table('pegawai')->get();
    foreach ($pegawais as $p) {
        $email = !empty($p->username) && str_contains($p->username, '@') 
            ? $p->username 
            : ($p->nip ? $p->nip . '@pu.go.id' : 'pegawai_' . $p->id_pegawai . '@pu.go.id');
        
        $role = ($p->role === 'admin') ? 'admin' : 'pegawai';

        $user = DB::table('users')->where('email', $email)->first();
        if (!$user) {
            $userId = DB::table('users')->insertGetId([
                'nama' => $p->nama_lengkap,
                'name' => $p->nama_lengkap,
                'email' => $email,
                'password' => !empty($p->password) ? $p->password : Hash::make('password'),
                'role' => $role,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $userId = $user->id;
            DB::table('users')->where('id', $userId)->update([
                'role' => $role,
                'name' => $p->nama_lengkap,
            ]);
        }

        DB::table('pegawai')->where('id_pegawai', $p->id_pegawai)->update([
            'user_id' => $userId,
            'nama' => $p->nama_lengkap,
        ]);
    }
}

// Ensure default admin & test pegawai exist for test compatibility & easy login
$adminUser = DB::table('users')->where('email', 'admin@kinpro.test')->first();
if (!$adminUser) {
    DB::table('users')->insert([
        'nama' => 'Administrator KinPro',
        'name' => 'Administrator KinPro',
        'email' => 'admin@kinpro.test',
        'password' => Hash::make('password'),
        'role' => 'admin',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

$testPegawaiUser = DB::table('users')->where('email', 'pegawai1@kinpro.test')->first();
if (!$testPegawaiUser) {
    $testUserId = DB::table('users')->insertGetId([
        'nama' => 'Pegawai Satu',
        'name' => 'Pegawai Satu',
        'email' => 'pegawai1@kinpro.test',
        'password' => Hash::make('password'),
        'role' => 'pegawai',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Ensure test pegawai record with NIP 123456789012345678 exists for DashboardTest assertions
    $pTest = DB::table('pegawai')->where('nip', '123456789012345678')->first();
    if (!$pTest) {
        DB::table('pegawai')->insert([
            'user_id' => $testUserId,
            'nama' => 'Pegawai Satu',
            'nama_lengkap' => 'Pegawai Satu',
            'nip' => '123456789012345678',
            'jabatan' => 'Pranata Komputer Ahli Muda',
            'pangkat_gol' => 'Penata Tk.I / III/d',
            'unit_kerja_id' => 1,
            'username' => 'pegawai1@kinpro.test',
            'password' => Hash::make('password'),
            'role' => 'user',
            'tanggal_dibuat' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    } else {
        DB::table('pegawai')->where('nip', '123456789012345678')->update([
            'user_id' => $testUserId
        ]);
    }
}

// 4. Table master_ppk
if (!Schema::hasTable('master_ppk')) {
    echo "Creating table: master_ppk...\n";
    Schema::create('master_ppk', function (Blueprint $table) {
        $table->increments('id');
        $table->string('nama_ppk', 100);
        $table->string('nip_ppk', 50);
        $table->string('jabatan', 100)->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });

    DB::table('master_ppk')->insert([
        [
            'nama_ppk' => 'Ir. H. Muhammad Arsyad, MT',
            'nip_ppk' => '196805141994031003',
            'jabatan' => 'PPK Pembinaan Teknis',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama_ppk' => 'Dra. Hj. Nurhidayah, M.Si',
            'nip_ppk' => '197103211997022001',
            'jabatan' => 'PPK Administrasi Keuangan',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);
}

// 5. Table reports (Pajokka)
if (!Schema::hasTable('reports')) {
    echo "Creating table: reports...\n";
    Schema::create('reports', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedBigInteger('user_id')->nullable();
        $table->unsignedInteger('pegawai_id')->nullable();
        $table->unsignedInteger('ppk_id')->nullable();
        $table->unsignedInteger('master_ppk_id')->nullable();
        $table->string('nomor_surat_tugas', 100)->nullable();
        $table->string('nomor_surat', 100)->nullable();
        $table->string('nomor_sppd', 100)->nullable();
        $table->date('tanggal_surat_tugas')->nullable();
        $table->date('tgl_mulai_dinas')->nullable();
        $table->date('tgl_selesai_dinas')->nullable();
        $table->date('tanggal_berangkat')->nullable();
        $table->date('tanggal_kembali')->nullable();
        $table->string('kota_tujuan', 150)->nullable();
        $table->string('tujuan', 150)->nullable();
        $table->text('maksud_perjalanan')->nullable();
        $table->json('daftar_pelaksana')->nullable();
        $table->text('isi_pointer')->nullable();
        $table->text('pointer_substansi')->nullable();
        $table->date('tanggal_sppd')->nullable();
        $table->string('opsi_tiket', 10)->default('kkp');
        $table->string('status', 30)->default('draft');
        $table->timestamp('submitted_at')->nullable();
        $table->unsignedBigInteger('reviewed_by')->nullable();
        $table->timestamp('reviewed_at')->nullable();
        $table->unsignedBigInteger('approved_by')->nullable();
        $table->timestamp('approved_at')->nullable();
        $table->timestamp('rejected_at')->nullable();
        $table->timestamp('completed_at')->nullable();
        $table->decimal('total_cost', 15, 2)->default(0);
        $table->decimal('total_biaya', 15, 2)->default(0);
        $table->unsignedInteger('revision_count')->default(0);
        $table->unsignedInteger('completeness_score')->default(0);
        $table->text('catatan')->nullable();
        $table->timestamps();
    });
} else {
    Schema::table('reports', function (Blueprint $table) {
        if (!Schema::hasColumn('reports', 'user_id')) $table->unsignedBigInteger('user_id')->nullable();
        if (!Schema::hasColumn('reports', 'nomor_surat_tugas')) $table->string('nomor_surat_tugas', 100)->nullable();
        if (!Schema::hasColumn('reports', 'tanggal_surat_tugas')) $table->date('tanggal_surat_tugas')->nullable();
        if (!Schema::hasColumn('reports', 'tgl_mulai_dinas')) $table->date('tgl_mulai_dinas')->nullable();
        if (!Schema::hasColumn('reports', 'tgl_selesai_dinas')) $table->date('tgl_selesai_dinas')->nullable();
        if (!Schema::hasColumn('reports', 'kota_tujuan')) $table->string('kota_tujuan', 150)->nullable();
        if (!Schema::hasColumn('reports', 'daftar_pelaksana')) $table->json('daftar_pelaksana')->nullable();
        if (!Schema::hasColumn('reports', 'isi_pointer')) $table->text('isi_pointer')->nullable();
        if (!Schema::hasColumn('reports', 'pointer_substansi')) $table->text('pointer_substansi')->nullable();
        if (!Schema::hasColumn('reports', 'tanggal_sppd')) $table->date('tanggal_sppd')->nullable();
        if (!Schema::hasColumn('reports', 'opsi_tiket')) $table->string('opsi_tiket', 10)->default('kkp');
        if (!Schema::hasColumn('reports', 'submitted_at')) $table->timestamp('submitted_at')->nullable();
        if (!Schema::hasColumn('reports', 'reviewed_by')) $table->unsignedBigInteger('reviewed_by')->nullable();
        if (!Schema::hasColumn('reports', 'reviewed_at')) $table->timestamp('reviewed_at')->nullable();
        if (!Schema::hasColumn('reports', 'approved_by')) $table->unsignedBigInteger('approved_by')->nullable();
        if (!Schema::hasColumn('reports', 'approved_at')) $table->timestamp('approved_at')->nullable();
        if (!Schema::hasColumn('reports', 'rejected_at')) $table->timestamp('rejected_at')->nullable();
        if (!Schema::hasColumn('reports', 'completed_at')) $table->timestamp('completed_at')->nullable();
        if (!Schema::hasColumn('reports', 'total_cost')) $table->decimal('total_cost', 15, 2)->default(0);
        if (!Schema::hasColumn('reports', 'revision_count')) $table->unsignedInteger('revision_count')->default(0);
        if (!Schema::hasColumn('reports', 'completeness_score')) $table->unsignedInteger('completeness_score')->default(0);
    });
}

// 5b. Table report_schedules
if (!Schema::hasTable('report_schedules')) {
    echo "Creating table: report_schedules...\n";
    Schema::create('report_schedules', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('report_id');
        $table->date('tanggal');
        $table->text('uraian_kegiatan');
        $table->text('lokasi_keterangan')->nullable();
        $table->timestamps();
    });
}

// 6. Table report_expenses (Pajokka expenses)
if (!Schema::hasTable('report_expenses')) {
    echo "Creating table: report_expenses...\n";
    Schema::create('report_expenses', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('report_id');
        $table->string('expense_type', 50)->nullable();
        $table->string('kategori', 50)->nullable();
        $table->string('keterangan', 255)->nullable();
        $table->decimal('amount', 15, 2)->default(0);
        $table->decimal('jumlah', 15, 2)->default(0);
        $table->string('payment_method', 20)->default('kkp');
        $table->string('metode_pembayaran', 30)->default('tunai');
        $table->date('transaction_date')->nullable();
        $table->text('description')->nullable();
        $table->string('bukti_path', 255)->nullable();
        $table->timestamps();
    });
} else {
    Schema::table('report_expenses', function (Blueprint $table) {
        if (!Schema::hasColumn('report_expenses', 'expense_type')) $table->string('expense_type', 50)->nullable();
        if (!Schema::hasColumn('report_expenses', 'amount')) $table->decimal('amount', 15, 2)->default(0);
        if (!Schema::hasColumn('report_expenses', 'payment_method')) $table->string('payment_method', 20)->default('kkp');
        if (!Schema::hasColumn('report_expenses', 'transaction_date')) $table->date('transaction_date')->nullable();
        if (!Schema::hasColumn('report_expenses', 'description')) $table->text('description')->nullable();
    });
}

// 6b. Table report_attachments
if (!Schema::hasTable('report_attachments')) {
    echo "Creating table: report_attachments...\n";
    Schema::create('report_attachments', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('report_id');
        $table->string('category', 50);
        $table->string('file_path', 255);
        $table->integer('sort_order')->nullable();
        $table->unsignedBigInteger('uploaded_by')->nullable();
        $table->timestamps();
    });
}

// 6c. Table report_revisions
if (!Schema::hasTable('report_revisions')) {
    echo "Creating table: report_revisions...\n";
    Schema::create('report_revisions', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('report_id');
        $table->unsignedBigInteger('requested_by');
        $table->string('section', 50)->nullable();
        $table->text('notes');
        $table->timestamp('created_at')->nullable();
        $table->timestamp('resolved_at')->nullable();
    });
}

// 6d. Table kop_surat
if (!Schema::hasTable('kop_surat')) {
    echo "Creating table: kop_surat...\n";
    Schema::create('kop_surat', function (Blueprint $table) {
        $table->increments('id');
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

    DB::table('kop_surat')->insert([
        'nama_kementerian' => 'KEMENTERIAN PEKERJAAN UMUM',
        'nama_badan' => 'BADAN PENGEMBANGAN SUMBER DAYA MANUSIA',
        'nama_unit_kerja' => 'BALAI PENGEMBANGAN KOMPETENSI PUPR WILAYAH VIII MAKASSAR',
        'alamat' => 'Jl. Pengayoman No. 1, Panakkukang, Makassar 90231',
        'telepon' => '(0411) 453123',
        'email' => 'bapekom8@pu.go.id',
        'website' => 'https://bpsdm.pu.go.id',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

// 6e. Table cities, transportation_types, business_processes
if (!Schema::hasTable('cities')) {
    echo "Creating table: cities...\n";
    Schema::create('cities', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name');
        $table->string('province')->nullable();
        $table->timestamps();
    });

    DB::table('cities')->insert([
        ['name' => 'Makassar', 'province' => 'Sulawesi Selatan', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Jakarta', 'province' => 'DKI Jakarta', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Bandung', 'province' => 'Jawa Barat', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Yogyakarta', 'province' => 'DI Yogyakarta', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Surabaya', 'province' => 'Jawa Timur', 'created_at' => now(), 'updated_at' => now()],
    ]);
}

if (!Schema::hasTable('transportation_types')) {
    echo "Creating table: transportation_types...\n";
    Schema::create('transportation_types', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name');
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });

    DB::table('transportation_types')->insert([
        ['name' => 'Pesawat Terbang', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Kendaraan Dinas / Mobil', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Kereta Api', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Kapal Laut', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
    ]);
}

if (!Schema::hasTable('business_processes')) {
    echo "Creating table: business_processes...\n";
    Schema::create('business_processes', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name');
        $table->string('code')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

if (!Schema::hasTable('report_business_processes')) {
    echo "Creating table: report_business_processes...\n";
    Schema::create('report_business_processes', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('report_id');
        $table->unsignedInteger('business_process_id');
        $table->timestamps();
    });
}

// 7. Table pengajuan_absen (Don't Forget)
if (!Schema::hasTable('pengajuan_absen')) {
    echo "Creating table: pengajuan_absen...\n";
    Schema::create('pengajuan_absen', function (Blueprint $table) {
        $table->increments('id');
        $table->string('nomor_surat', 100)->nullable();
        $table->unsignedInteger('pegawai_id');
        $table->unsignedInteger('atasan_id')->nullable();
        $table->string('jenis_absen', 100);
        $table->time('jam')->nullable();
        $table->text('alasan');
        $table->date('tanggal_lupa')->nullable();
        $table->date('tanggal_pengajuan')->nullable();
        $table->date('tanggal_absen')->nullable();
        $table->string('kota_surat', 50)->default('Makassar');
        $table->string('status', 30)->default('pending');
        $table->string('bukti_path', 255)->nullable();
        $table->text('catatan_admin')->nullable();
        $table->timestamps();
    });
} else {
    Schema::table('pengajuan_absen', function (Blueprint $table) {
        if (!Schema::hasColumn('pengajuan_absen', 'nomor_surat')) $table->string('nomor_surat', 100)->nullable();
        if (!Schema::hasColumn('pengajuan_absen', 'atasan_id')) $table->unsignedInteger('atasan_id')->nullable();
        if (!Schema::hasColumn('pengajuan_absen', 'tanggal_lupa')) $table->date('tanggal_lupa')->nullable();
        if (!Schema::hasColumn('pengajuan_absen', 'tanggal_pengajuan')) $table->date('tanggal_pengajuan')->nullable();
        if (!Schema::hasColumn('pengajuan_absen', 'kota_surat')) $table->string('kota_surat', 50)->default('Makassar');
        if (!Schema::hasColumn('pengajuan_absen', 'keterangan')) $table->text('keterangan')->nullable();
    });
}

// 8. Table penilaian
if (Schema::hasTable('penilaian')) {
    echo "Harmonizing table: penilaian...\n";
    if (!Schema::hasColumn('penilaian', 'pegawai_id')) {
        DB::statement("ALTER TABLE penilaian ADD COLUMN pegawai_id INT(11) NULL AFTER id_penilaian");
        DB::statement("UPDATE penilaian p JOIN pegawai pg ON p.nip = pg.nip SET p.pegawai_id = pg.id_pegawai");
    }
    if (!Schema::hasColumn('penilaian', 'predikat')) {
        DB::statement("ALTER TABLE penilaian ADD COLUMN predikat VARCHAR(50) NULL AFTER rata_rata");
        DB::statement("UPDATE penilaian SET predikat = CASE 
            WHEN rata_rata >= 90 THEN 'Sangat Baik'
            WHEN rata_rata >= 76 THEN 'Baik'
            WHEN rata_rata >= 61 THEN 'Cukup'
            ELSE 'Kurang'
        END WHERE predikat IS NULL OR predikat = ''");
    }
    if (!Schema::hasColumn('penilaian', 'created_at')) {
        DB::statement("ALTER TABLE penilaian ADD COLUMN created_at TIMESTAMP NULL AFTER tanggal_input");
        DB::statement("UPDATE penilaian SET created_at = tanggal_input WHERE created_at IS NULL");
    }
    if (!Schema::hasColumn('penilaian', 'updated_at')) {
        DB::statement("ALTER TABLE penilaian ADD COLUMN updated_at TIMESTAMP NULL AFTER created_at");
        DB::statement("UPDATE penilaian SET updated_at = created_at WHERE updated_at IS NULL");
    }
}

// 9. Table izin
if (Schema::hasTable('izin')) {
    echo "Harmonizing table: izin...\n";
    if (!Schema::hasColumn('izin', 'pegawai_id')) {
        DB::statement("ALTER TABLE izin ADD COLUMN pegawai_id INT(11) NULL AFTER id_pegawai");
        DB::statement("UPDATE izin SET pegawai_id = id_pegawai WHERE pegawai_id IS NULL");
    }
    if (!Schema::hasColumn('izin', 'created_at')) {
        DB::statement("ALTER TABLE izin ADD COLUMN created_at TIMESTAMP NULL AFTER tanggal_respon");
        DB::statement("UPDATE izin SET created_at = tanggal_pengajuan WHERE created_at IS NULL");
    }
    if (!Schema::hasColumn('izin', 'updated_at')) {
        DB::statement("ALTER TABLE izin ADD COLUMN updated_at TIMESTAMP NULL AFTER created_at");
        DB::statement("UPDATE izin SET updated_at = COALESCE(tanggal_respon, tanggal_pengajuan) WHERE updated_at IS NULL");
    }
}

// 9b. Table pengaduan
if (Schema::hasTable('pengaduan')) {
    echo "Harmonizing table: pengaduan...\n";
    if (!Schema::hasColumn('pengaduan', 'created_at')) {
        DB::statement("ALTER TABLE pengaduan ADD COLUMN created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP");
        DB::statement("UPDATE pengaduan SET created_at = tanggal_pengaduan WHERE created_at IS NULL");
    }
    if (!Schema::hasColumn('pengaduan', 'updated_at')) {
        DB::statement("ALTER TABLE pengaduan ADD COLUMN updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        DB::statement("UPDATE pengaduan SET updated_at = tanggal_pengaduan WHERE updated_at IS NULL");
    }
    if (!Schema::hasColumn('pengaduan', 'pegawai_id')) {
        DB::statement("ALTER TABLE pengaduan ADD COLUMN pegawai_id INT(11) NULL AFTER nip");
        DB::statement("UPDATE pengaduan p JOIN pegawai pg ON p.nip = pg.nip SET p.pegawai_id = pg.id_pegawai");
    }
}

// 10. Table rating_komentar
if (!Schema::hasTable('rating_komentar')) {
    echo "Creating table: rating_komentar...\n";
    Schema::create('rating_komentar', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('pegawai_id');
        $table->unsignedInteger('penilai_id')->nullable();
        $table->integer('rating')->default(5);
        $table->text('komentar')->nullable();
        $table->string('bulan', 20)->nullable();
        $table->integer('tahun')->nullable();
        $table->timestamps();
    });
}

// 11. Table activity_logs
if (!Schema::hasTable('activity_logs')) {
    echo "Creating table: activity_logs...\n";
    Schema::create('activity_logs', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('user_id')->nullable();
        $table->string('action', 50);
        $table->text('description')->nullable();
        $table->json('properties')->nullable();
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->timestamps();
    });
}

// 12. Sessions & Cache tables
if (!Schema::hasTable('sessions')) {
    echo "Creating table: sessions...\n";
    Schema::create('sessions', function (Blueprint $table) {
        $table->string('id')->primary();
        $table->foreignId('user_id')->nullable()->index();
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->longText('payload');
        $table->integer('last_activity')->index();
    });
}

if (!Schema::hasTable('password_reset_tokens')) {
    echo "Creating table: password_reset_tokens...\n";
    Schema::create('password_reset_tokens', function (Blueprint $table) {
        $table->string('email')->primary();
        $table->string('token');
        $table->timestamp('created_at')->nullable();
    });
}

if (!Schema::hasTable('cache')) {
    echo "Creating table: cache...\n";
    Schema::create('cache', function (Blueprint $table) {
        $table->string('key')->primary();
        $table->mediumText('value');
        $table->integer('expiration');
    });
}

if (!Schema::hasTable('cache_locks')) {
    echo "Creating table: cache_locks...\n";
    Schema::create('cache_locks', function (Blueprint $table) {
        $table->string('key')->primary();
        $table->string('owner');
        $table->integer('expiration');
    });
}

echo "--- Harmonization of kemenpu2 finished successfully! ---\n";
