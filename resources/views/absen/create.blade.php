<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-[#0F2747] tracking-tight">
                    Pengajuan Lupa Absen (Don't Forget)
                </h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Layanan administrasi permohonan absen & surat dinas Balai Wilayah VIII Makassar</p>
            </div>
            <div>
                <a href="{{ route('absen.index') }}" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-xl text-xs font-bold transition shadow-xs flex items-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Daftar Riwayat Absen</span>
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Style for Print -->
    <style>
        @media print {
            body {
                background: white !important;
                color: black !important;
                font-family: Arial, Tahoma, sans-serif !important;
            }
            aside, header, footer, .no-print {
                display: none !important;
            }
            main {
                padding: 0 !important;
                background: white !important;
            }
            .print-area {
                display: block !important;
                width: 210mm;
                min-height: 297mm;
                padding: 10mm 15mm 15mm 15mm;
                margin: 0 auto;
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>

    <div x-data="dontForgetApp()" class="space-y-6">
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-xl text-emerald-800 shadow-sm flex items-center gap-3 no-print">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium text-xs">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-rose-50 border-l-4 border-rose-500 rounded-xl text-rose-800 shadow-sm flex items-center gap-3 no-print">
                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-medium text-xs">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start no-print">
            <!-- Form Input (Don't Forget Layout) -->
            <div class="lg:col-span-5 bg-white p-6 sm:p-7 rounded-2xl border border-slate-200/80 shadow-xs space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-[#0F2747]">Formulir Surat Permohonan</h3>
                    <p class="text-[11px] text-slate-400">Pilih identitas Anda dan isi rincian lupa absen</p>
                </div>

                <form action="{{ route('absen.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Pilih Pegawai -->
                    <div class="mb-4">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Nama Pegawai</label>
                        <select name="pegawai_id" class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 bg-slate-50 text-xs font-medium focus:ring-[#1769AA] focus:border-[#1769AA]" x-model="selectedPegawaiId" @change="updatePegawaiData()" required>
                            <option value="">-- Pilih Pegawai --</option>
                            <template x-for="item in pegawaiList" :key="item.id">
                                <option :value="item.id" x-text="item.nama + ' (NIP. ' + item.nip + ')'"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Auto-Fill Readonly Fields -->
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">NIP</label>
                            <input type="text" class="w-full border border-slate-200 rounded-xl px-3 py-2 bg-slate-100 text-slate-600 font-mono text-xs" x-model="pegawaiData.nip" readonly>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Pangkat / Golongan</label>
                            <input type="text" class="w-full border border-slate-200 rounded-xl px-3 py-2 bg-slate-100 text-slate-600 text-xs" x-model="pegawaiData.pangkat_gol" readonly>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Jabatan</label>
                            <input type="text" class="w-full border border-slate-200 rounded-xl px-3 py-2 bg-slate-100 text-slate-600 text-xs truncate" x-model="pegawaiData.jabatan" readonly>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Unit Kerja / Bagian</label>
                            <input type="text" class="w-full border border-slate-200 rounded-xl px-3 py-2 bg-slate-100 text-slate-600 text-xs truncate" x-model="pegawaiData.bagian" readonly>
                        </div>
                    </div>

                    <!-- Atasan Penandatangan -->
                    <div class="mb-4">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Atasan Penandatangan</label>
                        <select name="atasan_id" class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 bg-slate-50 text-xs font-medium focus:ring-[#1769AA] focus:border-[#1769AA]" x-model="selectedAtasanId" @change="updateAtasanData()" required>
                            <option value="">-- Pilih Atasan --</option>
                            <template x-for="item in pegawaiList" :key="item.id">
                                <option :value="item.id" x-text="item.nama + ' (' + item.jabatan + ')'"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Jenis Lupa Absen -->
                    <div class="mb-4">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Jenis Lupa Absen</label>
                        <select name="jenis_absen" class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 bg-slate-50 text-xs font-medium focus:ring-[#1769AA] focus:border-[#1769AA]" x-model="jenisAbsen" required>
                            <option value="tidak mengisi absensi masuk">Tidak Mengisi Absensi Masuk (Datang)</option>
                            <option value="tidak mengisi absensi pulang">Tidak Mengisi Absensi Pulang</option>
                            <option value="tidak mengisi absensi masuk dan pulang">Tidak Mengisi Absensi Masuk dan Pulang</option>
                        </select>
                    </div>

                    <!-- Alasan -->
                    <div class="mb-4">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Alasan Lupa Absen</label>
                        <textarea name="alasan" class="w-full border border-slate-300 rounded-xl px-3.5 py-2 bg-slate-50 text-xs font-medium focus:ring-[#1769AA] focus:border-[#1769AA]" rows="2.5" x-model="alasan" placeholder="Contoh: Terburu-buru menghadiri rapat / sistem fingerprint error / mati lampu" required></textarea>
                    </div>

                    <!-- Tanggal Lupa & Tanggal Pengajuan (Auto H+1 Kerja) -->
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Tanggal Lupa</label>
                            <input type="date" name="tanggal_lupa" class="w-full border border-slate-300 rounded-xl px-3 py-2 bg-slate-50 text-xs font-medium focus:ring-[#1769AA] focus:border-[#1769AA]" x-model="tanggalLupa" @input="updateHariLupa()" required>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Tanggal Surat</label>
                            <input type="date" name="tanggal_pengajuan" class="w-full border border-slate-300 rounded-xl px-3 py-2 bg-slate-50 text-xs font-medium focus:ring-[#1769AA] focus:border-[#1769AA]" x-model="tanggalPengajuan" required>
                        </div>
                    </div>

                    <!-- Kota Surat -->
                    <div class="mb-6">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Kota Surat</label>
                        <input type="text" name="kota_surat" class="w-full border border-slate-300 rounded-xl px-3.5 py-2 bg-slate-50 text-xs font-medium focus:ring-[#1769AA] focus:border-[#1769AA]" x-model="kotaSurat" required>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <button type="submit" class="flex-1 bg-[#1769AA] hover:bg-[#0F2747] text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-sm cursor-pointer">
                            Simpan Permohonan
                        </button>
                        <button type="button" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-xs cursor-pointer flex items-center justify-center space-x-1.5" @click="window.print()">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Cetak Langsung (PDF)</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Live Preview Naskah Dinas Format A4 (Don't Forget Realtime Preview) -->
            <div class="lg:col-span-7 bg-slate-200/90 p-4 sm:p-6 rounded-2xl flex justify-center overflow-auto shadow-inner border border-slate-300/60">
                <div class="bg-white shadow-xl border border-slate-200 pt-2 px-10 pb-12 text-slate-900 w-[210mm] min-h-[297mm] flex flex-col justify-between rounded-md transform scale-[0.92] origin-top">
                    <div>
                        <!-- Kop Surat Resmi Balai Diklat Wil. VIII -->
                        <div class="mb-4 -mt-2">
                            <img src="{{ asset('images/kop_surat.jpg') }}" alt="Kop Surat Resmi" class="w-full h-auto object-contain">
                        </div>

                        <!-- Judul Surat Naskah Dinas -->
                        <div class="text-center font-bold text-base mb-6 uppercase underline decoration-2 tracking-wide">
                            Surat Permohonan Izin / Pemberitahuan
                        </div>

                        <!-- Pembuka -->
                        <div class="mb-4 text-xs leading-relaxed">
                            Yang bertanda tangan di bawah ini:
                        </div>

                        <!-- Identitas Pemohon -->
                        <div class="grid grid-cols-[140px_15px_1fr] gap-y-1.5 mb-6 ml-6 text-xs">
                            <div class="text-slate-600 font-medium">Nama</div><div>:</div><div class="font-bold" x-text="pegawaiData.nama || '-'"></div>
                            <div class="text-slate-600 font-medium">NIP</div><div>:</div><div class="font-mono" x-text="pegawaiData.nip || '-'"></div>
                            <div class="text-slate-600 font-medium">Pangkat/Golongan</div><div>:</div><div x-text="pegawaiData.pangkat_gol || '-'"></div>
                            <div class="text-slate-600 font-medium">Jabatan</div><div>:</div><div x-text="pegawaiData.jabatan || '-'"></div>
                            <div class="text-slate-600 font-medium">Bagian</div><div>:</div><div x-text="pegawaiData.bagian || '-'"></div>
                        </div>

                        <!-- Pernyataan / Isi Surat -->
                        <div class="leading-relaxed mb-6 text-xs text-justify">
                            Dengan ini menyampaikan bahwa saya <span class="font-bold underline" x-text="jenisAbsen"></span> pada hari <span class="font-bold" x-text="hariLupa || '-'"></span>, tanggal <span class="font-bold" x-text="formatDateIndo(tanggalLupa) || '-'"></span> dikarenakan <span class="font-semibold italic" x-text="alasan || '...'"></span>.
                        </div>

                        <!-- Penutup -->
                        <div class="leading-relaxed mb-8 text-xs">
                            Demikian permohonan/pemberitahuan ini saya sampaikan, atas perhatian Bapak/Ibu diucapkan terima kasih.
                        </div>
                    </div>

                    <!-- Tanda Tangan 2 Kolom -->
                    <div class="text-xs">
                        <div class="text-right mb-8" x-text="(kotaSurat || 'Makassar') + ', ' + (formatDateIndo(tanggalPengajuan) || '-')"></div>
                        <div class="grid grid-cols-2 gap-8 text-center">
                            <div>
                                <div class="mb-20 text-slate-700">Pegawai Pemohon,</div>
                                <div class="font-bold underline" x-text="pegawaiData.nama || 'Nama Pemohon'"></div>
                                <div class="text-[11px] text-slate-600 font-mono mt-0.5" x-text="pegawaiData.nip ? 'NIP. ' + pegawaiData.nip : ''"></div>
                            </div>
                            <div>
                                <div class="mb-20 text-slate-700">Atasan Langsung,</div>
                                <div class="font-bold underline" x-text="atasanData.nama || 'Nama Atasan'"></div>
                                <div class="text-[11px] text-slate-600 font-mono mt-0.5" x-text="atasanData.nip ? 'NIP. ' + atasanData.nip : ''"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Print-Only Page (Muncul Otomatis saat Cetak Browser A4) -->
        <div class="hidden print:block print-area mx-auto bg-white text-black text-sm">
            <div class="mb-4 -mt-2">
                <img src="{{ asset('images/kop_surat.jpg') }}" alt="Kop Surat" class="w-full h-auto">
            </div>

            <div class="text-center font-bold text-lg mb-8 uppercase underline decoration-2">
                Surat Permohonan Izin / Pemberitahuan
            </div>

            <div class="mb-5 leading-relaxed text-sm">
                Yang bertanda tangan di bawah ini:
            </div>

            <div class="grid grid-cols-[160px_15px_1fr] gap-y-2 mb-8 ml-8 text-sm">
                <div>Nama</div><div>:</div><div class="font-bold" x-text="pegawaiData.nama || '-'"></div>
                <div>NIP</div><div>:</div><div x-text="pegawaiData.nip || '-'"></div>
                <div>Pangkat/Golongan</div><div>:</div><div x-text="pegawaiData.pangkat_gol || '-'"></div>
                <div>Jabatan</div><div>:</div><div x-text="pegawaiData.jabatan || '-'"></div>
                <div>Bagian</div><div>:</div><div x-text="pegawaiData.bagian || '-'"></div>
            </div>

            <div class="leading-relaxed mb-6 text-sm text-justify">
                Dengan ini menyampaikan bahwa saya <span class="font-bold underline" x-text="jenisAbsen"></span> pada hari <span class="font-bold" x-text="hariLupa || '-'"></span>, tanggal <span class="font-bold" x-text="formatDateIndo(tanggalLupa) || '-'"></span> dikarenakan <span class="font-semibold" x-text="alasan || '...'"></span>.
            </div>

            <div class="leading-relaxed mb-12 text-sm">
                Demikian permohonan/pemberitahuan ini saya sampaikan, atas perhatian Bapak/Ibu diucapkan terima kasih.
            </div>

            <div class="mt-16 text-sm">
                <div class="text-right mb-12" x-text="(kotaSurat || 'Makassar') + ', ' + (formatDateIndo(tanggalPengajuan) || '-')"></div>
                <div class="grid grid-cols-2 gap-10 text-center">
                    <div>
                        <div class="mb-24">Pegawai Pemohon,</div>
                        <div class="font-bold underline" x-text="pegawaiData.nama || 'Nama Pemohon'"></div>
                        <div x-text="pegawaiData.nip ? 'NIP. ' + pegawaiData.nip : ''"></div>
                    </div>
                    <div>
                        <div class="mb-24">Atasan Langsung,</div>
                        <div class="font-bold underline" x-text="atasanData.nama || 'Nama Atasan'"></div>
                        <div x-text="atasanData.nip ? 'NIP. ' + atasanData.nip : ''"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Controller -->
    <script>
        function dontForgetApp() {
            return {
                pegawaiList: @json($pegawais),
                selectedPegawaiId: '{{ $userPegawai?->id ?? '' }}',
                selectedAtasanId: '',
                jenisAbsen: 'tidak mengisi absensi masuk',
                alasan: '',
                tanggalLupa: '',
                tanggalPengajuan: new Date().toISOString().split('T')[0],
                kotaSurat: 'Makassar',
                hariLupa: '',
                pegawaiData: {
                    nama: '',
                    nip: '',
                    pangkat_gol: '',
                    jabatan: '',
                    bagian: ''
                },
                atasanData: {
                    nama: '',
                    nip: ''
                },
                init() {
                    if (this.selectedPegawaiId) {
                        this.updatePegawaiData();
                    }
                },
                updatePegawaiData() {
                    const peg = this.pegawaiList.find(p => p.id == this.selectedPegawaiId);
                    if (peg) {
                        this.pegawaiData = {
                            nama: peg.nama,
                            nip: peg.nip,
                            pangkat_gol: peg.pangkat_gol,
                            jabatan: peg.jabatan,
                            bagian: peg.bagian
                        };
                        if (peg.atasan_id) {
                            this.selectedAtasanId = peg.atasan_id;
                            this.updateAtasanData();
                        } else {
                            // Default atasan rekomendasi: Kepala Subbag atau Kepala Balai
                            const defaultAtasan = this.pegawaiList.find(p => p.nama.includes('Wahyuni') || p.jabatan.includes('Subbag') || p.jabatan.includes('Kepala Balai'));
                            if (defaultAtasan) {
                                this.selectedAtasanId = defaultAtasan.id;
                                this.updateAtasanData();
                            }
                        }
                    } else {
                        this.pegawaiData = { nama: '', nip: '', pangkat_gol: '', jabatan: '', bagian: '' };
                        this.selectedAtasanId = '';
                        this.atasanData = { nama: '', nip: '' };
                    }
                },
                updateAtasanData() {
                    const atasan = this.pegawaiList.find(p => p.id == this.selectedAtasanId);
                    if (atasan) {
                        this.atasanData = {
                            nama: atasan.nama,
                            nip: atasan.nip
                        };
                    } else {
                        this.atasanData = { nama: '', nip: '' };
                    }
                },
                updateHariLupa() {
                    if (!this.tanggalLupa) {
                        this.hariLupa = '';
                        return;
                    }
                    const date = new Date(this.tanggalLupa);
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    this.hariLupa = days[date.getDay()];

                    // Hitung tanggal pengajuan otomatis H+1 hari kerja (sesuai Don't Forget)
                    let nextDate = new Date(date);
                    if (date.getDay() === 5) { // Jumat -> Senin (H+3)
                        nextDate.setDate(date.getDate() + 3);
                    } else if (date.getDay() === 6) { // Sabtu -> Senin (H+2)
                        nextDate.setDate(date.getDate() + 2);
                    } else {
                        nextDate.setDate(date.getDate() + 1);
                    }
                    this.tanggalPengajuan = nextDate.toISOString().split('T')[0];
                },
                formatDateIndo(dateStr) {
                    if (!dateStr) return '';
                    const date = new Date(dateStr);
                    const months = [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ];
                    return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
                }
            };
        }
    </script>
</x-app-layout>
