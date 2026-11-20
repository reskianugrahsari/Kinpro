# KINPRO

Sistem Informasi Kinerja Pegawai berbasis Laravel. KINPRO menyediakan dashboard Admin dan Pegawai, pengelolaan data pegawai, penilaian kinerja, izin/cuti, pengaduan, lupa absen, rating pegawai, perjalanan dinas, OCR dokumen, export PDF/DOCX, dan rekap statistik.

## 1. Persyaratan Sistem

### Wajib

- PHP 8.3 atau lebih baru
- Composer 2.x
- Node.js 20 LTS atau lebih baru
- npm
- Git
- Browser modern: Chrome, Edge, atau Firefox

### Database

Konfigurasi default memakai SQLite sehingga MySQL tidak wajib.

- SQLite: tersedia melalui PHP extension `pdo_sqlite`
- MySQL/MariaDB: alternatif untuk deployment
- Extension PHP `pdo_mysql`: wajib jika memakai MySQL/MariaDB

### Extension PHP

Pastikan extension berikut aktif:

```text
ctype
curl
dom
fileinfo
mbstring
openssl
pdo
pdo_sqlite
session
tokenizer
xml
zip
```

Untuk MySQL, aktifkan juga:

```text
pdo_mysql
```

Periksa instalasi:

```bash
php -v
php -m
composer -V
node -v
npm -v
```

## 2. Dependency Project

### Dependency PHP

Dependency utama tersedia di `composer.json` dan dipasang dengan Composer:

- `laravel/framework`: framework aplikasi
- `barryvdh/laravel-dompdf`: export laporan perjalanan dinas ke PDF
- `intervention/image`: pemrosesan dan optimasi gambar upload
- `phpoffice/phpword`: export laporan perjalanan dinas ke DOCX
- `laravel/breeze`: autentikasi Laravel
- `laravel/tinker`: command line Laravel
- `laravel/pint`: format kode PHP
- `phpunit/phpunit`: pengujian aplikasi

### Dependency Frontend

Dependency tersedia di `package.json` dan dipasang dengan npm:

- Vite dan `laravel-vite-plugin`: build asset Laravel
- Tailwind CSS: styling antarmuka
- Alpine.js: interaksi halaman
- PostCSS dan Autoprefixer: pemrosesan CSS
- Tailwind Forms: styling form

## 3. Instalasi Project

### 3.1 Clone atau buka project

```bash
git clone <URL_REPOSITORY>
cd Kinpro
```

Jika project sudah tersedia, langsung masuk ke folder project.

### 3.2 Install dependency PHP

```bash
composer install
```

### 3.3 Buat file environment

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Linux/macOS:

```bash
cp .env.example .env
```

### 3.4 Generate application key

```bash
php artisan key:generate
```

### 3.5 Siapkan database SQLite

Jika memakai konfigurasi default SQLite, buat file database:

Windows PowerShell:

```powershell
New-Item -ItemType File -Path database/database.sqlite -Force
```

Linux/macOS:

```bash
touch database/database.sqlite
```

Pastikan `.env` berisi:

```env
DB_CONNECTION=sqlite
```

### 3.6 Jalankan migration dan seeder

```bash
php artisan migrate --seed
```

Perintah tersebut membuat tabel dan data awal, termasuk akun Admin, akun Pegawai, unit kerja, dan Master PPK.

### 3.7 Install dependency frontend

```bash
npm install
```

Build asset untuk penggunaan normal:

```bash
npm run build
```

### 3.8 Siapkan storage upload

```bash
php artisan storage:link
```

Perintah ini diperlukan untuk menampilkan file upload, seperti bukti lupa absen, dokumen izin, lampiran perjalanan dinas, dan foto.

### 3.9 Jalankan aplikasi

```bash
php artisan serve
```

Buka alamat berikut pada browser:

```text
http://127.0.0.1:8000
```

## 4. Menjalankan Mode Development

Terminal pertama:

```bash
php artisan serve
```

Terminal kedua:

```bash
npm run dev
```

`npm run dev` digunakan saat mengembangkan CSS dan JavaScript agar perubahan asset dapat dimuat otomatis.

## 5. Konfigurasi Environment

Konfigurasi minimum `.env`:

```env
APP_NAME=KINPRO
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite

FILESYSTEM_DISK=local
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
MAIL_MAILER=log
```

Untuk email sungguhan, ubah konfigurasi `MAIL_*` sesuai SMTP yang digunakan. Konfigurasi default `MAIL_MAILER=log` hanya mencatat email ke log aplikasi.

## 6. Akun Default

Ganti password default setelah login pada lingkungan selain development.

## 7. Fitur Admin

