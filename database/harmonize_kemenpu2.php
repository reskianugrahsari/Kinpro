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
        $table->unsignedInteger('pegawai_id');
        $table->unsignedInteger('ppk_id')->nullable();
        $table->string('nomor_sppd', 100)->nullable();
        $table->string('maksud_perjalanan', 255);
        $table->string('tujuan', 150);
        $table->date('tanggal_berangkat');
        $table->date('tanggal_kembali');
        $table->integer('lama_hari')->default(1);
        $table->decimal('total_biaya', 15, 2)->default(0);
        $table->string('status', 30)->default('draft');
        $table->text('catatan')->nullable();
        $table->timestamps();
    });
}

// 6. Table report_expenses (Pajokka expenses)
if (!Schema::hasTable('report_expenses')) {
    echo "Creating table: report_expenses...\n";
    Schema::create('report_expenses', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('report_id');
        $table->string('kategori', 50);
        $table->string('keterangan', 255);
        $table->decimal('jumlah', 15, 2)->default(0);
        $table->string('metode_pembayaran', 30)->default('tunai');
        $table->string('bukti_path', 255)->nullable();
        $table->timestamps();
    });
}

// 7. Table pengajuan_absen (Don't Forget)
if (!Schema::hasTable('pengajuan_absen')) {
    echo "Creating table: pengajuan_absen...\n";
    Schema::create('pengajuan_absen', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('pegawai_id');
        $table->date('tanggal_absen');
        $table->string('jenis_absen', 50); // Masuk / Pulang / Keduanya
        $table->time('jam')->nullable();
        $table->text('alasan');
        $table->string('status', 30)->default('pending');
        $table->string('bukti_path', 255)->nullable();
        $table->text('catatan_admin')->nullable();
        $table->timestamps();
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
