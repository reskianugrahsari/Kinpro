# KinPro v2.0 — Task Tracking & Schedule (Master Checklist)

Target Timeline: 16 Oktober 2026 — 24 Desember 2026 (10 Minggu)  
Status Legend: `[ ]` Pending | `[/]` In Progress | `[x]` Completed | `[!]` Cancelled

---

## Fase 1: Foundation & Setup (Minggu 1-2: 16 Okt 2026 - 29 Okt 2026)

| ID | Task Item | Target Tanggal | Status | Keterangan / Deliverable |
|----|-----------|----------------|--------|--------------------------|
| F1.1 | Inisialisasi Project Laravel & Git Repo | 16 Okt 2026 | `[x]` | Setup core Laravel 11/12 & git remote |
| F1.2 | Konfigurasi Database MySQL & `.env` | 17 Okt 2026 | `[x]` | Koneksi database SQLite/MySQL |
| F1.3 | Setup Laravel Breeze Auth & Middleware 2 Role | 18-19 Okt 2026 | `[x]` | Breeze blade + Role middleware (admin/pegawai) |
| F1.4 | Layout Blade + Tailwind CSS v4 | 20-22 Okt 2026 | `[x]` | Sidebar, Header, Solid Blue `#1769AA` |
| F1.5 | Migrasi Skema Database Master & `users` | 23-24 Okt 2026 | `[x]` | Unified users & master tables (unit_kerja, pegawai, pengajuan_absen, reports, report_expenses) |
| F1.6 | Script Migrasi Data Pegawai (3 Sumber) | 25-27 Okt 2026 | `[x]` | Seeder & Model mapping (unit_kerja, master_ppk, pegawai) |
| F1.7 | Verifikasi Auth & Sesi Login | 28-29 Okt 2026 | `[x]` | Unit/Feature test login & multi-role (27 tests passed) |

---

## Fase 2: Modul Inti KinPro (Minggu 3-4: 30 Okt 2026 - 12 Nov 2026)

| ID | Task Item | Target Tanggal | Status | Keterangan / Deliverable |
|----|-----------|----------------|--------|--------------------------|
| F2.1 | Dashboard Admin | 30-31 Okt 2026 | `[x]` | Stat cards, charts predikat, trend |
| F2.2 | Dashboard Pegawai | 1-2 Nov 2026 | `[x]` | Ringkasan pribadi & quick actions |
| F2.3 | Data Pegawai & Unit Kerja / Master PPK | 3-5 Nov 2026 | `[x]` | CRUD Pegawai, Unit Kerja, Master PPK |
| F2.4 | Penilaian Kinerja | 6-8 Nov 2026 | `[x]` | 6 aspek, kalkulasi sum/avg, predikat |
| F2.5 | Modul Izin/Cuti | 9-10 Nov 2026 | `[x]` | Form pengajuan, kuota 4x/bln, approval |
| F2.6 | Modul Pengaduan | 11 Nov 2026 | `[x]` | Kirim aduan, balasan admin, status |
| F2.7 | Rating Pegawai & Profil | 12 Nov 2026 | `[x]` | Ranking, edit profil, password |

---

## Fase 3: Modul Lupa Absen / Don't Forget (Minggu 5: 13 Nov 2026 - 19 Nov 2026)

| ID | Task Item | Target Tanggal | Status | Keterangan / Deliverable |
|----|-----------|----------------|--------|--------------------------|
| F3.1 | Migrasi Tabel & Seeder `pengajuan_absen` | 13 Nov 2026 | `[x]` | Struktur tabel lupa absen |
| F3.2 | Form Pengajuan & Auto-fill Atasan | 14-15 Nov 2026 | `[x]` | Integrasi Alpine.js |
| F3.3 | Live Preview Naskah Dinas A4 | 16 Nov 2026 | `[x]` | Preview surat realtime |
| F3.4 | Cetak Surat (`window.print`) | 17 Nov 2026 | `[x]` | CSS `@media print` |
| F3.5 | Panel Admin Verifikasi Lupa Absen | 18-19 Nov 2026 | `[x]` | Approve/reject & cetak ulang |

---

## Fase 4: Modul Perjalanan Dinas / Pajokka (Minggu 6-8: 20 Nov 2026 - 10 Des 2026)

| ID | Task Item | Target Tanggal | Status | Keterangan / Deliverable |
|----|-----------|----------------|--------|--------------------------|
| F4.1 | Migrasi Tabel Pajokka | 20-21 Nov 2026 | `[x]` | Reports, schedules, expenses, attachments |
| F4.2 | CRUD Master Data Perjalanan Dinas | 22-25 Nov 2026 | `[x]` | PPK, unit kerja, kota, transportasi, kop |
| F4.3 | Wizard Step 1: Surat & OCR Scan | 26-28 Nov 2026 | `[x]` | PaddleOCR, CLAHE, compression |
| F4.4 | Wizard Step 2: Data Perjalanan & Pelaksana | 29-30 Nov 2026 | `[x]` | Multi-select pelaksana & tujuan |
| F4.5 | Wizard Step 3: Jadwal & Biaya | 1-3 Des 2026 | `[x]` | Metode bayar KKP/Cash per item |
| F4.6 | Wizard Step 4: Lampiran & Submit | 4-5 Des 2026 | `[x]` | Upload SPT, tiket, taksi, foto |
| F4.7 | Admin Review & Approval Workflow | 6-7 Des 2026 | `[x]` | Approve, revision, reject, delete |
| F4.8 | Ekspor Dokumen PDF | 8-9 Des 2026 | `[x]` | DomPDF, font Arial, TTD PPK |
| F4.9 | Ekspor Dokumen DOCX | 10 Des 2026 | `[x]` | PHPWord, kop surat, grid foto |

---

## Fase 5: Polish, Rekap & Deployment (Minggu 9-10: 11 Des 2026 - 24 Des 2026)

| ID | Task Item | Target Tanggal | Status | Keterangan / Deliverable |
|----|-----------|----------------|--------|--------------------------|
| F5.1 | Rekap & Statistik Gabungan | 11-13 Des 2026 | `[x]` | Rekap lintas modul, export CSV/Excel, summary cards |
| F5.2 | Activity Logging & Audit Trail | 14-15 Des 2026 | `[x]` | ActivityLog model, migration, login/logout & export audit |
| F5.3 | Security Hardening | 16-18 Des 2026 | `[x]` | SecurityHeaders middleware, CSRF, rate limit, mime validation |
| F5.4 | Unit & Feature Testing | 19-20 Des 2026 | `[x]` | 46 tests passing (135 assertions) |
| F5.5 | Responsive UI Audit & Fix | 21-22 Des 2026 | `[x]` | FUT shield cards, responsive grid, mobile sidebar |
| F5.6 | UAT & Bug Fixing | 23 Des 2026 | `[x]` | Verifikasi alur pengguna & tampilan semirip visual asli |
| F5.7 | Deployment Production & Serah Terima | 24 Des 2026 | `[ ]` | Siap deploy produksi |