Admin memiliki akses untuk:

- Melihat dashboard statistik keseluruhan
- Mengelola Unit Kerja
- Mengelola Master PPK
- Menambah, mengubah, dan menghapus data Pegawai
- Membuat dan mengelola Penilaian Kinerja
- Melihat rekap penilaian
- Memproses pengajuan Izin/Cuti
- Memproses Pengaduan
- Memproses pengajuan Lupa Absen
- Melihat rekap lupa absen bulanan
- Melihat dan mengelola Rating Pegawai
- Memeriksa, menyetujui, meminta revisi, atau menolak laporan Perjalanan Dinas
- Mengunduh laporan Perjalanan Dinas dalam PDF dan DOCX
- Melihat dan mengekspor Rekap Statistik dalam CSV

## 8. Fitur Pegawai

Pegawai memiliki akses untuk:

- Melihat dashboard pribadi
- Mengubah profil dan password
- Melihat direktori pegawai
- Melihat penilaian kinerja miliknya
- Mengajukan Izin/Cuti
- Membuat Pengaduan
- Mengajukan Lupa Absen
- Membuat laporan Perjalanan Dinas
- Mengunggah dokumen dan bukti pendukung
- Menggunakan OCR pada dokumen perjalanan dinas
- Memantau status laporan dan memperbaiki laporan yang diminta revisi
- Mengunduh laporan perjalanan dinas
- Memberikan Rating Pegawai

## 9. Fitur Perjalanan Dinas

Modul Perjalanan Dinas berada pada menu `Perjalanan Dinas`.

Pegawai dapat mengisi:

- PPK
- Nomor surat tugas
- Tanggal surat tugas
- Tanggal mulai dan selesai perjalanan
- Tujuan perjalanan
- Maksud perjalanan
- Daftar peserta
- Jadwal kegiatan
- Rincian biaya
- Surat tugas dan lampiran
- Tiket dan bukti transportasi
- Dokumentasi perjalanan

Status laporan:

```text
submitted
revision_required
approved
rejected
```

Admin dapat memeriksa laporan dan memilih tindakan `Approve`, `Request Revision`, atau `Reject`.

### Export PDF dan DOCX

Export PDF memakai `barryvdh/laravel-dompdf`.

Export DOCX memakai `phpoffice/phpword`.

Keduanya sudah tercantum dalam `composer.json` dan ikut terpasang saat menjalankan:

```bash
composer install
```

### OCR perjalanan dinas

OCR dijalankan oleh script:

```text
app/Services/ocr_extractor.py
```

OCR digunakan untuk membaca data dari gambar atau dokumen surat perjalanan dinas. Script memakai import berikut secara dinamis:

- EasyOCR
- OpenCV
- NumPy
- Pillow
- PaddleOCR/PaddlePaddle pada jalur OCR Paddle

Install Python 3.11 atau versi yang kompatibel, lalu buat virtual environment:

Windows PowerShell:

```powershell
py -3.11 -m venv .venv-ocr
.\.venv-ocr\Scripts\Activate.ps1
python -m pip install --upgrade pip
pip install easyocr opencv-python numpy Pillow paddleocr paddlepaddle
```

Linux/macOS:

```bash
python3.11 -m venv .venv-ocr
source .venv-ocr/bin/activate
python -m pip install --upgrade pip
pip install easyocr opencv-python numpy Pillow paddleocr paddlepaddle
```

Uji dependency OCR:

```bash
python -c "import easyocr, cv2, numpy, PIL; print('OCR dependency OK')"
```

Catatan penting: controller saat ini memiliki path Python Windows yang spesifik pada komputer pengembang. Pada komputer lain, pastikan konfigurasi interpreter diarahkan ke Python yang berisi dependency OCR. Jika OCR belum tersedia, data perjalanan masih dapat diisi secara manual, tetapi pemindaian OCR tidak dapat digunakan.

File OCR dengan resolusi terlalu rendah dapat ditolak. Gunakan dokumen atau gambar yang jelas dan minimal berukuran sekitar 500 x 500 piksel untuk gambar.

## 10. Fitur Pengajuan Lupa Absen

Modul Lupa Absen berada pada menu `Lupa Absen`.

Pegawai dapat mengisi:

- Jenis lupa absen
- Tanggal absensi
- Alasan
- Supervisor atau atasan
- Kota
- Bukti pendukung

Format bukti yang diterima:

```text
PDF, JPG, JPEG, PNG
```

Ukuran maksimum bukti lupa absen adalah 2 MB.

Alur penggunaan:

