<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-2xl text-[#0F2747] tracking-tight">
                {{ __('Formulir Pengaduan & Aspirasi') }}
            </h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Sampaikan laporan kendala kerja, sarana prasarana, atau aspirasi pegawai</p>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">

        @if(session('error'))
            <div class="p-4 bg-rose-50 border-l-4 border-rose-500 rounded-xl text-rose-800 text-xs font-medium shadow-xs">
                {{ session('error') }}
            </div>
        @endif

        <!-- Card Single Utuh Proporsional -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs p-6 sm:p-8 space-y-6">
            
            <!-- Ringkasan Identitas Pelapor -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-slate-50 dark:bg-slate-800/80 rounded-xl border border-slate-200/80 dark:border-slate-700">
                <div class="flex items-center space-x-3.5">
                    <div class="w-10 h-10 rounded-xl bg-[#0F2747] dark:bg-slate-700 text-white font-extrabold flex items-center justify-center text-xs shadow-xs shrink-0">
                        {{ strtoupper(substr($pegawai->nama ?? Auth::user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900 dark:text-slate-100 leading-tight">
                            {{ $pegawai->nama ?? Auth::user()->name }}
                        </div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                            NIP. <span class="font-mono">{{ $pegawai->nip ?? '-' }}</span> • {{ $pegawai->jabatan ?? 'Pegawai' }}
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <span class="text-[11px] bg-blue-50 dark:bg-blue-950/70 text-[#1769AA] dark:text-blue-300 font-bold px-3 py-1 rounded-full border border-blue-200 dark:border-blue-800">
                        Layanan Mandiri
                    </span>
                    <a href="{{ route('pengaduan.index') }}" class="px-3.5 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl shadow-xs transition">
                        &larr; Kembali
                    </a>
                </div>
            </div>

            <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 pt-2">
                @csrf

                <!-- Jenis Laporan / Kategori Cepat -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                        Jenis Laporan / Kategori <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <select id="kategori_select" onchange="if(this.value !== 'lainnya') { document.getElementById('jenis_laporan').value = this.value; } else { document.getElementById('jenis_laporan').value = ''; document.getElementById('jenis_laporan').focus(); }" class="w-full border border-slate-300 dark:border-slate-700 dark:bg-slate-800/90 dark:text-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-[#1769AA] focus:border-[#1769AA] outline-none">
                                <option value="">-- Pilih Kategori Umum --</option>
                                <option value="Kendala Mesin / Sistem Absensi">Kendala Mesin / Sistem Absensi</option>
                                <option value="Sarana & Prasarana Kantor">Sarana & Prasarana Kantor</option>
                                <option value="Jaringan Internet & IT">Jaringan Internet & IT</option>
                                <option value="Pelayanan Administrasi & Kepegawaian">Pelayanan Administrasi & Kepegawaian</option>
                                <option value="Aspirasi & Usulan Peningkatan Kinerja">Aspirasi & Usulan Peningkatan Kinerja</option>
                                <option value="lainnya">Lainnya (Ketik Manual)</option>
                            </select>
                        </div>
                        <div>
                            <input type="text" id="jenis_laporan" name="jenis_laporan" value="{{ old('jenis_laporan') }}" placeholder="Atau ketik judul kategori laporan..." class="w-full border border-slate-300 dark:border-slate-700 dark:bg-slate-800/90 dark:text-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-[#1769AA] focus:border-[#1769AA] outline-none" required>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('jenis_laporan')" class="mt-1" />
                </div>

                <!-- Tanggal Kejadian -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        Tanggal Kejadian / Temuan <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="tanggal_kejadian" value="{{ old('tanggal_kejadian', date('Y-m-d')) }}" class="w-full border border-slate-300 dark:border-slate-700 dark:bg-slate-800/90 dark:text-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-[#1769AA] focus:border-[#1769AA] outline-none" required>
                    <x-input-error :messages="$errors->get('tanggal_kejadian')" class="mt-1" />
                </div>

                <!-- Rincian Pengaduan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        Rincian Pengaduan / Uraian Masalah <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="keterangan" rows="4" class="w-full border border-slate-300 dark:border-slate-700 dark:bg-slate-800/90 dark:text-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-[#1769AA] focus:border-[#1769AA] outline-none" placeholder="Uraikan kendala, lokasi kejadian, atau kronologi secara spesifik dan jelas..." required>{{ old('keterangan') }}</textarea>
                    <x-input-error :messages="$errors->get('keterangan')" class="mt-1" />
                </div>

                <!-- Upload Bukti Pendukung -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        Unggah Bukti Pendukung <span class="text-slate-400 dark:text-slate-500 font-normal lowercase">(opsional - foto kendala/dokumen, maks 2MB)</span>
                    </label>
                    <input type="file" name="bukti" accept=".pdf,.jpg,.jpeg,.png" class="w-full border border-slate-300 dark:border-slate-700 dark:bg-slate-800/90 dark:text-slate-300 rounded-xl px-4 py-2 text-xs text-slate-600 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#1769AA] file:text-white hover:file:bg-[#0F2747] cursor-pointer">
                    <x-input-error :messages="$errors->get('bukti')" class="mt-1" />
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end space-x-3">
                    <a href="{{ route('pengaduan.index') }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#1769AA] hover:bg-[#0F2747] text-white text-xs font-bold rounded-xl shadow-xs transition cursor-pointer">
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
