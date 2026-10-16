# PRD — KinPro v2.0: Sistem Informasi Kinerja Pegawai + Lupa Absen + Perjalanan Dinas

## Dokumen Versi
| Field            | Value                                  |
|------------------|----------------------------------------|
| Nama Produk      | KinPro — Kinerja Profesional v2.0      |
| Tanggal          | 16 Oktober 2026                        |
| Status           | Draft                                  |
| Pemilik Produk   | Reski Anugrah Sari                     |
| Konteks Instansi | Kementerian PU — BPSDM / Balai PK Wilayah VIII Makassar |

---

## 1. Ringkasan Eksekutif

KinPro v2.0 adalah migrasi KinPro v1 (vanilla PHP) ke Laravel modern, **ditambah 2 fitur baru** dari project terpisah:

| Asal Project     | Masuk Sebagai Fitur   | Posisi di Sidebar        |
|------------------|-----------------------|--------------------------|
| **KinPro v1**    | Fitur inti (existing) | Dashboard, Pegawai, Penilaian, Izin, Pengaduan, Rating, Profil |
| **Don't Forget** | **Lupa Absen**        | Menu sidebar: "Lupa Absen" |
| **Pajokka**      | **Perjalanan Dinas**  | Menu sidebar: "Perjalanan Dinas" |

Semua fitur hidup di satu codebase, satu database, satu login, satu sidebar.

---

## 2. Latar Belakang & Tujuan

### Masalah
1. KinPro v1 masih vanilla PHP (tidak scalable, sulit maintain)
2. Fitur lupa absen dan perjalanan dinas hidup di project terpisah — pegawai harus buka 3 URL berbeda
3. Data pegawai terduplikasi di 3 database