1. Pegawai membuka menu `Lupa Absen`.
2. Pegawai memilih `Tambah Pengajuan`.
3. Pegawai mengisi seluruh data.
4. Pegawai mengunggah bukti jika tersedia.
5. Pegawai mengirim pengajuan.
6. Sistem menyimpan pengajuan dengan status `pending`.
7. Admin memeriksa pengajuan.
8. Admin memilih `approved` atau `rejected` dan dapat menambahkan catatan.
9. Pegawai melihat hasil pemeriksaan pada daftar pengajuan.

Admin dapat membuka rekap bulanan dan memfilter berdasarkan bulan, tahun, jenis absen, atau pegawai.

Modul ini tidak membutuhkan dependency eksternal tambahan selain dependency Laravel dan PHP extension standar.

## 11. Format Upload Utama

| Fitur | Format | Batas ukuran |
|---|---|---|
| Lupa Absen | PDF, JPG, JPEG, PNG | 2 MB |
| Izin/Cuti | PDF, JPG, JPEG, PNG | 2 MB |
| Pengaduan | PDF, JPG, JPEG, PNG | 2 MB |
| Foto Pegawai | JPG, JPEG, PNG, WEBP | 3 MB |
| Foto Profil | Format gambar | 5 MB |
| OCR Perjalanan Dinas | JPG, JPEG, PNG, PDF, DOC, DOCX | Mengikuti validasi form | 

## 12. Perintah Artisan Berguna

```bash
php artisan migrate
php artisan migrate --seed
php artisan migrate:fresh --seed
php artisan route:list
php artisan storage:link
php artisan optimize:clear
php artisan config:clear
php artisan view:clear
php artisan test
```

`migrate:fresh --seed` menghapus seluruh tabel lalu membuat ulang database. Jangan gunakan pada database produksi tanpa backup.

## 13. Troubleshooting

### Composer tidak ditemukan

Install Composer, lalu buka terminal baru dan jalankan:

```bash
composer -V
```

### PHP version tidak sesuai

Project membutuhkan PHP 8.3 atau lebih baru:

```bash
php -v
```

### Error database SQLite

Pastikan file berikut tersedia:

```text
database/database.sqlite
```

Pastikan extension `pdo_sqlite` aktif:

```bash
php -m
```

### CSS atau JavaScript tidak tampil

Jalankan:

```bash
npm install
npm run build
```

### File upload tidak tampil

Jalankan:

```bash
php artisan storage:link
```

### OCR gagal

Periksa hal berikut:

1. Python tersedia dan dapat dipanggil dari terminal.
2. Virtual environment OCR aktif.
3. `easyocr`, `opencv-python`, `numpy`, `Pillow`, `paddleocr`, dan `paddlepaddle` sudah terpasang.
4. Path interpreter Python pada controller sesuai dengan komputer yang digunakan.
5. File yang diunggah memiliki kualitas dan resolusi cukup.

### Export PDF gagal

Jalankan ulang dependency PHP:

```bash
composer install
php artisan optimize:clear
```

### Export DOCX gagal

Pastikan package PHPWord tersedia:

```bash
composer show phpoffice/phpword
```

Jika belum tersedia:

```bash
composer install
```

## 14. Struktur Project Penting

```text
app/Http/Controllers/       Controller aplikasi
app/Http/Requests/          Validasi request
app/Models/                 Model Eloquent
app/Services/               Service PDF, DOCX, dan OCR
app/Services/ocr_extractor.py Script OCR Python
database/migrations/        Struktur database
database/seeders/           Data awal
resources/views/            Template Blade
resources/css/              Asset CSS
resources/js/               Asset JavaScript
routes/web.php              Route aplikasi
routes/auth.php             Route autentikasi
storage/app/public/         File upload publik
public/                     File publik dan hasil storage link
composer.json               Dependency PHP
package.json                Dependency frontend
.env.example                Contoh konfigurasi environment
```

## 15. Pengujian Setelah Instalasi

1. Jalankan aplikasi dengan `php artisan serve`.
2. Login sebagai Admin.
3. Pastikan dashboard admin tampil.
4. Login sebagai Pegawai.
5. Buat pengajuan Lupa Absen dengan bukti PDF atau gambar.
6. Login kembali sebagai Admin dan proses pengajuan tersebut.
7. Buat laporan Perjalanan Dinas sebagai Pegawai.
8. Uji input manual laporan.
9. Uji OCR jika dependency Python sudah terpasang.
10. Uji export PDF dan DOCX.
11. Pastikan file upload dapat dibuka.
12. Jalankan test dengan `php artisan test`.

## 16. Lisensi

Project digunakan sebagai aplikasi internal KINPRO. Sesuaikan informasi lisensi dan pemilik repository sebelum distribusi publik.
