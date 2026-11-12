# KinPro v2.0 — Task Tracking & Schedule (Master Checklist)

Target Timeline: 09 Oktober 2026 — 09 November 2026 (4-5 Minggu)  
Status Legend: `[ ]` Pending | `[/]` In Progress | `[x]` Completed | `[!]` Cancelled

---

## Fase 1: Foundation & Setup (Minggu 1: 09 Okt 2026 - 15 Okt 2026)

| ID | Task Item | Target Tanggal | Status | Keterangan / Deliverable |
|----|-----------|----------------|--------|--------------------------|
| F1.1 | Inisialisasi Project Laravel & Git Repo | 09 Okt 2026 | `[x]` | Setup core Laravel 11/12 & git remote |
| F1.2 | Konfigurasi Database MySQL & `.env` | 10 Okt 2026 | `[x]` | Koneksi database SQLite/MySQL |
| F1.3 | Setup Laravel Breeze Auth & Middleware 2 Role | 11 Okt 2026 | `[x]` | Breeze blade + Role middleware (admin/pegawai) |
| F1.4 | Layout Blade + Tailwind CSS v4 | 12 Okt 2026 | `[x]` | Sidebar, Header, Solid Blue `#1769AA` |
| F1.5 | Migrasi Skema Database Master & `users` | 13 Okt 2026 | `[x]` | Unified users & master tables (unit_kerja, pegawai, pengajuan_absen, reports, report_expenses) |
| F1.6 | Script Migrasi Data Pegawai (3 Sumber) | 14 Okt 2026 | `[x]` | Seeder & Model mapping (unit_kerja, master_ppk, pegawai) |
| F1.7 | Verifikasi Auth & Sesi Login | 15 Okt 2026 | `[x]` | Unit/Feature test login & multi-role (27 tests passed) |

---

## Fase 2: Modul Inti KinPro (Minggu 2: 16 Okt 2026 - 23 Okt 2026)

| ID | Task Item | Target Tanggal | Status | Keterangan / Deliverable |
|----|-----------|----------------|--------|--------------------------|
| F2.1 | Dashboard Admin | 16 Okt 2026 | `[x]` | Stat cards, charts predikat, trend |
| F2.2 | Dashboard Pegawai | 17 Okt 2026 | `[x]` | Ringkasan pribadi & quick actions |
| F2.3 | Data Pegawai & Unit Kerja / Master PPK | 18-19 Okt 2026 | `[x]` | CRUD Pegawai, Unit Kerja, Master PPK |
| F2.4 | Penilaian Kinerja | 20-21 Okt 2026 | `[x]` | 6 aspek, kalkulasi sum/avg, predikat |
| F2.5 | Modul Izin/Cuti | 22 Okt 2026 | `[x]` | Form pengajuan, kuota 4x/bln, approval |
| F2.6 | Modul Pengaduan | 23 Okt 2026 | `[x]` | Kirim aduan, balasan admin, status |
| F2.7 | Rating Pegawai & Profil | 23 Okt 2026 | `[x]` | Ranking, edit profil, password |

---

## Fase 3: Modul Lupa Absen / Don't Forget (Minggu 3: 24 Okt 2026 - 27 Okt 2026)

| ID | Task Item | Target Tanggal | Status | Keterangan / Deliverable |
|----|-----------|----------------|--------|--------------------------|
| F3.1 | Migrasi Tabel & Seeder `pengajuan_absen` | 24 Okt 2026 | `[x]` | Struktur tabel lupa absen |
| F3.2 | Form Pengajuan & Auto-fill Atasan | 25 Okt 2026 | `[x]` | Integrasi Alpine.js |
| F3.3 | Live Preview Naskah Dinas A4 | 26 Okt 2026 | `[x]` | Preview surat realtime |
| F3.4 | Cetak Surat (`window.print`) | 26 Okt 2026 | `[x]` | CSS `@media print` |
| F3.5 | Panel Admin Verifikasi Lupa Absen | 27 Okt 2026 | `[x]` | Approve/reject & cetak ulang |

---

## Fase 4: Modul Perjalanan Dinas / Pajokka (Minggu 3-4: 28 Okt 2026 - 04 Nov 2026)

| ID | Task Item | Target Tanggal | Status | Keterangan / Deliverable |
|----|-----------|----------------|--------|--------------------------|
| F4.1 | Migrasi Tabel Pajokka | 28 Okt 2026 | `[x]` | Reports, schedules, expenses, attachments |
| F4.2 | CRUD Master Data Perjalanan Dinas | 29 Okt 2026 | `[x]` | PPK, unit kerja, kota, transportasi, kop |
| F4.3 | Wizard Step 1: Surat & OCR Scan | 30 Okt 2026 | `[x]` | PaddleOCR, CLAHE, compression |
| F4.4 | Wizard Step 2: Data Perjalanan & Pelaksana | 31 Okt 2026 | `[x]` | Multi-select pelaksana & tujuan |
| F4.5 | Wizard Step 3: Jadwal & Biaya | 01 Nov 2026 | `[x]` | Metode bayar KKP/Cash per item |
| F4.6 | Wizard Step 4: Lampiran & Submit | 02 Nov 2026 | `[x]` | Upload SPT, tiket, taksi, foto |
| F4.7 | Admin Review & Approval Workflow | 03 Nov 2026 | `[x]` | Approve, revision, reject, delete |
| F4.8 | Ekspor Dokumen PDF | 04 Nov 2026 | `[x]` | DomPDF, font Arial, TTD PPK |
| F4.9 | Ekspor Dokumen DOCX | 04 Nov 2026 | `[x]` | PHPWord, kop surat, grid foto |

---

## Fase 5: Polish, Rekap & Deployment (Minggu 5: 05 Nov 2026 - 09 Nov 2026)

| ID | Task Item | Target Tanggal | Status | Keterangan / Deliverable |
|----|-----------|----------------|--------|--------------------------|
| F5.1 | Rekap & Statistik Gabungan | 05 Nov 2026 | `[x]` | Rekap lintas modul, export CSV/Excel, summary cards |
| F5.2 | Activity Logging & Audit Trail | 06 Nov 2026 | `[x]` | ActivityLog model, migration, login/logout & export audit |
| F5.3 | Security Hardening | 07 Nov 2026 | `[x]` | SecurityHeaders middleware, CSRF, rate limit, mime validation |
| F5.4 | Unit & Feature Testing | 07-08 Nov 2026 | `[x]` | 46 tests passing (135 assertions) |
| F5.5 | Responsive UI Audit & Fix | 08 Nov 2026 | `[x]` | FUT shield cards, responsive grid, mobile sidebar |
| F5.6 | UAT & Bug Fixing | 09 Nov 2026 | `[x]` | Verifikasi alur pengguna & tampilan semirip visual asli |
| F5.7 | Deployment Production & Serah Terima | 09 Nov 2026 | `[ ]` | Siap deploy produksi |