### Tujuan
- Migrasi KinPro v1 ke Laravel
- Menambahkan fitur **Lupa Absen** (dari Don't Forget) ke sidebar KinPro
- Menambahkan fitur **Perjalanan Dinas** (dari Pajokka) ke sidebar KinPro
- Satu login, satu database, satu portal

---

## 3. Pengguna & Roles

2 role (tidak berubah dari KinPro v1):

| Role       | Akses                                               |
|------------|------------------------------------------------------|
| **Admin**  | Semua fitur. CRUD pegawai, penilaian, kelola izin, kelola pengaduan, kelola lupa absen, review perjalanan dinas, master data, rating |
| **Pegawai**| Dashboard pribadi, lihat penilaian sendiri, ajukan izin, ajukan aduan, ajukan lupa absen, buat laporan perjalanan dinas, lihat rating |

---

## 4. Stack Teknologi

| Layer          | Teknologi                                    |
|----------------|----------------------------------------------|
| Framework      | Laravel 11/12 (PHP 8.2+)                     |
| Frontend       | Blade + Tailwind CSS v4 + Alpine.js          |
| Database       | MySQL 8.0+                  |
| Autentikasi    | Laravel Breeze (session-based, 2 role)        |
| UI Approach    | Custom Blade+Tailwind (bukan Filament)        |
| PDF Export     | DomPDF (barryvdh/laravel-dompdf)              |
| DOCX Export    | PHPWord (phpoffice/phpword)                   |
| Image          | Intervention Image v3 (GD)                   |
| OCR            | PaddleOCR via Python script (server-side)     |
| Font Laporan   | Arial (semua output PDF/DOCX)                 |
| Theme          | Solid Blue (#1769AA / #0F2747) & White, tanpa gradient |
| Input Style    | rounded-xl                                    |

---

## 5. Navigasi Sidebar

### Sidebar Pegawai

```
📊 Dashboard
📋 Penilaian Saya
📅 Pengajuan Izin
📢 Aduan Saya
⭐ Rating Pegawai
── ── ── ── ── ── ──
🖊️ Lupa Absen              ← dari Don't Forget
✈️ Perjalanan Dinas          ← dari Pajokka
   ├── Daftar Laporan
   └── Buat Laporan Baru
── ── ── ── ── ── ──
👤 Profil
```

### Sidebar Admin

```
📊 Dashboard
👥 Data Pegawai
📋 Penilaian Kinerja
📅 Kelola Izin
📢 Kelola Pengaduan
⭐ Rating Pegawai
── ── ── ── ── ── ──
🖊️ Kelola Lupa Absen        ← dari Don't Forget
✈️ Perjalanan Dinas          ← dari Pajokka
   ├── Daftar Laporan
   └── Review Laporan
── ── ── ── ── ── ──
⚙️ Master Data
   ├── Unit Kerja
   ├── PPK
   ├── Kota
   ├── Transportasi
   ├── Proses Bisnis
   └── Kop Surat
📊 Rekap & Statistik
👤 Profil
```

---

## 6. Fitur Inti KinPro (Migrasi dari v1)

Semua fitur existing di-migrasi dari vanilla PHP ke Laravel tanpa perubahan fungsional.

### 6.1 Dashboard

**Admin:**
- Stat cards: total pegawai, total penilaian, rata-rata nilai, pengajuan pending (izin + lupa absen + laporan dinas)
- Donut chart distribusi predikat (Istimewa/Sangat Baik/Baik/Cukup/Kurang)
- Bar chart trend bulanan (6 bulan terakhir)
- Radar aspek penilaian (6 aspek)
- Top 5 performers
- 5 penilaian terbaru
- Top 10 pegawai paling sering izin
- Distribusi jabatan
- Jam realtime WITA

**Pegawai:**
- Ringkasan kinerja (rata-rata + total penilaian)
- Ringkasan izin (total, pending, disetujui)
- Detail penilaian terakhir (6 aspek)
- 5 izin terbaru
- Quick actions: Ajukan Izin, Lupa Absen, Buat Laporan Dinas

### 6.2 Data Pegawai (Admin Only)

- List pegawai: grid kartu visual (gaya FIFA Ultimate Team) + mode tabel
- CRUD: NIP, nama, jabatan, pangkat/gol, bagian, unit kerja, atasan, email, phone, foto
- Search by nama/NIP, filter unit kerja/jabatan
- Hierarki atasan (self-referencing dropdown)
- Soft delete (is_active = false)

### 6.3 Penilaian Kinerja

**6 aspek penilaian (0-100 per aspek):**
- Kedisiplinan, Kinerja, Sikap, Kepemimpinan, Loyalitas, IT

**Kalkulasi otomatis:**
- total_nilai = sum(6 aspek)
- rata_rata = avg(6 aspek)

**Predikat:**
| Rata-rata | Predikat    |
|-----------|-------------|
| ≥ 90      | Sangat Baik |
| ≥ 80      | Baik        |
| ≥ 70      | Cukup       |
| < 70      | Kurang      |

**Admin:** CRUD penilaian, filter bulan/tahun/NIP, statistik ringkasan
**Pegawai:** Read-only riwayat penilaian sendiri, grafik progress

### 6.4 Izin/Cuti

**Jenis izin:** Cuti Tahunan, Sakit, Izin Khusus, Dinas Luar
**Kuota:** Maks 4x pengajuan per bulan per pegawai

**Pegawai:** Form pengajuan (jenis, tanggal, alasan, lampiran) + riwayat sendiri
**Admin:** List semua, approve/reject + catatan, statistik izin

### 6.5 Pengaduan

**Pegawai:** Kirim aduan (subjek, isi, lampiran) + lihat riwayat & balasan
**Admin:** List semua aduan, proses (approve/reject + balasan)

### 6.6 Rating Pegawai

- Ranking pegawai berdasarkan avg penilaian
- Badge predikat visual
- Admin: beri rating. Pegawai: lihat ranking

### 6.7 Profil

- Lihat & edit profil (nama, phone, foto, tanda tangan digital)
- Ganti password (validasi password lama)
- Upload foto (maks 5MB, validasi tipe+MIME)

---

## 7. Fitur Baru: Lupa Absen (dari Don't Forget)

Menu sidebar: **"Lupa Absen"** (pegawai) / **"Kelola Lupa Absen"** (admin)

### 7.1 Pegawai — Form Pengajuan Lupa Absen

| Komponen               | Deskripsi                                             |
|------------------------|-------------------------------------------------------|
| Pilih Pegawai          | Dropdown nama pegawai (auto-fill dari session user)    |
| Auto-fill Atasan       | Alpine.js: pilih pegawai → otomatis isi nama & data atasan |
| Jenis Absen            | Pilihan: Lupa Absen Masuk / Lupa Absen Pulang / Keduanya |
| Alasan                 | Textarea (maks 1000 karakter)                          |
| Tanggal Lupa           | Date picker                                            |
| Tanggal Pengajuan      | Date picker (default: hari ini)                        |
| Kota Surat             | Input text (default: Makassar)                         |
| Nomor Surat            | Auto-generate atau manual                              |
| Live Preview Surat     | Preview naskah dinas format A4 realtime (sync dari form via Alpine.js) |
| Cetak Surat            | Browser Native Print (`window.print()`) + CSS `@media print` |
| Riwayat Pengajuan      | List pengajuan sendiri dengan status                   |

### 7.2 Admin — Kelola Lupa Absen

| Komponen               | Deskripsi                                             |
|------------------------|-------------------------------------------------------|
| List Semua Pengajuan   | Tabel semua pengajuan, filter status/tanggal           |
| Verifikasi             | Ubah status: pending → disetujui / ditolak             |
| Cetak Ulang            | Admin bisa cetak ulang surat dari tabel                |
| Rekap Bulanan          | Rekap jumlah pengajuan per bulan                       |

### 7.3 Tabel Database: `pengajuan_absen`

| Kolom              | Tipe           | Keterangan                              |
|--------------------|----------------|-----------------------------------------|
| id                 | bigint PK      |                                         |
| nomor_surat        | varchar(100)   | Nomor surat dinas                       |
| user_id            | FK users       | Pegawai pemohon                         |
| atasan_id          | FK users       | Atasan yang menyetujui                  |
| jenis_absen        | varchar(100)   | masuk / pulang / keduanya               |
| alasan             | text           |                                         |
| tanggal_lupa       | date           | Tanggal lupa fingerprint                |
| tanggal_pengajuan  | date           |                                         |
| kota_surat         | varchar(50)    | Default: Makassar                       |
| status             | enum           | 'pending','disetujui','ditolak'         |
| timestamps         |                |                                         |

---

## 8. Fitur Baru: Perjalanan Dinas (dari Pajokka)

Menu sidebar: **"Perjalanan Dinas"** (pegawai & admin)

### 8.1 Form Pembuatan Laporan — 4-Step Wizard

#### Step 1 — Scan & Identifikasi Surat
| Komponen                  | Deskripsi                                                |
|---------------------------|----------------------------------------------------------|
| Upload Surat Tugas        | Upload gambar/PDF surat tugas                            |
| OCR Scan                  | PaddleOCR + CLAHE preprocessing via Python script        |
| Raw OCR Text              | Tampilkan full text OCR mentah dengan cursor highlight text mapping |
| Auto-fill Field           | Ekstrak: nomor surat, tanggal, kota tujuan, periode dinas|
| Tanggal SPPD              | Default: tanggal selesai dinas + 2 hari (H+2)           |
| Client-side Compression   | Otomatis compress gambar > 2MB sebelum upload            |
| Low Resolution Warning    | Tolak gambar < 500px atau < 15KB                         |

#### Step 2 — Data Perjalanan
| Komponen            | Deskripsi                                         |
|----------------------|----------------------------------------------------|
| PPK                  | Dropdown dari master_ppk (aktif)                   |
| Pelaksana            | Multi-select pegawai dari users aktif              |
| Kota Tujuan          | Input / dropdown dari cities                       |
| Periode Dinas        | Tanggal mulai & selesai                            |
| Hal/Perihal          | Text input                                         |
| Opsi Tiket           | KKP / Cash                                         |
| Business Process     | Multi-select dari business_processes               |

#### Step 3 — Jadwal & Biaya
| Komponen            | Deskripsi                                         |
|----------------------|----------------------------------------------------|
| Jadwal Kegiatan      | Dynamic rows: tanggal, uraian kegiatan, keterangan |
| Rincian Biaya        | Dynamic rows: komponen, nominal, **metode bayar per item** (KKP/Cash) |
| Pointer Substansi    | Textarea narasi substansi kegiatan                 |
| Subtotal Otomatis    | Subtotal KKP + Subtotal Cash + Grand Total         |

#### Step 4 — Lampiran & Submit
| Komponen            | Deskripsi                                         |
|----------------------|----------------------------------------------------|
| Upload SPT           | Single file                                        |
| Upload Tiket         | Tiket pergi & pulang                               |
| Upload Taksi         | 4 kategori (rumah↔bandara, bandara↔hotel)          |
| Upload Dokumentasi   | Multi-file, maks 8 foto                            |
| Image Optimization   | Auto resize > 1600px, compress quality 75%         |
| Submit               | Status → 'submitted'                               |

### 8.2 Review & Approval (Admin)

| Aksi              | Deskripsi                                            |
|--------------------|------------------------------------------------------|
| List Laporan       | Semua laporan, search nomor surat/kota/nama, filter status |
| Detail             | View lengkap: data surat, jadwal, biaya, lampiran, revisi |
| Approve            | Status → 'approved', catat approved_by & timestamp   |
| Request Revision   | Status → 'revision_required', buat record revisi + catatan |
| Reject             | Status → 'rejected'                                  |
| Delete             | Hapus laporan + semua file lampiran                   |

### 8.3 Ekspor Laporan

| Format   | Detail                                                              |
|----------|---------------------------------------------------------------------|
| **PDF**  | DomPDF: kop surat, metadata, tabel jadwal, tabel biaya (KKP/Cash per item), subtotal KKP + Cash + Grand Total, TTD PPK + pelaksana, lampiran gambar. Font: Arial |
| **DOCX** | PHPWord: sama dengan PDF dalam format Word. Kop surat (logo atau teks fallback), metadata, tabel jadwal 3 kolom, tabel biaya 4 kolom (No, Komponen, Metode Bayar, Nominal), subtotal, TTD, lampiran bukti fisik (grid 2 kolom), dokumentasi foto (maks 8). Font: Arial |

### 8.4 Tabel Database: Perjalanan Dinas

**`reports`**

| Kolom              | Tipe           | Keterangan                              |
|--------------------|----------------|-----------------------------------------|
| id                 | bigint PK      |                                         |
| user_id            | FK users       | Pembuat laporan                         |
| ppk_id             | FK master_ppk  | Pejabat Pembuat Komitmen                |
| nomor_surat_tugas  | varchar(100)   |                                         |
| tanggal_surat_tugas| date nullable  |                                         |
| tgl_mulai_dinas    | date           |                                         |
| tgl_selesai_dinas  | date           |                                         |
| kota_tujuan        | varchar(100)   |                                         |
| daftar_pelaksana   | json           | Array of {name, nip}                    |
| isi_pointer        | text nullable  | Hal/Perihal                             |
| pointer_substansi  | text nullable  | Narasi substansi kegiatan               |
| tanggal_sppd       | date nullable  | Default: tgl_selesai + 2 hari           |
| opsi_tiket         | varchar(10)    | 'kkp' / 'cash'                          |
| status             | enum           | draft, submitted, revision_required, approved, rejected, completed |
| submitted_at       | timestamp null |                                         |
| reviewed_by        | FK users null  |                                         |
| reviewed_at        | timestamp null |                                         |
| approved_by        | FK users null  |                                         |
| approved_at        | timestamp null |                                         |
| rejected_at        | timestamp null |                                         |
| completed_at       | timestamp null |                                         |
| total_cost         | decimal(15,2)  | Kalkulasi otomatis dari expenses        |
| revision_count     | int default 0  |                                         |
| completeness_score | int default 0  |                                         |
| timestamps         |                |                                         |

**`report_schedules`** — Jadwal kegiatan harian

| Kolom              | Tipe          |
|--------------------|---------------|
| id                 | bigint PK     |
| report_id          | FK reports    |
| tanggal            | date          |
| uraian_kegiatan    | text          |
| lokasi_keterangan  | text nullable |
| timestamps         |               |

**`report_expenses`** — Rincian biaya (metode bayar per item)

| Kolom              | Tipe           |
|--------------------|----------------|
| id                 | bigint PK      |
| report_id          | FK reports     |
| expense_type       | varchar(50)    |
| amount             | decimal(15,2)  |
| payment_method     | varchar(10)    | ← 'kkp' / 'cash' per item |
| transaction_date   | date nullable  |
| description        | text nullable  |
| timestamps         |                |

**`report_attachments`** — Lampiran bukti

| Kolom              | Tipe           |
|--------------------|----------------|
| id                 | bigint PK      |
| report_id          | FK reports     |
| category           | varchar(50)    | spt, tiket_pergi, tiket_pulang, taksi_*, dokumentasi |
| file_path          | varchar        |
| sort_order         | int nullable   | Untuk dokumentasi (maks 8) |
| uploaded_by        | FK users       |
| timestamps         |                |

**`report_revisions`** — Riwayat revisi

| Kolom              | Tipe              |
|--------------------|--------------------|
| id                 | bigint PK          |
| report_id          | FK reports         |
| requested_by       | FK users           |
| section            | varchar(50)        |
| notes              | text               |
| created_at         | timestamp          |
| resolved_at        | timestamp nullable |

### 8.5 Tabel Master Data Pendukung (Admin CRUD)

**`master_ppk`** — Pejabat Pembuat Komitmen

| Kolom     | Tipe      |
|-----------|-----------|
| id        | bigint PK |
| nama      | varchar   |
| nip       | varchar   |
| jabatan   | varchar   |
| is_active | boolean   |
| timestamps|           |

**`unit_kerja`**

| Kolom       | Tipe      |
|-------------|-----------|
| id          | bigint PK |
| kode_unit   | varchar   |
| nama_unit   | varchar   |
| kepala_unit | varchar   |
| is_active   | boolean   |
| timestamps  |           |

**`cities`** — Kota tujuan dinas

| Kolom     | Tipe      |
|-----------|-----------|
| id        | bigint PK |
| nama_kota | varchar   |
| provinsi  | varchar   |
| timestamps|           |

**`transportation_types`**

| Kolom     | Tipe      |
|-----------|-----------|
| id        | bigint PK |
| nama      | varchar   |
| is_active | boolean   |
| timestamps|           |

**`business_processes`** — Proses bisnis (hierarki)

| Kolom       | Tipe          |
|-------------|---------------|
| id          | bigint PK     |
| tahun       | int           |
| kategori    | varchar       |
| kode        | varchar       |
| nama_proses | varchar       |
| parent_id   | FK self null  |
| is_active   | boolean       |
| timestamps  |               |

**`report_business_processes`** — Pivot

| report_id | business_process_id |

**`kop_surat`** — Template kop surat instansi

| Kolom            | Tipe    |
|------------------|---------|
| id               | bigint  |
| logo_path        | varchar |
| nama_kementerian | varchar |
| nama_badan       | varchar |
| nama_unit_kerja  | varchar |
| alamat           | varchar |
| telepon          | varchar |
| email            | varchar |
| website          | varchar |
| timestamps       |         |

---

## 9. Database — Tabel Inti KinPro

### 9.1 Tabel `users` (Unified)

Menggabungkan data pegawai dari 3 sumber menjadi 1 tabel auth + profile:

| Kolom              | Tipe           | Keterangan                        |
|--------------------|----------------|-----------------------------------|
| id                 | bigint PK      |                                   |
| nip                | varchar(50) UQ | NIP 18 digit                      |
| name               | varchar(150)   | Nama lengkap                      |
| email              | varchar UQ     | Login email                       |
| username           | varchar UQ null| Login username (dari KinPro v1)   |
| password           | varchar        | Bcrypt                            |
| role               | enum           | 'pegawai', 'admin'               |
| jabatan            | varchar(100)   |                                   |
| pangkat_golongan   | varchar(50)    |                                   |
| bagian             | varchar(100)   |                                   |
| unit_kerja_id      | FK nullable    |                                   |
| atasan_id          | FK self null   | Atasan langsung (dari Don't Forget)|
| phone              | varchar(20)    |                                   |
| photo              | varchar        | Path foto profil                  |
| signature_path     | varchar        | Tanda tangan digital              |
| is_active          | boolean        |                                   |
| email_verified_at  | timestamp      |                                   |
| remember_token     | varchar        |                                   |
| timestamps         |                |                                   |

### 9.2 Tabel `penilaian`

| Kolom              | Tipe             | Keterangan                       |
|--------------------|------------------|----------------------------------|
| id                 | bigint PK        |                                  |
| user_id            | FK users         | Pegawai yang dinilai             |
| bulan              | varchar(20)      | Januari-Desember                 |
| tahun              | year             |                                  |
| nilai_kedisiplinan | tinyint 0-100    |                                  |
| kinerja            | tinyint 0-100    |                                  |
| sikap              | tinyint 0-100    |                                  |
| kepemimpinan       | tinyint 0-100    |                                  |
| loyalitas          | tinyint 0-100    |                                  |
| it                 | tinyint 0-100    |                                  |
| total_nilai        | int (generated)  | sum 6 aspek                      |
| rata_rata          | decimal (generated)| avg 6 aspek                    |
| masukan_atasan     | text nullable    |                                  |
| penilai_id         | FK users null    | Admin yang menilai               |
| timestamps         |                  |                                  |

### 9.3 Tabel `izin`

| Kolom              | Tipe           |
|--------------------|----------------|
| id                 | bigint PK      |
| user_id            | FK users       |
| jenis_izin         | enum           | cuti_tahunan, sakit, izin_khusus, dinas_luar |
| tanggal_mulai      | date           |
| tanggal_selesai    | date           |
| alasan             | text           |
| lampiran           | varchar null   |
| status             | enum           | pending, approved, rejected |
| tanggal_pengajuan  | date           |
| catatan_admin      | text nullable  |
| processed_by       | FK users null  |
| processed_at       | timestamp null |
| timestamps         |                |

### 9.4 Tabel `pengaduan`

| Kolom          | Tipe           |
|----------------|----------------|
| id             | bigint PK      |
| user_id        | FK users       |
| subjek         | varchar(200)   |
| isi            | text           |
| lampiran       | varchar null   |
| status         | enum           | pending, diproses, selesai, ditolak |
| balasan_admin  | text nullable  |
| processed_by   | FK users null  |
| timestamps     |                |

### 9.5 Tabel `activity_logs`

| Kolom      | Tipe       |
|------------|------------|
| id         | bigint PK  |
| user_id    | FK users   |
| action     | varchar    |
| model_type | varchar    |
| model_id   | bigint     |
| changes    | json       |
| ip_address | varchar    |
| timestamps |            |

---

## 10. Matriks Akses per Fitur

| Fitur                       | Pegawai              | Admin                   |
|-----------------------------|----------------------|-------------------------|
| Dashboard                   | Ringkasan pribadi    | Statistik keseluruhan   |
| Data Pegawai                | ❌                   | CRUD                    |
| Penilaian Kinerja           | RO (sendiri)         | CRUD                    |
| Pengajuan Izin              | Ajukan + RO sendiri  | Approve/Reject semua    |
| Pengaduan                   | Kirim + RO sendiri   | Proses semua            |
| Rating                      | RO                   | RW                      |
| **Lupa Absen**              | Ajukan + cetak + RO  | Verifikasi + rekap      |
| **Perjalanan Dinas**        | Buat + RO sendiri    | Review/Approve/Reject   |
| **Ekspor PDF/DOCX**         | Sendiri saja         | Semua laporan           |
| Master Data                 | ❌                   | CRUD                    |
| Kop Surat                   | ❌                   | CRUD                    |
| Rekap & Statistik           | ❌                   | RO                      |
| Profil                      | RW sendiri           | RW sendiri              |

---

## 11. Keamanan

| Fitur                | Implementasi                                    |
|----------------------|--------------------------------------------------|
| CSRF Protection      | Laravel @csrf                                    |
| Password Hashing     | bcrypt (Laravel default)                         |
| SQL Injection        | Eloquent ORM (prepared statements otomatis)      |
| Input Validation     | Form Request classes                             |
| Rate Limiting        | Laravel RateLimiter (5x/15 menit login)          |
| Session Timeout      | 2 jam                                            |
| File Upload          | Validasi tipe, ukuran, MIME, .htaccess deny exec |
| Role Middleware       | Custom RoleMiddleware ('pegawai', 'admin')       |
| Activity Log         | Catat aksi CRUD penting                          |

---

## 12. UI/UX Guidelines

| Aspek            | Spesifikasi                                    |
|------------------|------------------------------------------------|
| Warna Primer     | Solid Blue #1769AA                              |
| Warna Dark       | Navy #0F2747                                    |
| Background       | White / Slate Light                             |
| **Gradient**     | **DILARANG** — semua warna solid                |
| Border Radius    | rounded-xl untuk semua input                    |
| Font UI          | System font stack (Inter/sans-serif)            |
| Font Laporan     | Arial (PDF/DOCX)                                |
| Logo             | Tanpa background container, transparan          |
| Login Page       | Light theme, autofill text: dark navy (#0f2747) |
| Responsive       | Mobile-first, sidebar collapse di mobile        |
| Icons            | Heroicons (outline)                             |

---

## 13. Struktur Direktori

```
kinpro/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── DashboardController.php
│   │   │   ├── PegawaiController.php
│   │   │   ├── PenilaianController.php
│   │   │   ├── IzinController.php
│   │   │   ├── PengaduanController.php
│   │   │   ├── RatingController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── PengajuanAbsenController.php   ← Lupa Absen
│   │   │   ├── ReportController.php           ← Perjalanan Dinas
│   │   │   ├── MasterDataController.php
│   │   │   └── RekapController.php
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php
│   │   └── Requests/
│   │       ├── StorePenilaianRequest.php
│   │       ├── StoreIzinRequest.php
│   │       ├── StorePengajuanAbsenRequest.php  ← Lupa Absen
│   │       └── StoreReportRequest.php          ← Perjalanan Dinas
│   ├── Models/
│   │   ├── User.php
│   │   ├── Penilaian.php
│   │   ├── Izin.php
│   │   ├── Pengaduan.php
│   │   ├── PengajuanAbsen.php                  ← Lupa Absen
│   │   ├── Report.php                          ← Perjalanan Dinas
│   │   ├── ReportSchedule.php
│   │   ├── ReportExpense.php
│   │   ├── ReportAttachment.php
│   │   ├── ReportRevision.php
│   │   ├── MasterPpk.php
│   │   ├── UnitKerja.php
│   │   ├── City.php
│   │   ├── TransportationType.php
│   │   ├── BusinessProcess.php
│   │   ├── KopSurat.php
│   │   └── ActivityLog.php
│   └── Services/
│       ├── PdfExportService.php
│       ├── DocxExportService.php
│       └── ocr_extractor.py
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php           # Sidebar + header
│   │   └── guest.blade.php         # Login
│   ├── auth/login.blade.php
│   ├── dashboard.blade.php
│   ├── pegawai/                    # Data Pegawai
│   ├── penilaian/                  # Penilaian Kinerja
│   ├── izin/                       # Izin/Cuti
│   ├── pengaduan/                  # Pengaduan
│   ├── rating/                     # Rating
│   ├── profil/                     # Profil
│   ├── lupa-absen/                 # ← Lupa Absen (Don't Forget)
│   │   ├── form.blade.php          # Form + live preview surat
│   │   ├── index.blade.php         # Riwayat
│   │   └── kelola.blade.php        # Admin kelola
│   ├── perjalanan-dinas/           # ← Perjalanan Dinas (Pajokka)
│   │   ├── index.blade.php         # List laporan
│   │   ├── create.blade.php        # 4-step wizard
│   │   ├── show.blade.php          # Detail laporan
│   │   └── pdf-template.blade.php  # Template PDF
│   ├── master-data/                # CRUD master data
│   └── rekap/                      # Rekap & Statistik
├── routes/web.php
└── storage/app/public/
    ├── attachments/                # Lampiran laporan dinas
    ├── photos/                     # Foto profil
    └── temp_ocr/                   # Temp OCR
```

---

## 14. Routes

```
# Auth
POST   /login
POST   /logout

# Dashboard
GET    /dashboard

# Data Pegawai (Admin)
RESOURCE /pegawai

# Penilaian
GET    /penilaian               # Admin: semua, Pegawai: sendiri
POST   /penilaian               # Admin
PUT    /penilaian/{id}          # Admin
DELETE /penilaian/{id}          # Admin

# Izin
GET    /izin                    # Pegawai: sendiri
POST   /izin                    # Pegawai: ajukan
GET    /kelola-izin             # Admin
PUT    /kelola-izin/{id}/approve
PUT    /kelola-izin/{id}/reject

# Pengaduan
GET    /pengaduan               # Pegawai: sendiri
POST   /pengaduan
GET    /kelola-pengaduan        # Admin
PUT    /kelola-pengaduan/{id}

# Rating
GET    /rating

# Profil
GET    /profil
PUT    /profil
PUT    /profil/password

# ── Lupa Absen (Don't Forget) ──
GET    /lupa-absen              # Pegawai: form + riwayat
POST   /lupa-absen              # Submit pengajuan
GET    /kelola-absen            # Admin: list semua
PUT    /kelola-absen/{id}/approve
PUT    /kelola-absen/{id}/reject

# ── Perjalanan Dinas (Pajokka) ──
GET    /perjalanan-dinas                # List (role-scoped)
GET    /perjalanan-dinas/create         # 4-step wizard
POST   /perjalanan-dinas                # Store
GET    /perjalanan-dinas/{id}           # Detail
POST   /perjalanan-dinas/{id}/approve   # Admin
POST   /perjalanan-dinas/{id}/revision  # Admin
POST   /perjalanan-dinas/{id}/reject    # Admin
DELETE /perjalanan-dinas/{id}
GET    /perjalanan-dinas/{id}/pdf       # Download PDF
GET    /perjalanan-dinas/{id}/docx      # Download DOCX
POST   /perjalanan-dinas/scan-ocr      # OCR endpoint

# Master Data (Admin)
RESOURCE /master/unit-kerja
RESOURCE /master/ppk
RESOURCE /master/kota
RESOURCE /master/transportasi
RESOURCE /master/proses-bisnis
RESOURCE /master/kop-surat

# Rekap
GET    /rekap
GET    /rekap/export/{type}
```

---

## 15. Migrasi Data

### Strategi
1. Buat tabel `users` baru di KinPro v2
2. Import pegawai dari KinPro v1 (`kemenpu2.pegawai`) — NIP sebagai key
3. Merge data dari Pajokka (`pajokka.users`) by NIP — tambah field: pangkat, unit_kerja, phone, signature
4. Merge data dari Don't Forget (`dont_forget.pegawai`) by NIP — tambah field: bagian, atasan_id
5. Prioritas: Pajokka (paling lengkap) > KinPro v1 (auth) > Don't Forget (hierarki)
6. NIP collision: union merge (field null diisi dari sumber lain)

---

## 16. Dependensi

```json
{
  "require": {
    "php": "^8.2",
    "laravel/framework": "^11.0|^12.0",
    "barryvdh/laravel-dompdf": "^3.0",
    "phpoffice/phpword": "^1.3",
    "intervention/image": "^3.0"
  },
  "require-dev": {
    "laravel/breeze": "^2.0"
  }
}
```

---

## 17. Rencana Implementasi

### Fase 1 — Foundation (Minggu 1-2)
- [ ] Init project Laravel, Breeze auth (2 role)
- [ ] Database migration semua tabel
- [ ] Model Eloquent + relasi
- [ ] RoleMiddleware
- [ ] Layout Blade + Tailwind (sidebar, header)
- [ ] Login page (light theme, solid blue, rounded-xl)
- [ ] Migrasi data pegawai dari 3 sumber

### Fase 2 — Fitur Inti KinPro (Minggu 3-4)
- [ ] Dashboard (admin + pegawai)
- [ ] Data Pegawai (kartu FIFA + CRUD)
- [ ] Penilaian Kinerja (CRUD + kalkulasi + predikat)
- [ ] Izin/Cuti (ajukan + approve/reject + kuota)
- [ ] Pengaduan (kirim + proses)
- [ ] Rating Pegawai
- [ ] Profil

### Fase 3 — Fitur Lupa Absen (Minggu 5)
- [ ] Form pengajuan + auto-fill atasan (Alpine.js)
- [ ] Live preview naskah dinas
- [ ] Cetak surat (window.print + @media print)
- [ ] Admin: verifikasi + rekap bulanan

### Fase 4 — Fitur Perjalanan Dinas (Minggu 6-8)
- [ ] Master Data CRUD (PPK, Unit Kerja, Kota, Transportasi, Proses Bisnis, Kop Surat)
- [ ] 4-step wizard pembuatan laporan
- [ ] OCR Scan (PaddleOCR + CLAHE + client-side compression)
- [ ] Ekspor PDF (DomPDF) + DOCX (PHPWord)
- [ ] Review & Approval workflow
- [ ] Revisi workflow
- [ ] Activity log

### Fase 5 — Polish (Minggu 9-10)
- [ ] Rekap & Statistik semua modul
- [ ] Export rekap
- [ ] Seeder data demo
- [ ] Testing (Pest/PHPUnit)
- [ ] Security audit
- [ ] Responsive finalization
- [ ] UAT

---

## 18. Risiko & Mitigasi

| Risiko                              | Mitigasi                                        |
|-------------------------------------|-------------------------------------------------|
| NIP konflik saat merge 3 sumber     | Log konflik, review manual                      |
| OCR Python dependency di production | Fallback: manual input jika OCR gagal            |
| File attachment migrasi Pajokka     | Copy storage/app/public/attachments              |
| Performance data besar              | Index NIP, user_id, status. Eager loading.       |

---

## 19. Definition of Done

- [ ] Semua fitur KinPro v1 berfungsi di Laravel
- [ ] Fitur **Lupa Absen** berfungsi (form + preview + cetak + verifikasi admin)
- [ ] Fitur **Perjalanan Dinas** berfungsi (wizard + OCR + PDF/DOCX + approval)
- [ ] Satu login, satu sidebar, satu database
- [ ] Theme: solid blue + white, tanpa gradient, rounded-xl, logo transparan
- [ ] Export PDF/DOCX format benar (Arial, kop surat, biaya per-item KKP/Cash)
- [ ] Responsive mobile & desktop
- [ ] Keamanan aktif (CSRF, rate limit, session timeout, file validation)
- [ ] Tidak ada data hilang dari migrasi
