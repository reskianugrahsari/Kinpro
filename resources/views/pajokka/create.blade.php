<x-app-layout>
    <div x-data="reportWizard()" class="max-w-5xl mx-auto space-y-6">

        <!-- Wizard Progress Bar Header -->
        <div class="bg-white dark:bg-[#11223A] p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-xl font-bold text-[#0F2747] dark:text-white">Buat Laporan Hasil Perjalanan Dinas</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Isi seluruh tahapan laporan perjalanan dinas dengan cermat.</p>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold text-[#1769AA] dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-3 py-1 rounded-full border border-blue-100 dark:border-blue-800">
                        Langkah <span x-text="step"></span> dari 7
                    </span>
                </div>
            </div>

            <!-- Step Indicators -->
            <div class="grid grid-cols-7 gap-1">
                <template x-for="i in 7" :key="i">
                    <div 
                        class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                        @click="goToStep(i)"
                        :class="step >= i ? 'bg-[#1769AA]' : 'bg-slate-200 dark:bg-slate-700'"
                        :title="`Buka Step ${i}`"
                    ></div>
                </template>
            </div>
            
            <div class="flex justify-between text-[10px] font-semibold text-slate-400 dark:text-slate-500 mt-2">
                <button type="button" @click="goToStep(1)" class="hover:underline" :class="step >= 1 ? 'text-[#1769AA] dark:text-blue-400 font-bold' : ''">1. Info</button>
                <button type="button" @click="goToStep(2)" class="hover:underline" :class="step >= 2 ? 'text-[#1769AA] dark:text-blue-400 font-bold' : ''">2. Kegiatan</button>
                <button type="button" @click="goToStep(3)" class="hover:underline" :class="step >= 3 ? 'text-[#1769AA] dark:text-blue-400 font-bold' : ''">3. Prosbis</button>
                <button type="button" @click="goToStep(4)" class="hover:underline" :class="step >= 4 ? 'text-[#1769AA] dark:text-blue-400 font-bold' : ''">4. Dokumen</button>
                <button type="button" @click="goToStep(5)" class="hover:underline" :class="step >= 5 ? 'text-[#1769AA] dark:text-blue-400 font-bold' : ''">5. Biaya</button>
                <button type="button" @click="goToStep(6)" class="hover:underline" :class="step >= 6 ? 'text-[#1769AA] dark:text-blue-400 font-bold' : ''">6. Foto</button>
                <button type="button" @click="goToStep(7)" class="hover:underline" :class="step >= 7 ? 'text-[#1769AA] dark:text-blue-400 font-bold' : ''">7. Review</button>
            </div>
        </div>

        <!-- Dynamic Step Error Banner -->
        <div x-show="errorMessage" x-cloak x-transition class="p-4 bg-red-100 dark:bg-red-950/50 border-l-4 border-red-600 rounded-r-2xl text-red-900 dark:text-red-200 text-xs font-semibold flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span x-text="errorMessage"></span>
            </div>
            <button type="button" @click="errorMessage = ''" class="text-red-700 dark:text-red-400 hover:underline text-xs font-bold ml-4">&times; Tutup</button>
        </div>

        @if ($errors->any())
            <div class="p-4 bg-red-50 dark:bg-red-950/30 border-l-4 border-red-500 rounded-r-2xl text-red-800 dark:text-red-300 text-xs font-medium space-y-1 shadow-sm">
                <div class="font-bold text-red-900 dark:text-red-200">Terjadi kesalahan saat pengajuan laporan:</div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" novalidate @submit="submitForm($event)">
            @csrf

            <!-- STEP 1: INFORMASI PERJALANAN -->
            <div x-show="step === 1" class="bg-white dark:bg-[#11223A] p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                <h3 class="font-bold text-sm text-[#0F2747] dark:text-white border-b border-slate-200 dark:border-slate-700 pb-2">Step 1: Informasi Perjalanan Dinas</h3>

                <!-- Hidden OCR Extracted Fields -->
                <input type="hidden" name="nomor_surat_tugas" x-model="nomorSuratTugas">
                <input type="hidden" name="tanggal_surat_tugas" x-model="tanggalSuratTugas">
                <input type="hidden" name="tgl_mulai_dinas" x-model="tglMulai">
                <input type="hidden" name="tgl_selesai_dinas" x-model="tglSelesai">
                <input type="hidden" name="kota_tujuan" x-model="kotaTujuan">

                <!-- Auto-Scan OCR Box with Integrated Results Display -->
                <div class="bg-sky-50/70 dark:bg-sky-950/40 p-3 sm:p-3.5 rounded-2xl border border-sky-100 dark:border-sky-900/60 space-y-3">
                    <template x-if="ocrMessage">
                        <div :class="ocrStatus === 'success' ? 'bg-emerald-100 dark:bg-emerald-950/80 border-emerald-300 dark:border-emerald-800 text-emerald-900 dark:text-emerald-200' : ocrStatus === 'warning' ? 'bg-amber-100 dark:bg-amber-950/80 border-amber-300 dark:border-amber-800 text-amber-900 dark:text-amber-200' : 'bg-rose-100 dark:bg-rose-950/80 border-rose-300 dark:border-rose-800 text-rose-900 dark:text-rose-200'" class="p-2.5 rounded-xl border text-[11px] font-bold flex items-center justify-between transition-all">
                            <span x-text="ocrMessage"></span>
                            <button type="button" @click="ocrMessage = ''" class="font-bold text-sm opacity-75 hover:opacity-100">&times;</button>
                        </div>
                    </template>

                    <div class="flex items-center justify-between gap-2.5 text-xs">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#1769AA] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <div>
                                <p class="font-extrabold text-xs text-[#0F2747] dark:text-white">Unggah Berkas Surat Undangan</p>
                            </div>
                        </div>
                        <label class="px-3 py-1.5 bg-[#1769AA] hover:bg-[#0F2747] text-white rounded-xl text-[11px] font-bold transition cursor-pointer shrink-0 flex items-center gap-1.5 shadow-sm">
                            <span x-text="isNomorSuratScanning ? 'Membaca...' : 'Unggah Surat'"></span>
                            <input type="file" accept="application/pdf,image/*" class="hidden" :disabled="isNomorSuratScanning" @change="triggerOcrScan($event, (text, data) => applyOcrData(data), () => isNomorSuratScanning = true, () => isNomorSuratScanning = false)">
                        </label>
                    </div>

                    <!-- Live OCR Extracted Results Grid -->
                    <div class="p-3.5 rounded-xl bg-white dark:bg-[#0B1727] border border-sky-100 dark:border-sky-900/40 space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                            <h4 class="font-bold text-xs text-[#0F2747] dark:text-sky-300 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Hasil Ekstraksi OCR Surat</span>
                            </h4>
                            <span class="text-[10px] px-2 py-0.5 rounded-md font-semibold transition-all" :class="nomorSuratTugas ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400' : 'bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400'" x-text="nomorSuratTugas ? 'Terbaca dari Scan Surat' : 'Belum Unggah Surat'"></span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-300 mb-0.5">Nomor Surat Tugas</label>
                                <input type="text" x-model="nomorSuratTugas" placeholder="Akan otomatis terisi dari scan surat..." class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-[#11223A] text-slate-900 dark:text-slate-100 text-xs focus:border-[#1769AA]">
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-300 mb-0.5">Tanggal Surat Tugas</label>
                                <input type="text" x-model="tanggalSuratTugas" placeholder="Akan otomatis terisi dari scan surat..." class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-[#11223A] text-slate-900 dark:text-slate-100 text-xs focus:border-[#1769AA]">
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-300 mb-0.5">Tanggal Berangkat & Pulang</label>
                                <div class="flex items-center gap-2">
                                    <input type="text" x-model="tglMulai" placeholder="Tgl Mulai..." class="w-1/2 rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-[#11223A] text-slate-900 dark:text-slate-100 text-xs focus:border-[#1769AA]">
                                    <span class="text-slate-400">s/d</span>
                                    <input type="text" x-model="tglSelesai" placeholder="Tgl Selesai..." class="w-1/2 rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-[#11223A] text-slate-900 dark:text-slate-100 text-xs focus:border-[#1769AA]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-300 mb-0.5">Kota Tujuan Perjalanan</label>
                                <input type="text" x-model="kotaTujuan" placeholder="Akan otomatis terisi dari scan surat..." class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-[#11223A] text-slate-900 dark:text-slate-100 text-xs focus:border-[#1769AA]">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-300 mb-0.5">Hal / Perihal Perjalanan Dinas <span class="text-red-500">*</span></label>
                                <input type="text" name="isi_pointer" x-model="isiPointer" placeholder="Akan otomatis terisi dari scan surat (cth: Penyusunan Peta Proses Bisnis BPSDM Kementerian PU Tahun 2026)..." class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-[#11223A] text-slate-900 dark:text-slate-100 text-xs focus:border-[#1769AA]">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Pejabat Pembuat Komitmen (PPK) <span class="text-red-500">*</span></label>
                        <select name="ppk_id" x-model="ppkId" required class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-[#0B1727] text-slate-900 dark:text-slate-100 text-xs focus:border-[#1769AA] focus:ring-[#1769AA]">
                            <option value="">-- Pilih PPK --</option>
                            @foreach($ppks as $ppk)
                                <option value="{{ $ppk->id }}">{{ $ppk->nama }} ({{ $ppk->jabatan }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Pembuatan SPPD (H+2 s/d H+3 Pulang)</label>
                        <input type="text" name="tanggal_sppd" x-ref="fpTanggalSppd" x-init="initDatePicker($el, 'tanggalSppd')" placeholder="Otomatis H+2 dari Tanggal Pulang" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-[#0B1727] text-slate-900 dark:text-slate-100 text-xs focus:border-[#1769AA] focus:ring-[#1769AA]">
                        <p x-show="tglSelesai" class="text-[10px] text-blue-600 dark:text-blue-400 mt-1 font-medium">
                            Otomatis disesuaikan: <span class="font-bold" x-text="minSppdFormatted"></span> s/d <span class="font-bold" x-text="maxSppdFormatted"></span>
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Pelaksana Perjalanan Dinas (Pilih Satu atau Lebih) <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#0B1727] p-3 rounded-xl max-h-40 overflow-y-auto">
                            @foreach($pegawai as $p)
                                <label class="flex items-center gap-2 p-1.5 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg cursor-pointer text-xs">
                                    <input type="checkbox" name="pelaksana[]" value="{{ $p->id }}" x-model="selectedPelaksana" class="rounded text-[#1769AA] focus:ring-[#1769AA]">
                                    <span class="font-medium text-slate-800 dark:text-slate-200">{{ $p->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: URAIAN KEGIATAN -->
            <div x-show="step === 2" class="bg-white dark:bg-[#11223A] p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-slate-200 dark:border-slate-700 pb-3 gap-2">
                    <div>
                        <h3 class="font-bold text-sm text-[#0F2747] dark:text-white">Step 2: Uraian & Kronologis Kegiatan Harian</h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="addSchedule()" class="px-3 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-[#1769AA] dark:text-blue-400 font-semibold text-xs rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/50 transition flex items-center gap-1">
                            + Tambah Manual
                        </button>
                    </div>
                </div>

                <!-- Prominent File Upload Dropzone for Step 2 Agenda File -->
                <div class="border-2 border-dashed border-[#1769AA]/40 dark:border-blue-500/40 rounded-2xl p-5 bg-blue-50/40 dark:bg-blue-950/20 text-center relative hover:border-[#1769AA] transition cursor-pointer group">
                    <input type="file" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" :disabled="isScheduleScanning" @change="triggerOcrScan($event, (text, data) => applyScheduleOcrData(data), () => isScheduleScanning = true, () => isScheduleScanning = false)">
                    <div class="flex flex-col items-center justify-center space-y-2 pointer-events-none">
                        <div class="w-10 h-10 rounded-full bg-[#1769AA]/10 dark:bg-blue-400/10 flex items-center justify-center text-[#1769AA] dark:text-blue-400 group-hover:scale-110 transition-transform">
                            <template x-if="!isScheduleScanning">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </template>
                            <template x-if="isScheduleScanning">
                                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            </template>
                        </div>
                        <div>
                            <span class="font-bold text-xs text-[#0F2747] dark:text-white" x-text="isScheduleScanning ? 'Mengekstrak Agenda & Membaca Tabel...' : 'Klik atau Seret Berkas Agenda/Jadwal Kegiatan Di Sini'"></span>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Unggah foto/PDF jadwal kegiatan (seperti tabel di atas). Hasil Tanggal, Lokasi, dan Uraian Kegiatan akan muncul otomatis di tabel bawah.</p>
                        </div>
                    </div>
                </div>

                <!-- Empty state if no schedules exist yet -->
                <template x-if="schedules.length === 0">
                    <div class="p-6 text-center border border-slate-200 dark:border-slate-700/60 rounded-xl bg-slate-50/50 dark:bg-[#0B1727]/50 text-slate-500 dark:text-slate-400 text-xs space-y-2">
                        <p class="font-semibold text-slate-700 dark:text-slate-300">Belum ada rincian kegiatan.</p>
                        <p class="text-[11px]">Silakan unggah berkas agenda pada area di atas untuk membaca hasil Tanggal, Lokasi, dan Uraian Kegiatan secara otomatis, atau klik <strong class="text-[#1769AA] dark:text-blue-400">+ Tambah Manual</strong>.</p>
                    </div>
                </template>

                <div class="space-y-3" x-show="schedules.length > 0">
                    <template x-for="(sched, index) in schedules" :key="sched.id || index">
                        <div class="p-4 bg-slate-50 dark:bg-[#0B1727] border border-slate-200 dark:border-slate-700 rounded-xl space-y-3 relative text-xs">
                            <div class="flex items-center justify-between font-bold text-slate-700 dark:text-slate-300">
                                <span>Kegiatan #<span x-text="index + 1"></span></span>
                                <button type="button" @click="removeSchedule(index)" class="text-red-600 dark:text-red-400 hover:underline text-xs">Hapus</button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal <span class="text-red-500">*</span></label>
                                    <input type="text" :name="`schedules[${index}][tanggal]`" x-model="sched.tanggal" :data-sched-index="index" x-init="$nextTick(() => initScheduleDatePicker($el, sched))" placeholder="Contoh: 14 Sep 2026" required class="schedule-date-picker w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-[#11223A] text-slate-900 dark:text-slate-100 text-xs focus:border-[#1769AA] focus:ring-[#1769AA]">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Lokasi / Keterangan</label>
                                    <input type="text" :name="`schedules[${index}][lokasi_keterangan]`" x-model="sched.lokasi" placeholder="Misal: Bandara / Balai Diklat" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-[#11223A] text-slate-900 dark:text-slate-100 text-xs">
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Uraian Kegiatan (Dapat berisi rincian jam) <span class="text-red-500">*</span></label>
                                    <textarea :name="`schedules[${index}][uraian_kegiatan]`" x-model="sched.uraian" :value="sched.uraian" rows="3" required placeholder="08.00 - 08.30 Registrasi Peserta&#10;08.30 - 12.00 Pembukaan Kegiatan..." class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-[#11223A] text-slate-900 dark:text-slate-100 text-xs"></textarea>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- STEP 3: PETA PROSES BISNIS -->
            <div x-show="step === 3" class="bg-white dark:bg-[#11223A] p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                <div class="border-b border-slate-200 dark:border-slate-700 pb-2">
                    <h3 class="font-bold text-sm text-[#0F2747] dark:text-white">Step 3: Catatan & Pointer Substansi (Opsional)</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tuliskan narasi pembahasan atau catatan tambahan laporan perjalanan dinas jika ada.</p>
                </div>

                <div>
                    <textarea name="pointer_substansi" x-model="pointerSubstansi" rows="8" placeholder="Masukkan narasi pembahasan atau catatan tambahan (Opsional)..." class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-[#0B1727] text-slate-900 dark:text-slate-100 text-xs focus:border-[#1769AA] focus:ring-[#1769AA]"></textarea>
                </div>
            </div>

            <!-- STEP 4: DOKUMEN PERJALANAN -->
            <div x-show="step === 4" class="bg-white dark:bg-[#11223A] p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                <h3 class="font-bold text-sm text-[#0F2747] dark:text-white border-b border-slate-200 dark:border-slate-700 pb-2">Step 4: Upload Dokumen & Tiket Transportasi</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Upload bukti tiket dan taksi (PDF/JPG/PNG). Gambar di atas 2MB akan dikompres otomatis secara instan.</p>

                <template x-if="isCompressing">
                    <div class="p-3 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 rounded-xl text-xs text-amber-900 dark:text-amber-200 flex items-center gap-2 animate-pulse font-semibold">
                        <svg class="w-4 h-4 animate-spin text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        <span>Mengompres ukuran gambar otomatis...</span>
                    </div>
                </template>

                <template x-if="compressionNotice">
                    <div x-transition class="p-3 bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 rounded-xl text-xs text-blue-900 dark:text-blue-200 flex items-center justify-between font-medium">
                        <div class="flex items-center gap-2">
                            <span x-text="compressionNotice"></span>
                        </div>
                        <button type="button" @click="compressionNotice = ''" class="text-blue-700 dark:text-blue-400 font-bold hover:underline ml-3">&times;</button>
                    </div>
                </template>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <template x-for="docField in docFields" :key="docField.name">
                        <div class="p-3 bg-slate-50 dark:bg-[#0B1727] border border-slate-200 dark:border-slate-700 rounded-xl space-y-2">
                            <label class="block font-bold text-slate-800 dark:text-slate-200 mb-1">
                                <span x-text="docField.label"></span>
                                <template x-if="docField.required"><span class="text-red-500">*</span></template>
                            </label>

                            <!-- Preview hasil kamera -->
                            <template x-if="cameraFiles[docField.name]">
                                <div class="flex items-center gap-2 p-2 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 rounded-lg">
                                    <span class="text-emerald-700 dark:text-emerald-300 font-semibold text-[11px]"><span x-text="cameraFiles[docField.name].name"></span></span>
                                    <button type="button" @click="removeCameraFile(docField)" class="text-red-500 hover:underline text-[10px] font-bold ml-auto">&times; Hapus</button>
                                </div>
                            </template>

                            <!-- File input - selalu ada di DOM, hidden saat ada camera file -->
                            <input type="file" :name="docField.name" :accept="docField.accept" @change="handleFileUpload($event, docField.trackAs); clearCameraFile(docField.name)" class="w-full text-xs text-slate-700 dark:text-slate-300" :class="cameraFiles[docField.name] ? 'hidden' : ''">

                            <!-- Tombol kamera -->
                            <template x-if="docField.allowCamera">
                                <button type="button" @click="openCamera(docField)" class="w-full px-3 py-2 bg-[#1769AA]/10 dark:bg-blue-900/30 text-[#1769AA] dark:text-blue-400 border border-[#1769AA]/30 dark:border-blue-700 rounded-lg text-xs font-bold hover:bg-[#1769AA]/20 dark:hover:bg-blue-900/50 transition flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span x-text="cameraFiles[docField.name] ? 'Foto Ulang dari Kamera' : 'Ambil Foto dari Kamera'"></span>
                                </button>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            <!-- STEP 5: PEMBAYARAN & RINCIAN PENGELUARAN -->
            <div x-show="step === 5" class="bg-white dark:bg-[#11223A] p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 pb-2">
                    <div>
                        <h3 class="font-bold text-sm text-[#0F2747] dark:text-white">Step 5: Rincian Pengeluaran & Metode Pembayaran Per-Item</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tentukan metode pembayaran (KKP atau Cash) secara individual untuk setiap komponen pengeluaran.</p>
                    </div>
                    <button type="button" @click="addExpense()" class="px-3 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-[#1769AA] dark:text-blue-400 font-semibold text-xs rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/50 transition">
                        + Tambah Komponen Biaya
                    </button>
                </div>

                <!-- Dynamic Expenses Table -->
                <div class="space-y-3">
                    <template x-for="(exp, idx) in expenses" :key="idx">
                        <div class="p-4 bg-slate-50 dark:bg-[#0B1727] border border-slate-200 dark:border-slate-700 rounded-xl space-y-3 relative text-xs">
                            <div class="flex items-center justify-between font-bold text-slate-700 dark:text-slate-300">
                                <span>Pengeluaran #<span x-text="idx + 1"></span></span>
                                <button type="button" @click="removeExpense(idx)" class="text-red-600 dark:text-red-400 hover:underline text-xs" x-show="expenses.length > 1">Hapus</button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                                <div class="md:col-span-2">
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Jenis / Uraian Biaya <span class="text-red-500">*</span></label>
                                    <input type="text" :name="`expenses[${idx}][expense_type]`" x-model="exp.type" required placeholder="Misal: Tiket Pergi / Taksi Bandara / Uang Harian" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-[#11223A] text-slate-900 dark:text-slate-100 text-xs">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Nominal (Rp) <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-500 dark:text-slate-400 font-bold font-mono text-xs">Rp</span>
                                        <input type="number" step="1000" min="0" :name="`expenses[${idx}][amount]`" x-model="exp.amount" required placeholder="0" class="w-full pl-9 pr-2 py-2 bg-white dark:bg-[#0B1727] border border-slate-300 dark:border-slate-600 rounded-xl text-xs text-slate-900 dark:text-white font-mono font-bold focus:border-[#1769AA] focus:ring-1 focus:ring-[#1769AA] [color-scheme:light] dark:[color-scheme:dark] transition">
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Metode Pembayaran <span class="text-red-500">*</span></label>
                                    <select :name="`expenses[${idx}][payment_method]`" x-model="exp.payment_method" required class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-[#11223A] text-slate-900 dark:text-slate-100 text-xs font-bold">
                                        <option value="kkp">KKP (Kartu Kredit Pemerintah)</option>
                                        <option value="cash">Cash / Reimburse (Tunai)</option>
                                    </select>
                                </div>
                                <div class="md:col-span-4">
                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Keterangan / Catatan Biaya (Opsional)</label>
                                    <input type="text" :name="`expenses[${idx}][description]`" x-model="exp.description" placeholder="Misal: Garuda Indonesia GA-602 / Taksi Bluebird" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-[#11223A] text-slate-900 dark:text-slate-100 text-xs">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Financial Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-2">
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl text-xs">
                        <div class="text-slate-500 dark:text-slate-400 font-semibold">Total KKP (Kartu Kredit Govt)</div>
                        <div class="text-base font-extrabold text-[#1769AA] dark:text-blue-400 mt-0.5" x-text="formatRupiah(totalKkp())"></div>
                    </div>
                    <div class="p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl text-xs">
                        <div class="text-slate-500 dark:text-slate-400 font-semibold">Total Cash / Reimburse (Tunai)</div>
                        <div class="text-base font-extrabold text-amber-700 dark:text-amber-400 mt-0.5" x-text="formatRupiah(totalCash())"></div>
                    </div>
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl text-xs">
                        <div class="text-slate-500 dark:text-slate-400 font-semibold">Grand Total Pengeluaran</div>
                        <div class="text-base font-extrabold text-emerald-700 dark:text-emerald-400 mt-0.5" x-text="formatRupiah(totalCost())"></div>
                    </div>
                </div>
            </div>

            <!-- STEP 6: DOKUMENTASI FOTO -->
            <div x-show="step === 6" class="bg-white dark:bg-[#11223A] p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                <h3 class="font-bold text-sm text-[#0F2747] dark:text-white border-b border-slate-200 dark:border-slate-700 pb-2">Step 6: Upload Dokumentasi Kegiatan (Maksimal 8 Foto) <span class="text-red-500">*</span></h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Pilih hingga 8 foto dokumentasi kegiatan (JPG/PNG). Foto di atas 2MB akan otomatis dikompres sebelum diunggah.</p>

                <template x-if="isCompressing">
                    <div class="p-3 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 rounded-xl text-xs text-amber-900 dark:text-amber-200 flex items-center gap-2 animate-pulse font-semibold">
                        <svg class="w-4 h-4 animate-spin text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        <span>Mengompres foto kegiatan secara otomatis...</span>
                    </div>
                </template>

                <template x-if="compressionNotice">
                    <div x-transition class="p-3 bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 rounded-xl text-xs text-blue-900 dark:text-blue-200 flex items-center justify-between font-medium">
                        <div class="flex items-center gap-2">
                            <span x-text="compressionNotice"></span>
                        </div>
                        <button type="button" @click="compressionNotice = ''" class="text-blue-700 dark:text-blue-400 font-bold hover:underline ml-3">&times;</button>
                    </div>
                </template>

                <div>
                    <input type="file" name="dokumentasi[]" multiple accept="image/*" @change="handleFileUpload($event, 'dokumentasi')" class="w-full text-xs text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-600 rounded-xl p-3 bg-slate-50 dark:bg-[#0B1727]">
                </div>
            </div>

            <!-- STEP 7: REVIEW & SUBMIT -->
            <div x-show="step === 7" class="bg-white dark:bg-[#11223A] p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-200 dark:border-slate-700 pb-3 gap-2">
                    <div>
                        <h3 class="font-bold text-base text-[#0F2747] dark:text-white">Step 7: Review Status & Kelengkapan Data Laporan</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Periksa seluruh status tahapan sebelum mengirim laporan ke Admin/Verifikator.</p>
                    </div>
                    <span 
                        class="px-4 py-1.5 rounded-full font-black text-xs border tracking-wide shrink-0"
                        :class="completenessScore === 100 ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800' : 'bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300 border-amber-300 dark:border-amber-800'"
                    >
                        Completeness Score: <span x-text="completenessScore"></span>%
                    </span>
                </div>

                <!-- Main Status Overview Box -->
                <template x-if="requiredIncompleteCount === 0">
                    <div class="p-5 bg-emerald-50 dark:bg-emerald-950/30 border-2 border-emerald-500/50 rounded-2xl text-xs text-emerald-900 dark:text-emerald-200 space-y-2 shadow-sm">
                        <div class="font-extrabold text-sm flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>STATUS LAPORAN: SELURUH DATA LENGKAP (SIAP DISUBMIT)</span>
                        </div>
                        <p class="text-emerald-800 dark:text-emerald-300 leading-relaxed">
                            Seluruh tahapan pengisian laporan telah terisi dengan lengkap dan cermat. Anda dapat langsung menekan tombol <strong>Submit Laporan Dinas</strong> di bawah ini.
                        </p>
                    </div>
                </template>

                <template x-if="requiredIncompleteCount > 0">
                    <div class="p-5 bg-red-50 dark:bg-red-950/40 border-2 border-red-500/50 rounded-2xl text-xs text-red-900 dark:text-red-200 space-y-2 shadow-sm">
                        <div class="font-extrabold text-sm flex items-center justify-between flex-wrap gap-2">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-red-500 animate-pulse"></span>
                                <span>STATUS LAPORAN: BELUM LENGKAP</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-red-200 dark:bg-red-900 text-red-900 dark:text-red-100 text-[10px] font-black uppercase" x-text="`${requiredIncompleteCount} Tahapan Belum Lengkap`"></span>
                        </div>
                        <p class="text-red-800 dark:text-red-300 leading-relaxed">
                            Sistem <strong>tidak dapat memproses atau mengirim laporan ini</strong> karena terdapat tahapan pengisian yang belum lengkap (ditandai dengan warna merah di bawah). Silakan klik tombol <em>Lengkapi Step X</em> pada bagian yang belum lengkap.
                        </p>
                    </div>
                </template>

                <!-- Detailed Per-Step Review Cards (Step 1 s/d Step 6) -->
                <div class="space-y-3 pt-2">
                    <h4 class="font-extrabold text-xs text-[#0F2747] dark:text-white uppercase tracking-wider">Status Kelengkapan Per-Tahapan (Step 1 - 6):</h4>
                    
                    <!-- Step 1 Card -->
                    <div class="p-4 rounded-2xl border text-xs transition space-y-2" :class="stepStatus[1].isValid ? 'bg-emerald-50/40 dark:bg-emerald-950/10 border-emerald-200 dark:border-emerald-800/60' : 'bg-red-50/60 dark:bg-red-950/20 border-red-300 dark:border-red-800/80'">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs" :class="stepStatus[1].isValid ? 'text-emerald-600' : 'text-red-600'" x-text="stepStatus[1].isValid ? '[V]' : '[!]'"></span>
                                <span class="font-extrabold text-[#0F2747] dark:text-white text-xs">Step 1: Informasi Perjalanan Dinas</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full font-black text-[10px] uppercase border" :class="stepStatus[1].isValid ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 border-emerald-300' : 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300 border-red-300'">
                                    <span x-text="stepStatus[1].isValid ? 'LENGKAP' : 'BELUM LENGKAP'"></span>
                                </span>
                                <button type="button" @click="goToStep(1)" class="px-3 py-1 bg-white dark:bg-[#0B1727] border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                    <span x-text="stepStatus[1].isValid ? 'Edit Step 1' : 'Lengkapi Step 1'"></span> &rarr;
                                </button>
                            </div>
                        </div>
                        <template x-if="!stepStatus[1].isValid">
                            <ul class="list-disc list-inside text-[11px] text-red-700 dark:text-red-300 space-y-0.5 pt-1 pl-2">
                                <template x-for="err in stepStatus[1].errors" :key="err">
                                    <li x-text="err"></li>
                                </template>
                            </ul>
                        </template>
                    </div>

                    <!-- Step 2 Card -->
                    <div class="p-4 rounded-2xl border text-xs transition space-y-2" :class="stepStatus[2].isValid ? 'bg-emerald-50/40 dark:bg-emerald-950/10 border-emerald-200 dark:border-emerald-800/60' : 'bg-red-50/60 dark:bg-red-950/20 border-red-300 dark:border-red-800/80'">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs" :class="stepStatus[2].isValid ? 'text-emerald-600' : 'text-red-600'" x-text="stepStatus[2].isValid ? '[V]' : '[!]'"></span>
                                <span class="font-extrabold text-[#0F2747] dark:text-white text-xs">Step 2: Uraian & Kronologis Kegiatan Harian</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full font-black text-[10px] uppercase border" :class="stepStatus[2].isValid ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 border-emerald-300' : 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300 border-red-300'">
                                    <span x-text="stepStatus[2].isValid ? 'LENGKAP' : 'BELUM LENGKAP'"></span>
                                </span>
                                <button type="button" @click="goToStep(2)" class="px-3 py-1 bg-white dark:bg-[#0B1727] border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                    <span x-text="stepStatus[2].isValid ? 'Edit Step 2' : 'Lengkapi Step 2'"></span> &rarr;
                                </button>
                            </div>
                        </div>
                        <template x-if="!stepStatus[2].isValid">
                            <ul class="list-disc list-inside text-[11px] text-red-700 dark:text-red-300 space-y-0.5 pt-1 pl-2">
                                <template x-for="err in stepStatus[2].errors" :key="err">
                                    <li x-text="err"></li>
                                </template>
                            </ul>
                        </template>
                    </div>

                    <!-- Step 3 Card (Opsional) -->
                    <div class="p-4 rounded-2xl border text-xs transition space-y-2" :class="stepStatus[3].isValid ? 'bg-emerald-50/40 dark:bg-emerald-950/10 border-emerald-200 dark:border-emerald-800/60' : 'bg-amber-50/40 dark:bg-amber-950/10 border-amber-200 dark:border-amber-800/60'">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs" :class="stepStatus[3].isValid ? 'text-emerald-600' : 'text-amber-600'" x-text="stepStatus[3].isValid ? '[V]' : '[-]'"></span>
                                <span class="font-extrabold text-[#0F2747] dark:text-white text-xs">Step 3: Pointer Peta Proses Bisnis (Opsional)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full font-black text-[10px] uppercase border" :class="stepStatus[3].isValid ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 border-emerald-300' : 'bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300 border-amber-300'">
                                    <span x-text="stepStatus[3].isValid ? 'LENGKAP' : 'OPSIONAL (BELUM DIISI)'"></span>
                                </span>
                                <button type="button" @click="goToStep(3)" class="px-3 py-1 bg-white dark:bg-[#0B1727] border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                    <span>Lihat Step 3</span> &rarr;
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4 Card (Opsional) -->
                    <div class="p-4 rounded-2xl border text-xs transition space-y-2" :class="stepStatus[4].isValid ? 'bg-emerald-50/40 dark:bg-emerald-950/10 border-emerald-200 dark:border-emerald-800/60' : 'bg-amber-50/40 dark:bg-amber-950/10 border-amber-200 dark:border-amber-800/60'">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs" :class="stepStatus[4].isValid ? 'text-emerald-600' : 'text-amber-600'" x-text="stepStatus[4].isValid ? '[V]' : '[-]'"></span>
                                <span class="font-extrabold text-[#0F2747] dark:text-white text-xs">Step 4: Upload Dokumen & Tiket Transportasi (Opsional)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full font-black text-[10px] uppercase border" :class="stepStatus[4].isValid ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 border-emerald-300' : 'bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300 border-amber-300'">
                                    <span x-text="stepStatus[4].isValid ? 'LENGKAP' : 'OPSIONAL (BELUM UPLOAD SPT)'"></span>
                                </span>
                                <button type="button" @click="goToStep(4)" class="px-3 py-1 bg-white dark:bg-[#0B1727] border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                    <span>Lihat Step 4</span> &rarr;
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5 Card -->
                    <div class="p-4 rounded-2xl border text-xs transition space-y-2" :class="stepStatus[5].isValid ? 'bg-emerald-50/40 dark:bg-emerald-950/10 border-emerald-200 dark:border-emerald-800/60' : 'bg-red-50/60 dark:bg-red-950/20 border-red-300 dark:border-red-800/80'">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs" :class="stepStatus[5].isValid ? 'text-emerald-600' : 'text-red-600'" x-text="stepStatus[5].isValid ? '[V]' : '[!]'"></span>
                                <span class="font-extrabold text-[#0F2747] dark:text-white text-xs">Step 5: Rincian Pengeluaran & Metode Pembayaran</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full font-black text-[10px] uppercase border" :class="stepStatus[5].isValid ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 border-emerald-300' : 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300 border-red-300'">
                                    <span x-text="stepStatus[5].isValid ? 'LENGKAP' : 'BELUM LENGKAP'"></span>
                                </span>
                                <button type="button" @click="goToStep(5)" class="px-3 py-1 bg-white dark:bg-[#0B1727] border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                    <span x-text="stepStatus[5].isValid ? 'Edit Step 5' : 'Lengkapi Step 5'"></span> &rarr;
                                </button>
                            </div>
                        </div>
                        <template x-if="!stepStatus[5].isValid">
                            <ul class="list-disc list-inside text-[11px] text-red-700 dark:text-red-300 space-y-0.5 pt-1 pl-2">
                                <template x-for="err in stepStatus[5].errors" :key="err">
                                    <li x-text="err"></li>
                                </template>
                            </ul>
                        </template>
                    </div>

                    <!-- Step 6 Card (Opsional) -->
                    <div class="p-4 rounded-2xl border text-xs transition space-y-2" :class="stepStatus[6].isValid ? 'bg-emerald-50/40 dark:bg-emerald-950/10 border-emerald-200 dark:border-emerald-800/60' : 'bg-amber-50/40 dark:bg-amber-950/10 border-amber-200 dark:border-amber-800/60'">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs" :class="stepStatus[6].isValid ? 'text-emerald-600' : 'text-amber-600'" x-text="stepStatus[6].isValid ? '[V]' : '[-]'"></span>
                                <span class="font-extrabold text-[#0F2747] dark:text-white text-xs">Step 6: Upload Dokumentasi Kegiatan (Opsional)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full font-black text-[10px] uppercase border" :class="stepStatus[6].isValid ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 border-emerald-300' : 'bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300 border-amber-300'">
                                    <span x-text="stepStatus[6].isValid ? 'LENGKAP' : 'OPSIONAL (BELUM ADA FOTO)'"></span>
                                </span>
                                <button type="button" @click="goToStep(6)" class="px-3 py-1 bg-white dark:bg-[#0B1727] border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                    <span>Lihat Step 6</span> &rarr;
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Wizard Footer Buttons -->
            <div class="mt-6 flex items-center justify-between">
                <button type="button" @click="step--" x-show="step > 1" class="px-5 py-2.5 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition">
                    &larr; Kembali
                </button>

                <div class="flex items-center gap-2 ms-auto">
                    <button type="button" @click="nextStep()" x-show="step < 7" class="px-6 py-2.5 bg-[#1769AA] hover:bg-[#0F2747] text-white rounded-xl text-xs font-bold transition shadow-sm">
                        Lanjut &rarr;
                    </button>

                    <template x-if="step === 7">
                        <div>
                            <button 
                                type="submit" 
                                x-show="requiredIncompleteCount === 0"
                                class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-md flex items-center gap-2"
                            >
                                <span>Submit Laporan Dinas</span>
                            </button>

                            <button 
                                type="button" 
                                x-show="requiredIncompleteCount > 0"
                                @click="errorMessage = 'Laporan belum dapat disubmit. Silakan lengkapi tahapan yang ditandai BELUM LENGKAP di bawah.'; window.scrollTo({ top: 0, behavior: 'smooth' });"
                                class="px-6 py-2.5 bg-slate-400 dark:bg-slate-700 text-white rounded-xl text-xs font-bold shadow-none opacity-70 cursor-not-allowed flex items-center gap-2"
                                title="Lengkapi dahulu tahapan yang ditandai merah"
                            >
                                <span>Belum Dapat Submit (Lengkapi Step Merah)</span>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Global Camera Modal -->
            <div x-show="cameraOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm" @keydown.escape.window="closeCamera()">
                <div class="bg-white dark:bg-[#11223A] rounded-2xl shadow-2xl max-w-lg w-full mx-4 overflow-hidden border border-slate-200 dark:border-slate-700">
                    <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <h4 class="font-bold text-sm text-[#0F2747] dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#1769AA]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span x-text="cameraTargetField ? ('Kamera: ' + cameraTargetField.label) : 'Kamera: Scan OCR Foto'"></span>
                        </h4>
                        <button type="button" @click="closeCamera()" class="text-slate-500 hover:text-red-500 transition text-lg font-bold">&times;</button>
                    </div>
                    <div class="relative bg-black">
                        <video x-ref="cameraVideo" autoplay playsinline class="w-full aspect-[4/3] object-cover"></video>
                        <canvas x-ref="cameraCanvas" class="hidden"></canvas>
                        <template x-if="cameraError">
                            <div class="absolute inset-0 flex items-center justify-center bg-black/80 text-white text-xs text-center p-4">
                                <div>
                                    <p class="font-bold text-red-400 mb-1">Kamera Tidak Tersedia</p>
                                    <p x-text="cameraError" class="text-slate-300"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="p-4 flex items-center justify-between gap-3">
                        <button type="button" @click="closeCamera()" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold hover:bg-slate-300 dark:hover:bg-slate-600 transition">
                            Batal
                        </button>
                        <button type="button" @click="snapPhoto()" :disabled="!!cameraError" class="px-6 py-2.5 bg-[#1769AA] hover:bg-[#0F2747] text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="currentColor"/></svg>
                            Ambil Foto
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <script>
        function reportWizard() {
            return {
                step: 1,
                nomorSuratTugas: '{{ old('nomor_surat_tugas', '') }}',
                tanggalSuratTugas: '{{ old('tanggal_surat_tugas', '') }}',
                tglMulai: '{{ old('tgl_mulai_dinas', '') }}',
                tglSelesai: '{{ old('tgl_selesai_dinas', '') }}',
                tanggalSppd: '{{ old('tanggal_sppd', '') }}',
                kotaTujuan: '{{ old('kota_tujuan', '') }}',
                ppkId: '{{ old('ppk_id', '') }}',
                selectedPelaksana: [{{ auth()->id() }}],
                isiPointer: '{{ old('isi_pointer', '') }}',
                pointerSubstansi: '{{ old('pointer_substansi', '') }}',
                isPointerScanning: false,
                isNomorSuratScanning: false,
                isScheduleScanning: false,
                ocrMessage: '',
                ocrStatus: '',
                applyScheduleOcrData(data) {
                    if (!data) return;

                    if (data.hal && !this.isiPointer) {
                        this.isiPointer = data.hal;
                    }

                    // 1. Ekstraksi otomatis dari Step 1 (Upload Berkas Utama Surat Tugas)
                    if (data.kegiatan_list && data.kegiatan_list.length > 0) {
                        this.schedules = data.kegiatan_list.map((s, idx) => ({
                            id: Date.now() + idx,
                            tanggal: this.parseOcrDateToYmd(s.tanggal) || this.tglMulai || '',
                            lokasi: s.lokasi_keterangan || data.tempat_pelaksanaan || this.kotaTujuan || 'Lokasi Kegiatan',
                            uraian: s.uraian_kegiatan || '',
                            isScanning: false
                        }));
                    } else if (data.text && data.text.length > 10) {
                        // Fallback teks murni jika format tabel tidak terpisah
                        if (this.schedules.length === 0) this.addSchedule();
                        this.schedules[0].uraian = data.text;
                    }

                    this.$nextTick(() => {
                        document.querySelectorAll('.schedule-date-picker').forEach(el => {
                            if (el._flatpickr) {
                                const schedIndex = el.getAttribute('data-sched-index');
                                if (schedIndex !== null && this.schedules[schedIndex]) {
                                    el._flatpickr.setDate(this.schedules[schedIndex].tanggal, true);
                                }
                            }
                        });
                    });
                },
                parseOcrDateToYmd(str) {
                    if (!str) return '';
                    const months = {januari:1,februari:2,maret:3,april:4,mei:5,juni:6,juli:7,agustus:8,september:9,oktober:10,november:11,desember:12,jan:1,feb:2,mar:3,apr:4,jun:6,jul:7,agu:8,sep:9,okt:10,nov:11,des:12};
                    const clean = str.replace(/^[A-Za-z]+,\s*/, '').trim();
                    const m = clean.match(/^(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})$/);
                    if (m) {
                        const mon = months[m[2].toLowerCase()];
                        if (mon) return `${m[3]}-${String(mon).padStart(2,'0')}-${m[1].padStart(2,'0')}`;
                    }
                    if (/^\d{4}-\d{2}-\d{2}$/.test(str)) return str;
                    return str;
                },
                applyOcrData(data) {
                    if (!data) return;
                    let foundAny = false;
                    if (data.nomor_surat) { this.nomorSuratTugas = data.nomor_surat; foundAny = true; }
                    if (data.tanggal_surat) { this.tanggalSuratTugas = data.tanggal_surat; foundAny = true; }
                    if (data.tgl_mulai_dinas) { this.tglMulai = data.tgl_mulai_dinas; foundAny = true; }
                    if (data.tgl_selesai_dinas) { this.tglSelesai = data.tgl_selesai_dinas; foundAny = true; }
                    if (data.kota_tujuan) {
                        const blacklistWords = ['narasumber', 'peserta', 'panitia', 'pendamping', 'moderator', 'penguji', 'fasilitator', 'acara', 'kegiatan', 'workshop', 'bimtek', 'sosialisasi', 'rapat', 'koordinasi'];
                        const words = data.kota_tujuan.split(/\s+/).filter(w => !blacklistWords.includes(w.toLowerCase()));
                        this.kotaTujuan = words.join(' ') || data.kota_tujuan;
                        foundAny = true;
                    }
                    if (data.hal) {
                        this.isiPointer = data.hal;
                        foundAny = true;
                    } else if (data.text) {
                        const mHal = data.text.match(/(?:Hal|Perihal|Maksud|Acara)\s*[:\-]?\s*([^\n\r]+)/i);
                        if (mHal && mHal[1]) {
                            const val = mHal[1].replace(/^[:\-\s]+/, '').trim();
                            if (val.length > 3 && !val.toLowerCase().startsWith('nomor')) {
                                this.isiPointer = val;
                                foundAny = true;
                            }
                        }
                    }

                    if (this.tglMulai && this.tglSelesai) {
                        const parseDateStr = (str) => {
                            if (!str) return null;
                            const m = { 'januari':0, 'jan':0, 'februari':1, 'feb':1, 'maret':2, 'mar':2, 'april':3, 'apr':3, 'mei':4, 'juni':5, 'jun':5, 'juli':6, 'jul':6, 'agustus':7, 'agu':7, 'september':8, 'sep':8, 'oktober':9, 'okt':9, 'november':10, 'nov':10, 'desember':11, 'des':11 };
                            const parts = str.trim().split(/\s+/);
                            if (parts.length === 3) {
                                const day = parseInt(parts[0]);
                                const month = m[parts[1].toLowerCase()] ?? 0;
                                const yr = parseInt(parts[2]);
                                return new Date(yr, month, day);
                            }
                            return null;
                        };
                        const d1 = parseDateStr(this.tglMulai);
                        const d2 = parseDateStr(this.tglSelesai);
                        if (d1 && d2 && d1 > d2) {
                            const tmp = this.tglMulai;
                            this.tglMulai = this.tglSelesai;
                            this.tglSelesai = tmp;
                        }
                    }

                    if (!data.nomor_surat && data.text) {
                        const lines = data.text.split('\n').map(l => l.trim()).filter(l => l.length > 0);
                        const codeLine = lines.find(l => l.includes('/') || l.toLowerCase().includes('no'));
                        if (codeLine) {
                            this.nomorSuratTugas = codeLine;
                            foundAny = true;
                        }
                    }

                    if (this.applyScheduleOcrData) {
                        this.applyScheduleOcrData(data);
                    }

                    if (foundAny || (data.text && data.text.length > 15)) {
                        this.ocrStatus = 'success';
                        if (data.handwriting_warning) {
                            this.ocrStatus = 'warning';
                            this.ocrMessage = 'Terdeteksi tulisan tangan — hasil OCR mungkin kurang akurat. Silakan periksa dan koreksi data di bawah secara manual.';
                        } else {
                            this.ocrMessage = data.message || 'Berhasil mengekstraksi data surat undangan via OCR!';
                        }
                    } else {
                        this.ocrStatus = 'error';
                        this.ocrMessage = data.message || 'Berkas kurang jelas atau teks tidak terdeteksi. Silakan periksa kembali.';
                    }
                },
                processOcrFile(file, onSuccess, onStart, onEnd) {
                    if (!file) return;
                    if (onStart) onStart();

                    const formData = new FormData();
                    formData.append('image', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    fetch('{{ route('reports.ocr-scan') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(async res => {
                        const ct = res.headers.get('content-type') || '';
                        if (ct.includes('application/json')) {
                            const json = await res.json();
                            return { ok: res.ok, data: json };
                        }
                        const textErr = await res.text();
                        let customMsg = 'Server mengembalikan respons non-JSON (' + res.status + ').';
                        if (res.status === 504) {
                            customMsg = 'Waktu eksekusi server habis (504 Gateway Timeout). Berkas terlalu besar, mohon gunakan PDF atau foto berukuran lebih kecil.';
                        } else if (res.status === 419) {
                            customMsg = 'Sesi browser Anda telah berakhir. Silakan muat ulang (refresh) halaman.';
                        } else if (res.status === 500) {
                            customMsg = 'Terjadi kesalahan internal pada server pemroses OCR (HTTP 500).';
                        }
                        return { 
                            ok: false, 
                            data: { 
                                status: 'error', 
                                message: customMsg
                            } 
                        };
                    })
                    .then(({ ok, data }) => {
                        if (data.status === 'success') {
                            if (onSuccess) onSuccess(data.text, data);
                            if (!this.ocrMessage) {
                                this.ocrStatus = data.handwriting_warning ? 'warning' : 'success';
                                this.ocrMessage = data.message || 'Berhasil mengekstraksi berkas surat!';
                            }
                        } else {
                            this.ocrStatus = 'error';
                            this.ocrMessage = (data.message || 'Gagal mengekstraksi berkas surat undangan.');
                        }
                    })
                    .catch(err => {
                        console.error('OCR Error:', err);
                        this.ocrStatus = 'error';
                        this.ocrMessage = 'Gagal terhubung ke server pemroses OCR: ' + (err.message || 'Koneksi terputus');
                    })
                    .finally(() => {
                        if (onEnd) onEnd();
                    });
                },

                triggerOcrScan(event, onSuccess, onStart, onEnd) {
                    const file = event.target.files[0];
                    if (!file) return;
                    this.processOcrFile(file, onSuccess, onStart, onEnd);
                    event.target.value = '';
                },
                sptUploaded: false,
                tiketPergiUploaded: false,
                tiketPulangUploaded: false,
                dokumentasiCount: 0,
                errorMessage: '',
                compressionNotice: '',
                isCompressing: false,

                // Camera state
                cameraOpen: false,
                cameraStream: null,
                cameraTargetField: null,
                cameraOcrCallbacks: null,
                cameraError: '',
                cameraFiles: {},  // { fieldName: File }

                // Document fields config
                docFields: [
                    { name: 'spt', label: 'Surat Tugas / SPT (PDF/Gambar)', accept: 'application/pdf,image/*', trackAs: 'spt', required: true, allowCamera: true },
                    { name: 'tiket_pergi', label: 'Tiket Pesawat Pergi', accept: 'application/pdf,image/*', trackAs: 'tiket_pergi', required: false, allowCamera: true },
                    { name: 'tiket_pulang', label: 'Tiket Pesawat Pulang', accept: 'application/pdf,image/*', trackAs: 'tiket_pulang', required: false, allowCamera: true },
                    { name: 'taksi_rumah_ke_bandara', label: 'Taksi Rumah \u2192 Bandara Asal', accept: 'application/pdf,image/*', trackAs: 'taksi_rumah_ke_bandara', required: false, allowCamera: true },
                    { name: 'taksi_bandara_ke_rumah', label: 'Taksi Bandara Asal \u2192 Rumah', accept: 'application/pdf,image/*', trackAs: 'taksi_bandara_ke_rumah', required: false, allowCamera: true },
                    { name: 'taksi_bandara_tujuan_ke_hotel', label: 'Taksi Bandara Tujuan \u2192 Hotel', accept: 'application/pdf,image/*', trackAs: 'taksi_bandara_tujuan_ke_hotel', required: false, allowCamera: true },
                    { name: 'taksi_hotel_ke_bandara_tujuan', label: 'Taksi Hotel \u2192 Bandara Tujuan', accept: 'application/pdf,image/*', trackAs: 'taksi_hotel_ke_bandara_tujuan', required: false, allowCamera: true },
                ],

                async handleFileUpload(event, fieldName) {
                    this.isCompressing = true;
                    if (typeof window.autoCompressFileInput === 'function') {
                        await window.autoCompressFileInput(event, 2, (msg) => {
                            this.compressionNotice = msg;
                        });
                    }
                    this.isCompressing = false;

                    const files = event.target.files;
                    if (fieldName === 'spt') {
                        this.sptUploaded = files.length > 0;
                        if (files.length > 0) {
                            this.processOcrFile(files[0], (text, data) => this.applyOcrData(data));
                        }
                    }
                    if (fieldName === 'tiket_pergi') this.tiketPergiUploaded = files.length > 0;
                    if (fieldName === 'tiket_pulang') this.tiketPulangUploaded = files.length > 0;
                    if (fieldName === 'dokumentasi') this.dokumentasiCount = files.length;
                },

                async startCameraStream() {
                    try {
                        this.cameraStream = await navigator.mediaDevices.getUserMedia({
                            video: { facingMode: 'environment', width: { ideal: 1920 }, height: { ideal: 1080 } },
                            audio: false
                        });
                        this.$refs.cameraVideo.srcObject = this.cameraStream;
                    } catch (e) {
                        this.cameraError = 'Tidak dapat mengakses kamera. Pastikan izin kamera telah diberikan di browser Anda.';
                    }
                },

                async openCamera(docField) {
                    this.cameraError = '';
                    this.cameraTargetField = docField;
                    this.cameraOcrCallbacks = null;
                    this.cameraOpen = true;
                    await this.$nextTick();
                    await this.startCameraStream();
                },

                async openOcrCamera(onSuccess, onStart, onEnd) {
                    this.cameraError = '';
                    this.cameraTargetField = null;
                    this.cameraOcrCallbacks = { onSuccess, onStart, onEnd };
                    this.cameraOpen = true;
                    await this.$nextTick();
                    await this.startCameraStream();
                },

                closeCamera() {
                    if (this.cameraStream) {
                        this.cameraStream.getTracks().forEach(t => t.stop());
                        this.cameraStream = null;
                    }
                    this.cameraOpen = false;
                    this.cameraTargetField = null;
                    this.cameraOcrCallbacks = null;
                    this.cameraError = '';
                },

                snapPhoto() {
                    const video = this.$refs.cameraVideo;
                    const canvas = this.$refs.cameraCanvas;
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0);
                    canvas.toBlob((blob) => {
                        if (!blob) return;
                        const timestamp = new Date().toISOString().replace(/[:.]/g, '-').slice(0, 19);

                        if (this.cameraTargetField) {
                            const field = this.cameraTargetField;
                            const fileName = `foto-${field.name}-${timestamp}.jpg`;
                            const file = new File([blob], fileName, { type: 'image/jpeg' });

                            this.cameraFiles = { ...this.cameraFiles, [field.name]: file };
                            const input = this.$el.querySelector(`input[type="file"][name="${field.name}"]`);
                            if (input) input.value = '';

                            if (field.trackAs === 'spt') this.sptUploaded = true;
                            if (field.trackAs === 'tiket_pergi') this.tiketPergiUploaded = true;
                            if (field.trackAs === 'tiket_pulang') this.tiketPulangUploaded = true;

                            this.closeCamera();
                        } else if (this.cameraOcrCallbacks) {
                            const fileName = `ocr-scan-${timestamp}.jpg`;
                            const file = new File([blob], fileName, { type: 'image/jpeg' });
                            const callbacks = this.cameraOcrCallbacks;
                            this.closeCamera();
                            this.processOcrFile(file, callbacks.onSuccess, callbacks.onStart, callbacks.onEnd);
                        }
                    }, 'image/jpeg', 0.85);
                },

                removeCameraFile(docField) {
                    const copy = { ...this.cameraFiles };
                    delete copy[docField.name];
                    this.cameraFiles = copy;
                    if (docField.trackAs === 'spt') this.sptUploaded = false;
                    if (docField.trackAs === 'tiket_pergi') this.tiketPergiUploaded = false;
                    if (docField.trackAs === 'tiket_pulang') this.tiketPulangUploaded = false;
                },

                clearCameraFile(name) {
                    if (!this.cameraFiles[name]) return;
                    const copy = { ...this.cameraFiles };
                    delete copy[name];
                    this.cameraFiles = copy;
                },

                submitForm(e) {
                    // Block if incomplete
                    if (this.requiredIncompleteCount > 0) {
                        e.preventDefault();
                        this.goToStep(7);
                        this.errorMessage = 'Laporan belum dapat disubmit. Silakan lengkapi seluruh tahapan yang ditandai BELUM LENGKAP.';
                        return;
                    }

                    // If no camera files, let native form submit work
                    const camNames = Object.keys(this.cameraFiles);
                    if (camNames.length === 0) return;

                    // Intercept: build FormData manually with camera files
                    e.preventDefault();
                    const form = e.target;
                    const fd = new FormData(form);

                    // Replace/add camera file entries
                    for (const name of camNames) {
                        const targetName = name === 'dokumentasi' ? 'dokumentasi[]' : name;
                        fd.delete(targetName);
                        fd.append(targetName, this.cameraFiles[name]);
                    }

                    // Submit via fetch then redirect
                    const btn = form.querySelector('button[type="submit"]');
                    if (btn) { btn.disabled = true; btn.textContent = 'Mengirim...'; }

                    fetch(form.action, {
                        method: 'POST',
                        body: fd,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json, text/html'
                        }
                    }).then(async res => {
                        if (!res.ok) {
                            let msg = 'Gagal menyimpan laporan. Mohon periksa kembali isian form.';
                            try {
                                const json = await res.json();
                                if (json.errors) {
                                    msg = Object.values(json.errors).flat().join('<br>');
                                } else if (json.message) {
                                    msg = json.message;
                                }
                            } catch (errJson) {}
                            this.errorMessage = msg;
                            if (btn) { btn.disabled = false; btn.textContent = 'Submit Laporan Dinas'; }
                            return;
                        }
                        if (res.redirected) {
                            window.location.href = res.url;
                        } else {
                            window.location.href = '{{ route("reports.index") }}';
                        }
                    }).catch(err => {
                        this.errorMessage = 'Gagal mengirim laporan: ' + err.message;
                        if (btn) { btn.disabled = false; btn.textContent = 'Submit Laporan Dinas'; }
                    });
                },

                fpInstances: {},
                initDatePicker(el, propName) {
                    const self = this;
                    const fp = flatpickr(el, {
                        dateFormat: 'Y-m-d',
                        altInput: true,
                        altFormat: 'j M Y',
                        altInputClass: el.className,
                        locale: 'id',
                        defaultDate: self[propName] || null,
                        onChange: (selectedDates, dateStr) => {
                            self[propName] = dateStr;
                            if (propName === 'tglSelesai' || propName === 'tglMulai') {
                                self.updatePickerBounds();
                            }
                        }
                    });
                    this.fpInstances[propName] = fp;
                    this.$nextTick(() => this.updatePickerBounds());
                    return fp;
                },
                initScheduleDatePicker(el, schedObj) {
                    return flatpickr(el, {
                        dateFormat: 'Y-m-d',
                        altInput: true,
                        altFormat: 'j M Y',
                        altInputClass: el.className,
                        locale: 'id',
                        defaultDate: schedObj.tanggal || null,
                        onChange: (selectedDates, dateStr) => {
                            schedObj.tanggal = dateStr;
                        }
                    });
                },
                parseDateStrToDateObj(str) {
                    if (!str) return null;
                    str = String(str).trim();
                    if (/^\d{4}-\d{2}-\d{2}/.test(str)) {
                        const parts = str.split('-');
                        return new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
                    }
                    const months = { 'januari':0, 'jan':0, 'februari':1, 'feb':1, 'maret':2, 'mar':2, 'april':3, 'apr':3, 'mei':4, 'juni':5, 'jun':5, 'juli':6, 'jul':6, 'agustus':7, 'agu':7, 'september':8, 'sep':8, 'oktober':9, 'okt':9, 'november':10, 'nov':10, 'desember':11, 'des':11 };
                    const parts = str.split(/\s+/);
                    if (parts.length === 3) {
                        const day = parseInt(parts[0]);
                        const month = months[parts[1].toLowerCase()];
                        const yr = parseInt(parts[2]);
                        if (!isNaN(day) && month !== undefined && !isNaN(yr)) {
                            return new Date(yr, month, day);
                        }
                    }
                    const d = new Date(str);
                    return isNaN(d.getTime()) ? null : d;
                },
                updatePickerBounds() {
                    if (this.fpInstances['tglMulai'] && this.fpInstances['tglSelesai']) {
                        if (this.tglMulai) {
                            this.fpInstances['tglSelesai'].set('minDate', this.tglMulai);
                        }
                    }
                    if (this.tglSelesai) {
                        const dSelesai = this.parseDateStrToDateObj(this.tglSelesai);
                        if (dSelesai) {
                            const minD = new Date(dSelesai);
                            minD.setDate(minD.getDate() + 2); // H+2

                            const maxD = new Date(dSelesai);
                            maxD.setDate(maxD.getDate() + 3); // H+3

                            const formatYmd = (dt) => {
                                const y = dt.getFullYear();
                                const m = String(dt.getMonth() + 1).padStart(2, '0');
                                const d = String(dt.getDate()).padStart(2, '0');
                                return `${y}-${m}-${d}`;
                            };

                            const minDStr = formatYmd(minD);
                            const maxDStr = formatYmd(maxD);

                            // Auto-set default tanggalSppd to H+2
                            if (!this.tanggalSppd) {
                                this.tanggalSppd = minDStr;
                            } else {
                                const curSppd = this.parseDateStrToDateObj(this.tanggalSppd);
                                if (!curSppd || curSppd < minD || curSppd > maxD) {
                                    this.tanggalSppd = minDStr;
                                }
                            }

                            if (this.fpInstances['tanggalSppd']) {
                                if (this.fpInstances['tanggalSppd'].altInput) {
                                    this.fpInstances['tanggalSppd'].altInput.disabled = false;
                                }
                                this.fpInstances['tanggalSppd'].set('minDate', minD);
                                this.fpInstances['tanggalSppd'].set('maxDate', maxD);
                                this.fpInstances['tanggalSppd'].setDate(this.tanggalSppd, true);
                            }
                        }
                    } else if (this.fpInstances['tanggalSppd']) {
                        this.fpInstances['tanggalSppd'].set('minDate', null);
                        this.fpInstances['tanggalSppd'].set('maxDate', null);
                    }
                },
                get minSppdFormatted() {
                    if (!this.tglSelesai) return '';
                    const d = this.parseDateStrToDateObj(this.tglSelesai);
                    if (!d) return '';
                    d.setDate(d.getDate() + 2);
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                    return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
                },
                get maxSppdFormatted() {
                    if (!this.tglSelesai) return '';
                    const d = this.parseDateStrToDateObj(this.tglSelesai);
                    if (!d) return '';
                    d.setDate(d.getDate() + 3);
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                    return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
                },
                schedules: @json(old('schedules', [])).length > 0 
                    ? @json(old('schedules', [])).map((s, idx) => ({ id: Date.now() + idx, tanggal: s.tanggal || '', lokasi: s.lokasi_keterangan || '', uraian: s.uraian_kegiatan || '', isScanning: false })) 
                    : [],
                expenses: [
                    { type: 'Tiket Pesawat Utama (Pergi / Pulang)', amount: 0, payment_method: 'kkp', description: '' },
                    { type: 'Taksi / Transportasi Lokal Bandara', amount: 0, payment_method: 'cash', description: '' }
                ],
                addSchedule() {
                    this.schedules.push({ id: Date.now() + Math.random(), tanggal: '', lokasi: '', uraian: '', isScanning: false });
                },
                removeSchedule(index) {
                    this.schedules.splice(index, 1);
                },
                addExpense() {
                    this.expenses.push({ type: '', amount: 0, payment_method: 'cash', description: '' });
                },
                removeExpense(index) {
                    this.expenses.splice(index, 1);
                },
                totalKkp() {
                    return this.expenses
                        .filter(e => e.payment_method === 'kkp')
                        .reduce((sum, e) => sum + (parseFloat(e.amount) || 0), 0);
                },
                totalCash() {
                    return this.expenses
                        .filter(e => e.payment_method === 'cash')
                        .reduce((sum, e) => sum + (parseFloat(e.amount) || 0), 0);
                },
                totalCost() {
                    return this.totalKkp() + this.totalCash();
                },
                formatRupiah(val) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val);
                },

                // Validation logic per step
                validateStep(stepNum) {
                    this.errorMessage = '';
                    if (stepNum === 1) {
                        if (!this.ppkId) return 'Pejabat Pembuat Komitmen / PPK (Step 1) wajib dipilih.';
                        if (!this.selectedPelaksana || this.selectedPelaksana.length === 0) return 'Minimal 1 Pelaksana Perjalanan Dinas (Step 1) wajib dipilih.';
                    } else if (stepNum === 2) {
                        if (this.schedules.length === 0) return 'Minimal 1 baris uraian kegiatan harian (Step 2) wajib diisi.';
                        for (let i = 0; i < this.schedules.length; i++) {
                            if (!this.schedules[i].tanggal) return `Tanggal pada Kegiatan #${i+1} (Step 2) wajib diisi.`;
                            if (!this.schedules[i].uraian || !this.schedules[i].uraian.trim()) return `Uraian pada Kegiatan #${i+1} (Step 2) wajib diisi.`;
                        }
                    } else if (stepNum === 3) {
                        if (!this.isiPointer || !this.isiPointer.trim()) return 'Peta Proses Bisnis / Narasi Pointer (Step 3) wajib diisi.';
                    } else if (stepNum === 4) {
                        if (!this.sptUploaded) return 'Dokumen Surat Tugas / SPT (Step 4) wajib diunggah.';
                    } else if (stepNum === 5) {
                        if (this.expenses.length === 0) return 'Minimal 1 rincian pengeluaran (Step 5) wajib diisi.';
                        for (let i = 0; i < this.expenses.length; i++) {
                            if (!this.expenses[i].type || !this.expenses[i].type.trim()) return `Jenis / Uraian Biaya pada Pengeluaran #${i+1} (Step 5) wajib diisi.`;
                            if (!this.expenses[i].amount || parseFloat(this.expenses[i].amount) <= 0) return `Nominal Biaya pada Pengeluaran #${i+1} (Step 5) harus lebih dari 0.`;
                            if (!this.expenses[i].payment_method) return `Metode Pembayaran pada Pengeluaran #${i+1} (Step 5) wajib dipilih.`;
                        }
                    } else if (stepNum === 6) {
                        if (this.dokumentasiCount === 0) return 'Dokumentasi Foto Kegiatan (Step 6) wajib diunggah (minimal 1 foto).';
                    }
                    return '';
                },

                nextStep() {
                    let err = this.validateStep(this.step);
                    if (err) {
                        this.errorMessage = err;
                        return;
                    }
                    if (this.step < 7) {
                        this.errorMessage = '';
                        this.step++;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },

                goToStep(targetStep) {
                    if (targetStep > this.step) {
                        for (let s = 1; s < targetStep; s++) {
                            let err = this.validateStep(s);
                            if (err) {
                                this.errorMessage = err;
                                this.step = s;
                                window.scrollTo({ top: 0, behavior: 'smooth' });
                                return;
                            }
                        }
                    }
                    this.errorMessage = '';
                    this.step = targetStep;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                get stepStatus() {
                    return {
                        1: {
                            isValid: !this.validateStep(1),
                            errors: [
                                !this.ppkId && 'Pejabat Pembuat Komitmen (PPK) belum dipilih',
                                (!this.selectedPelaksana || this.selectedPelaksana.length === 0) && 'Pelaksana belum dipilih',
                            ].filter(Boolean)
                        },
                        2: {
                            isValid: !this.validateStep(2),
                            errors: [
                                (this.schedules.length === 0) && 'Jadwal kegiatan harian belum diisi',
                                ...this.schedules.map((s, idx) => {
                                    let errs = [];
                                    if (!s.tanggal) errs.push(`Tanggal pada Kegiatan #${idx+1} belum diisi`);
                                    if (!s.uraian || !s.uraian.trim()) errs.push(`Uraian narasi pada Kegiatan #${idx+1} belum diisi`);
                                    return errs.join(', ');
                                }).filter(Boolean)
                            ].filter(e => e.length > 0)
                        },
                        3: {
                            isValid: !this.validateStep(3),
                            errors: [(!this.isiPointer || !this.isiPointer.trim()) && 'Peta Proses Bisnis / Narasi Pointer belum diisi'].filter(Boolean)
                        },
                        4: {
                            isValid: !this.validateStep(4),
                            errors: [!this.sptUploaded && 'Berkas Surat Tugas (SPT) belum diunggah'].filter(Boolean)
                        },
                        5: {
                            isValid: !this.validateStep(5),
                            errors: [
                                (this.expenses.length === 0) && 'Rincian biaya belum diisi',
                                ...this.expenses.map((e, idx) => {
                                    let errs = [];
                                    if (!e.type || !e.type.trim()) errs.push(`Jenis biaya pada Pengeluaran #${idx+1} belum diisi`);
                                    if (!e.amount || parseFloat(e.amount) <= 0) errs.push(`Nominal biaya pada Pengeluaran #${idx+1} masih Rp 0`);
                                    return errs.join(', ');
                                }).filter(Boolean)
                            ].filter(e => e.length > 0)
                        },
                        6: {
                            isValid: !this.validateStep(6),
                            errors: [this.dokumentasiCount === 0 && 'Foto dokumentasi kegiatan belum diunggah (min. 1 foto)'].filter(Boolean)
                        }
                    }
                },

                get requiredIncompleteCount() {
                    return (!this.stepStatus[1].isValid ? 1 : 0) +
                           (!this.stepStatus[2].isValid ? 1 : 0) +
                           (!this.stepStatus[3].isValid ? 1 : 0) +
                           (!this.stepStatus[4].isValid ? 1 : 0) +
                           (!this.stepStatus[5].isValid ? 1 : 0) +
                           (!this.stepStatus[6].isValid ? 1 : 0);
                },

                get completenessScore() {
                    let total = 6;
                    let valid = 0;
                    if (this.stepStatus[1].isValid) valid++;
                    if (this.stepStatus[2].isValid) valid++;
                    if (this.stepStatus[3].isValid) valid++;
                    if (this.stepStatus[4].isValid) valid++;
                    if (this.stepStatus[5].isValid) valid++;
                    if (this.stepStatus[6].isValid) valid++;
                    return Math.round((valid / total) * 100);
                }
            }
        }
    </script>
</x-app-layout>
