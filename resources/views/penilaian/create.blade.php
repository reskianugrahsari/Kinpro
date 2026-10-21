<x-app-layout>
    <div class="space-y-6" x-data="{
        selectedPegawaiId: '{{ old('pegawai_id', $pegawais->first()?->id ?? '') }}',
        pegawaiData: @js($pegawais->keyBy('id')),
        selectedBulan: '{{ old('bulan', 'Januari') }}',
        selectedTahun: '{{ old('tahun', date('Y')) }}',
        periodeKinerja: 'Triwulan I',

        dis: {{ old('nilai_kedisiplinan', 85) }},
        kin: {{ old('kinerja', 80) }},
        sik: {{ old('sikap', 85) }},
        kep: {{ old('kepemimpinan', 80) }},
        loy: {{ old('loyalitas', 90) }},
        itVal: {{ old('it', 75) }},

        catatan: `{{ old('masukan_atasan', '') }}`,
        karakterCount() {
            return this.catatan.length;
        },

        get currentPegawai() {
            return this.pegawaiData[this.selectedPegawaiId] || null;
        },

        get average() {
            let total = (parseFloat(this.dis) || 0) +
                        (parseFloat(this.kin) || 0) +
                        (parseFloat(this.sik) || 0) +
                        (parseFloat(this.kep) || 0) +
                        (parseFloat(this.loy) || 0) +
                        (parseFloat(this.itVal) || 0);
            return (total / 6).toFixed(2);
        },

        get predikat() {
            let avg = parseFloat(this.average);
            if (avg >= 90) return 'SANGAT BAIK';
            if (avg >= 76) return 'BAIK';
            if (avg >= 60) return 'CUKUP';
            return 'BUTUH PERBAIKAN';
        },

        get predikatColor() {
            let avg = parseFloat(this.average);
            if (avg >= 90) return 'bg-primary text-white';
            if (avg >= 76) return 'bg-[#10B981] text-white';
            if (avg >= 60) return 'bg-[#F59E0B] text-white';
            return 'bg-error text-white';
        },

        get benchmarkDiff() {
            let diff = (parseFloat(this.average) - 78.00).toFixed(2);
            if (diff >= 0) return `+${diff} Poin di Atas Target`;
            return `${diff} Poin di Bawah Target`;
        },

        getAspectStatus(val) {
            val = parseFloat(val) || 0;
            if (val >= 90) return { label: 'Sangat Baik', color: 'bg-primary/10 text-primary border-primary/20' };
            if (val >= 76) return { label: 'Baik', color: 'bg-[#ECFDF5] text-[#065F46] border-[#A7F3D0]' };
            if (val >= 60) return { label: 'Cukup', color: 'bg-[#FEF3C7] text-[#92400E] border-[#FDE68A]' };
            return { label: 'Kurang', color: 'bg-error-container text-on-error-container border-error/30' };
        },

        appendSaran(text) {
            if (!this.catatan) {
                this.catatan = text;
            } else {
                this.catatan += ' ' + text;
            }
        }
    }">
        <!-- Breadcrumbs & Return Bar -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-xs">
                <a class="text-secondary dark:text-slate-400 hover:text-primary dark:hover:text-blue-400 transition-colors flex items-center gap-1 font-medium" href="{{ route('penilaian.index') }}">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    <span>Kembali ke Daftar Penilaian</span>
                </a>
                <span class="text-outline-variant dark:text-slate-600">/</span>
                <span class="text-secondary dark:text-slate-400">Penilaian Kinerja</span>
                <span class="text-outline-variant dark:text-slate-600">/</span>
                <span class="text-primary dark:text-blue-400 font-semibold">Input Penilaian Baru</span>
            </div>

            <!-- Status Tag Indicator -->
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-[#ECFDF5] dark:bg-emerald-950/40 border border-[#A7F3D0] dark:border-emerald-800/40 text-[#065F46] dark:text-emerald-300 text-xs font-medium">
                    <span class="material-symbols-outlined text-xs">check_circle</span>
                    <span>Formulir Aktif Terverifikasi</span>
                </span>
                <a href="{{ route('rekap.export', 'pdf') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-surface-container-lowest dark:bg-slate-800 border border-outline-variant/60 dark:border-slate-700 text-secondary dark:text-slate-300 hover:text-primary dark:hover:text-blue-400 hover:border-primary text-xs font-medium transition-colors">
                    <span class="material-symbols-outlined text-sm">menu_book</span>
                    <span>Panduan SKP PermenPAN-RB No. 6/2022</span>
                </a>
            </div>
        </div>

        <!-- Hero Header Section -->
        <div class="bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant/30 dark:border-slate-800 rounded-xl p-5 sm:p-6 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-xs transition-colors">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2.5">
                    <span class="px-2.5 py-1 rounded text-[10px] font-bold tracking-wider bg-primary-container text-on-primary">FORMULIR RESMI ASN</span>
                    <span class="text-xs text-secondary dark:text-slate-400 font-mono">KODE: FORM-EVAL-PUPR-08</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-on-surface dark:text-slate-100 tracking-tight">Input Penilaian Kinerja Pegawai</h1>
                <p class="text-xs text-on-surface-variant dark:text-slate-400 max-w-3xl leading-relaxed">
                    Evaluasi berkala 6 pilar kompetensi dan capaian sasaran kinerja ASN Balai Pengembangan Kompetensi Wilayah VIII Makassar sesuai mandat PermenPAN-RB No. 6 Tahun 2022.
                </p>
            </div>

            <!-- Compact Status Indicator Card -->
            <div class="flex-shrink-0 bg-surface-container-low dark:bg-slate-800/80 border-l-4 border-primary-container p-4 rounded-r-lg w-full md:w-64 border border-outline-variant/30 dark:border-slate-700">
                <p class="text-[10px] font-bold text-secondary dark:text-slate-400 uppercase tracking-wider">Periode Berjalan</p>
                <p class="text-sm font-bold text-on-surface dark:text-slate-100 mt-0.5" x-text="periodeKinerja + ' - ' + selectedTahun"></p>
                <div class="mt-2 flex items-center justify-between text-xs text-on-surface-variant dark:text-slate-400">
                    <span>Batas Input:</span>
                    <span class="font-mono font-semibold text-error dark:text-rose-400">5 Bulan Berjalan</span>
                </div>
            </div>
        </div>

        <!-- FORM WRAPPER -->
        <form action="{{ route('penilaian.store') }}" method="POST">
            @csrf

            <!-- DUAL-COLUMN ENTERPRISE FORM GRID -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <!-- =================================================================== -->
                <!-- LEFT COLUMN: PRIMARY INPUT WORKFLOW (8 COLS)                        -->
                <!-- =================================================================== -->
                <div class="lg:col-span-8 space-y-6">
                    <!-- Section 1: Identitas Pegawai & Periode Evaluasi -->
                    <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl border border-outline-variant/30 dark:border-slate-800 p-5 sm:p-6 space-y-5 shadow-xs transition-colors">
                        <div class="flex items-center justify-between border-b border-outline-variant/20 dark:border-slate-800 pb-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded bg-primary/10 dark:bg-blue-900/40 text-primary dark:text-blue-400 flex items-center justify-center font-bold text-sm">
                                    1
                                </div>
                                <div>
                                    <h2 class="text-sm font-bold text-on-surface dark:text-slate-100">Identitas ASN &amp; Periode Evaluasi</h2>
                                    <p class="text-xs text-secondary dark:text-slate-400">Tentukan pegawai yang dievaluasi serta rentang waktu pelaksanaan kinerja</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-[#EFF6FF] dark:bg-blue-950/60 text-primary dark:text-blue-300 border border-primary/20 dark:border-blue-800/40">LANGKAH 1/3</span>
                        </div>

                        <!-- Pegawai Selection Dropdown with Enhanced Preview -->
                        <div class="space-y-2">
                            <label for="pegawai_id" class="block text-xs font-semibold text-on-surface dark:text-slate-200">
                                Pegawai yang Dinilai <span class="text-error dark:text-rose-400">*</span>
                            </label>
                            <div class="relative">
                                <select id="pegawai_id" name="pegawai_id" x-model="selectedPegawaiId" class="w-full bg-surface-container-lowest dark:bg-slate-800 text-on-surface dark:text-slate-100 border border-outline-variant/60 dark:border-slate-700 rounded-lg p-2.5 pr-10 text-xs font-medium focus:outline-none focus:border-primary-container focus:ring-1 focus:ring-primary-container appearance-none transition-colors cursor-pointer" required>
                                    <option value="">-- Pilih Pegawai yang Dinilai --</option>
                                    @foreach($pegawais as $p)
                                        <option value="{{ $p->id }}" {{ old('pegawai_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama }} — NIP. {{ $p->nip }} ({{ $p->jabatan ?? ($p->unitKerja->nama_unit ?? 'Pegawai Balai') }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-secondary dark:text-slate-400">
                                    <span class="material-symbols-outlined text-lg">expand_more</span>
                                </div>
                            </div>
                            @if(isset($errors) && $errors->has('pegawai_id'))
                                <p class="mt-1 text-xs text-error dark:text-rose-400">{{ $errors->first('pegawai_id') }}</p>
                            @endif

                            <!-- Selected ASN Live Badge Card -->
                            <div class="mt-3 p-3.5 bg-surface dark:bg-slate-800/60 rounded-lg border border-outline-variant/30 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3" x-show="currentPegawai">
                                <div class="flex items-center gap-3">
                                    <div class="relative shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-secondary-container dark:bg-slate-700 text-on-secondary-fixed dark:text-slate-200 flex items-center justify-center font-bold text-sm overflow-hidden border border-outline-variant/50">
                                            <span class="material-symbols-outlined text-xl">person</span>
                                        </div>
                                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-[#10B981] ring-2 ring-white dark:ring-slate-800" title="Status Kepegawaian Aktif"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <p class="text-xs font-bold text-on-surface dark:text-slate-100 truncate" x-text="currentPegawai ? currentPegawai.nama : ''"></p>
                                            <span class="px-1.5 py-0.2 rounded text-[10px] font-semibold bg-[#ECFDF5] text-[#065F46] border border-[#A7F3D0] dark:bg-emerald-950/40 dark:text-emerald-300">AKTIF</span>
                                        </div>
                                        <p class="text-[11px] font-mono text-secondary dark:text-slate-400 truncate leading-tight" x-text="currentPegawai ? 'NIP. ' + currentPegawai.nip : ''"></p>
                                        <p class="text-[10px] text-on-surface-variant dark:text-slate-400 truncate" x-text="currentPegawai ? 'Jabatan: ' + (currentPegawai.jabatan || 'Aparatur Sipil Negara') : ''"></p>
                                    </div>
                                </div>
                                <div class="text-left sm:text-right shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-outline-variant/20 dark:border-slate-700">
                                    <span class="text-[10px] font-bold text-secondary dark:text-slate-400 uppercase block tracking-wider">Instansi</span>
                                    <p class="text-xs font-medium text-on-surface dark:text-slate-200 truncate max-w-xs" x-text="currentPegawai && currentPegawai.unit_kerja ? currentPegawai.unit_kerja.nama_unit : 'Bapekom Wil. VIII Makassar'"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Periode & Jenis Form Selector -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Bulan Dropdown -->
                            <div class="space-y-1.5">
                                <label for="bulan" class="block text-xs font-semibold text-on-surface dark:text-slate-200">Bulan Evaluasi</label>
                                <div class="relative">
                                    <select id="bulan" name="bulan" x-model="selectedBulan" class="w-full bg-surface-container-lowest dark:bg-slate-800 text-on-surface dark:text-slate-100 border border-outline-variant/60 dark:border-slate-700 rounded-lg p-2.5 pr-8 text-xs focus:outline-none focus:border-primary-container appearance-none cursor-pointer" required>
                                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $b)
                                            <option value="{{ $b }}" {{ old('bulan', 'Januari') == $b ? 'selected' : '' }}>{{ $b }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-secondary dark:text-slate-400">
                                        <span class="material-symbols-outlined text-sm">expand_more</span>
                                    </div>
                                </div>
                                @if(isset($errors) && $errors->has('bulan'))
                                    <p class="mt-1 text-xs text-error dark:text-rose-400">{{ $errors->first('bulan') }}</p>
                                @endif
                            </div>

                            <!-- Tahun Evaluasi -->
                            <div class="space-y-1.5">
                                <label for="tahun" class="block text-xs font-semibold text-on-surface dark:text-slate-200">Tahun Anggaran</label>
                                <input id="tahun" name="tahun" x-model="selectedTahun" class="w-full bg-surface-container-lowest dark:bg-slate-800 text-on-surface dark:text-slate-100 border border-outline-variant/60 dark:border-slate-700 rounded-lg p-2.5 font-mono text-xs focus:outline-none focus:border-primary-container" type="number" min="2000" max="2100" required />
                                @if(isset($errors) && $errors->has('tahun'))
                                    <p class="mt-1 text-xs text-error dark:text-rose-400">{{ $errors->first('tahun') }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Pill Selector Jenis Evaluasi -->
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-on-surface dark:text-slate-200">Rentang Periode Kinerja</label>
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2">
                                @foreach(['Triwulan I', 'Triwulan II', 'Triwulan III', 'Triwulan IV', 'Tahunan SKP'] as $pk)
                                    <button type="button" @click="periodeKinerja = '{{ $pk }}'"
                                        class="py-1.5 px-2.5 text-center text-xs rounded border transition-colors cursor-pointer"
                                        :class="periodeKinerja === '{{ $pk }}' ? 'font-semibold bg-primary text-on-primary border-primary' : 'font-medium border-outline-variant/60 dark:border-slate-700 bg-surface-container-lowest dark:bg-slate-800 text-secondary dark:text-slate-300 hover:bg-surface-container dark:hover:bg-slate-700'">
                                        {{ $pk }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Penilaian 6 Aspek Kompetensi (0-100) -->
                    <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl border border-outline-variant/30 dark:border-slate-800 p-5 sm:p-6 space-y-5 shadow-xs transition-colors">
                        <div class="flex flex-wrap items-center justify-between border-b border-outline-variant/20 dark:border-slate-800 pb-3.5 gap-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded bg-primary/10 dark:bg-blue-900/40 text-primary dark:text-blue-400 flex items-center justify-center font-bold text-sm">
                                    2
                                </div>
                                <div>
                                    <h2 class="text-sm font-bold text-on-surface dark:text-slate-100">Penilaian 6 Pilar Aspek Kompetensi</h2>
                                    <p class="text-xs text-secondary dark:text-slate-400">Skala baku penilaian instansi pemerintah: 0 - 100 poin (Ambang batas balai: 78.00)</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-secondary dark:text-slate-400">
                                    <span class="w-2 h-2 rounded-full bg-[#10B981]"></span> Baik (76-89)
                                </span>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-secondary dark:text-slate-400 ml-2">
                                    <span class="w-2 h-2 rounded-full bg-primary"></span> Sangat Baik (90+)
                                </span>
                            </div>
                        </div>

                        <!-- Grid 6 Mini Interactive Indicator Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                            <!-- Aspek 1: Kedisiplinan -->
                            <div class="p-3.5 rounded-lg border border-outline-variant/40 dark:border-slate-700 bg-surface/50 dark:bg-slate-800/40 hover:border-primary transition-all space-y-2.5">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <span class="text-[10px] font-bold text-primary dark:text-blue-400 uppercase tracking-wider">Aspek 1</span>
                                        <h3 class="text-xs font-bold text-on-surface dark:text-slate-100">Kedisiplinan</h3>
                                        <p class="text-[11px] text-secondary dark:text-slate-400">Presensi, ketepatan jam kerja &amp; kepatuhan apel</p>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border" :class="getAspectStatus(dis).color" x-text="getAspectStatus(dis).label"></span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input type="range" min="0" max="100" x-model="dis" class="w-full accent-primary cursor-pointer">
                                    <div class="w-16 shrink-0">
                                        <input type="number" min="0" max="100" name="nilai_kedisiplinan" x-model="dis" class="w-full text-center font-mono font-bold text-xs py-1 px-1 bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant dark:border-slate-700 rounded text-on-surface dark:text-slate-100 focus:border-primary focus:outline-none" required>
                                    </div>
                                </div>
                                <div class="flex justify-between text-[10px] font-mono text-outline dark:text-slate-500">
                                    <span>Min: 0</span>
                                    <span class="text-primary dark:text-blue-400 font-bold">Ambang Balai: 78</span>
                                    <span>Maks: 100</span>
                                </div>
                            </div>

                            <!-- Aspek 2: Kinerja -->
                            <div class="p-3.5 rounded-lg border border-outline-variant/40 dark:border-slate-700 bg-surface/50 dark:bg-slate-800/40 hover:border-primary transition-all space-y-2.5">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <span class="text-[10px] font-bold text-primary dark:text-blue-400 uppercase tracking-wider">Aspek 2</span>
                                        <h3 class="text-xs font-bold text-on-surface dark:text-slate-100">Kinerja</h3>
                                        <p class="text-[11px] text-secondary dark:text-slate-400">Target output SKP &amp; kualitas hasil fisik/keuangan</p>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border" :class="getAspectStatus(kin).color" x-text="getAspectStatus(kin).label"></span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input type="range" min="0" max="100" x-model="kin" class="w-full accent-primary cursor-pointer">
                                    <div class="w-16 shrink-0">
                                        <input type="number" min="0" max="100" name="kinerja" x-model="kin" class="w-full text-center font-mono font-bold text-xs py-1 px-1 bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant dark:border-slate-700 rounded text-on-surface dark:text-slate-100 focus:border-primary focus:outline-none" required>
                                    </div>
                                </div>
                                <div class="flex justify-between text-[10px] font-mono text-outline dark:text-slate-500">
                                    <span>Min: 0</span>
                                    <span class="text-primary dark:text-blue-400 font-bold">Ambang Balai: 78</span>
                                    <span>Maks: 100</span>
                                </div>
                            </div>

                            <!-- Aspek 3: Sikap Perilaku -->
                            <div class="p-3.5 rounded-lg border border-outline-variant/40 dark:border-slate-700 bg-surface/50 dark:bg-slate-800/40 hover:border-primary transition-all space-y-2.5">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <span class="text-[10px] font-bold text-primary dark:text-blue-400 uppercase tracking-wider">Aspek 3</span>
                                        <h3 class="text-xs font-bold text-on-surface dark:text-slate-100">Sikap Perilaku</h3>
                                        <p class="text-[11px] text-secondary dark:text-slate-400">Implementasi BerAKHLAK &amp; Core Values PUPR</p>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border" :class="getAspectStatus(sik).color" x-text="getAspectStatus(sik).label"></span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input type="range" min="0" max="100" x-model="sik" class="w-full accent-primary cursor-pointer">
                                    <div class="w-16 shrink-0">
                                        <input type="number" min="0" max="100" name="sikap" x-model="sik" class="w-full text-center font-mono font-bold text-xs py-1 px-1 bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant dark:border-slate-700 rounded text-on-surface dark:text-slate-100 focus:border-primary focus:outline-none" required>
                                    </div>
                                </div>
                                <div class="flex justify-between text-[10px] font-mono text-outline dark:text-slate-500">
                                    <span>Min: 0</span>
                                    <span class="text-primary dark:text-blue-400 font-bold">Ambang Balai: 78</span>
                                    <span>Maks: 100</span>
                                </div>
                            </div>

                            <!-- Aspek 4: Kepemimpinan -->
                            <div class="p-3.5 rounded-lg border border-outline-variant/40 dark:border-slate-700 bg-surface/50 dark:bg-slate-800/40 hover:border-primary transition-all space-y-2.5">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <span class="text-[10px] font-bold text-primary dark:text-blue-400 uppercase tracking-wider">Aspek 4</span>
                                        <h3 class="text-xs font-bold text-on-surface dark:text-slate-100">Kepemimpinan</h3>
                                        <p class="text-[11px] text-secondary dark:text-slate-400">Inisiatif kerja tim &amp; problem solving unit kerja</p>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border" :class="getAspectStatus(kep).color" x-text="getAspectStatus(kep).label"></span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input type="range" min="0" max="100" x-model="kep" class="w-full accent-primary cursor-pointer">
                                    <div class="w-16 shrink-0">
                                        <input type="number" min="0" max="100" name="kepemimpinan" x-model="kep" class="w-full text-center font-mono font-bold text-xs py-1 px-1 bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant dark:border-slate-700 rounded text-on-surface dark:text-slate-100 focus:border-primary focus:outline-none" required>
                                    </div>
                                </div>
                                <div class="flex justify-between text-[10px] font-mono text-outline dark:text-slate-500">
                                    <span>Min: 0</span>
                                    <span class="text-primary dark:text-blue-400 font-bold">Ambang Balai: 78</span>
                                    <span>Maks: 100</span>
                                </div>
                            </div>

                            <!-- Aspek 5: Loyalitas -->
                            <div class="p-3.5 rounded-lg border border-outline-variant/40 dark:border-slate-700 bg-surface/50 dark:bg-slate-800/40 hover:border-primary transition-all space-y-2.5">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <span class="text-[10px] font-bold text-primary dark:text-blue-400 uppercase tracking-wider">Aspek 5</span>
                                        <h3 class="text-xs font-bold text-on-surface dark:text-slate-100">Loyalitas</h3>
                                        <p class="text-[11px] text-secondary dark:text-slate-400">Kepatuhan perintah kedinasan &amp; integritas balai</p>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border" :class="getAspectStatus(loy).color" x-text="getAspectStatus(loy).label"></span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input type="range" min="0" max="100" x-model="loy" class="w-full accent-primary cursor-pointer">
                                    <div class="w-16 shrink-0">
                                        <input type="number" min="0" max="100" name="loyalitas" x-model="loy" class="w-full text-center font-mono font-bold text-xs py-1 px-1 bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant dark:border-slate-700 rounded text-on-surface dark:text-slate-100 focus:border-primary focus:outline-none" required>
                                    </div>
                                </div>
                                <div class="flex justify-between text-[10px] font-mono text-outline dark:text-slate-500">
                                    <span>Min: 0</span>
                                    <span class="text-primary dark:text-blue-400 font-bold">Ambang Balai: 78</span>
                                    <span>Maks: 100</span>
                                </div>
                            </div>

                            <!-- Aspek 6: Penguasaan IT -->
                            <div class="p-3.5 rounded-lg border border-outline-variant/40 dark:border-slate-700 bg-surface/50 dark:bg-slate-800/40 hover:border-primary transition-all space-y-2.5">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <span class="text-[10px] font-bold text-primary dark:text-blue-400 uppercase tracking-wider">Aspek 6</span>
                                        <h3 class="text-xs font-bold text-on-surface dark:text-slate-100">Penguasaan IT</h3>
                                        <p class="text-[11px] text-secondary dark:text-slate-400">Literasi aplikasi e-Kinerja PUPR &amp; sistem digital</p>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border" :class="getAspectStatus(itVal).color" x-text="getAspectStatus(itVal).label"></span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input type="range" min="0" max="100" x-model="itVal" class="w-full accent-primary cursor-pointer">
                                    <div class="w-16 shrink-0">
                                        <input type="number" min="0" max="100" name="it" x-model="itVal" class="w-full text-center font-mono font-bold text-xs py-1 px-1 bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant dark:border-slate-700 rounded text-on-surface dark:text-slate-100 focus:border-primary focus:outline-none" required>
                                    </div>
                                </div>
                                <div class="flex justify-between text-[10px] font-mono text-outline dark:text-slate-500">
                                    <span>Min: 0</span>
                                    <span class="text-[#F59E0B] font-bold">Ambang Balai: 78</span>
                                    <span>Maks: 100</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Masukan Atasan & Bukti Dukung -->
                    <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl border border-outline-variant/30 dark:border-slate-800 p-5 sm:p-6 space-y-4 shadow-xs transition-colors">
                        <div class="flex items-center gap-3 border-b border-outline-variant/20 dark:border-slate-800 pb-3.5">
                            <div class="w-8 h-8 rounded bg-primary/10 dark:bg-blue-900/40 text-primary dark:text-blue-400 flex items-center justify-center font-bold text-sm">
                                3
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-on-surface dark:text-slate-100">Masukan, Catatan Atasan &amp; Rekomendasi</h2>
                                <p class="text-xs text-secondary dark:text-slate-400">Rekomendasi pengembangan kompetensi ASN serta rencana tindak lanjut</p>
                            </div>
                        </div>

                        <!-- Catatan Atasan Langsung Textarea -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label for="masukan_atasan" class="block text-xs font-semibold text-on-surface dark:text-slate-200">
                                    Catatan Masukan / Rekomendasi Atasan Langsung
                                </label>
                                <span class="text-[11px] text-outline dark:text-slate-500 font-mono" x-text="'Karakter: ' + karakterCount() + '/500'"></span>
                            </div>

                            <!-- Quick Tags Template -->
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="text-[11px] text-secondary dark:text-slate-400 font-medium">Saran Cepat:</span>
                                <button type="button" @click="appendSaran('Kinerja memuaskan sesuai target.')" class="px-2 py-0.5 rounded bg-surface dark:bg-slate-800 border border-outline-variant/60 dark:border-slate-700 text-secondary dark:text-slate-300 hover:border-primary hover:text-primary text-[11px] transition-colors cursor-pointer">
                                    + Kinerja memuaskan sesuai target
                                </button>
                                <button type="button" @click="appendSaran('Perlu percepatan literasi aplikasi e-Kinerja.')" class="px-2 py-0.5 rounded bg-surface dark:bg-slate-800 border border-outline-variant/60 dark:border-slate-700 text-secondary dark:text-slate-300 hover:border-primary hover:text-primary text-[11px] transition-colors cursor-pointer">
                                    + Perlu percepatan literasi digital
                                </button>
                                <button type="button" @click="appendSaran('Disiplin kehadiran patut dipertahankan.')" class="px-2 py-0.5 rounded bg-surface dark:bg-slate-800 border border-outline-variant/60 dark:border-slate-700 text-secondary dark:text-slate-300 hover:border-primary hover:text-primary text-[11px] transition-colors cursor-pointer">
                                    + Disiplin patut dipertahankan
                                </button>
                            </div>

                            <textarea id="masukan_atasan" name="masukan_atasan" x-model="catatan" rows="4" class="w-full bg-surface-container-lowest dark:bg-slate-800 text-on-surface dark:text-slate-100 border border-outline-variant/60 dark:border-slate-700 rounded-lg p-3 text-xs focus:outline-none focus:border-primary-container focus:ring-1 focus:ring-primary-container transition-colors" placeholder="Tuliskan masukan konkret mengenai capaian kinerja, integritas kerja, serta rencana perbaikan pada triwulan berikutnya..."></textarea>
                            @if(isset($errors) && $errors->has('masukan_atasan'))
                                <p class="mt-1 text-xs text-error dark:text-rose-400">{{ $errors->first('masukan_atasan') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Section 4: Konfirmasi & Action Footer -->
                    <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl border border-outline-variant/30 dark:border-slate-800 p-5 sm:p-6 space-y-4 shadow-xs transition-colors">
                        <div class="flex items-start gap-2.5">
                            <input id="integrity-check" type="checkbox" checked class="mt-0.5 w-4 h-4 text-primary rounded border-outline-variant dark:border-slate-700 focus:ring-primary cursor-pointer" required>
                            <label for="integrity-check" class="text-xs text-on-surface dark:text-slate-300 cursor-pointer select-none leading-relaxed">
                                <span class="font-bold">Pakta Integritas Penilaian:</span> Saya menyatakan bahwa seluruh nilai aspek kompetensi dan catatan evaluasi yang diisikan telah sesuai fakta capaian kinerja riil ASN yang bersangkutan dan dapat dipertanggungjawabkan kepada Tim Penilai Bapekom PU Wilayah VIII Makassar.
                            </label>
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-3 pt-3.5 border-t border-outline-variant/20 dark:border-slate-800">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('penilaian.index') }}" class="px-4 py-2 rounded-lg border border-outline-variant/60 dark:border-slate-700 text-secondary dark:text-slate-300 hover:bg-surface-container dark:hover:bg-slate-800 text-xs font-semibold transition-colors cursor-pointer">
                                    Batal
                                </a>
                            </div>

                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary text-on-primary hover:bg-[#002b8a] text-xs font-bold transition-all shadow-sm cursor-pointer">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                <span>SIMPAN &amp; PUBLIKASI PENILAIAN</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- =================================================================== -->
                <!-- RIGHT COLUMN: REALTIME CALCULATOR & ASSISTANT PANEL (4 COLS)        -->
                <!-- =================================================================== -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
                    <!-- Card 1: Live Aggregate Score Widget -->
                    <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl border border-outline-variant/30 dark:border-slate-800 p-5 sm:p-6 space-y-5 shadow-xs transition-colors">
                        <div class="flex items-center justify-between border-b border-outline-variant/20 dark:border-slate-800 pb-3">
                            <h3 class="text-sm font-bold text-on-surface dark:text-slate-100">Kalkulasi Otomatis</h3>
                            <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-[#ECFDF5] text-[#065F46] border border-[#A7F3D0] dark:bg-emerald-950/40 dark:text-emerald-300">LIVE SYNC</span>
                        </div>

                        <!-- Score Circular Radial Display -->
                        <div class="flex flex-col items-center justify-center p-4 bg-surface dark:bg-slate-800/50 rounded-lg border border-outline-variant/20 dark:border-slate-700 text-center">
                            <span class="text-[10px] font-bold text-secondary dark:text-slate-400 uppercase tracking-wider">SKOR RATA-RATA KOMPETENSI</span>
                            <div class="my-2.5 flex items-baseline justify-center gap-1">
                                <span class="text-4xl sm:text-5xl leading-none font-extrabold text-primary dark:text-blue-400 tracking-tight font-mono" x-text="average"></span>
                                <span class="text-xs font-mono text-outline dark:text-slate-500">/ 100</span>
                            </div>

                            <!-- Predikat Badge Box -->
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold tracking-wide transition-colors" :class="predikatColor">
                                <span class="material-symbols-outlined text-sm">verified</span>
                                <span x-text="'PREDIKAT: ' + predikat"></span>
                            </div>
                            <p class="text-xs text-on-surface-variant dark:text-slate-400 mt-2">
                                Standar Evaluasi ASN Wilayah VIII Makassar
                            </p>
                        </div>

                        <!-- Benchmark Comparison Bar -->
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-xs">
                                <span class="text-secondary dark:text-slate-400">Status Ambang Batas:</span>
                                <span class="font-bold font-mono" :class="parseFloat(average) >= 78.0 ? 'text-[#10B981]' : 'text-error'" x-text="benchmarkDiff"></span>
                            </div>
                            <div class="w-full bg-[#E2E8F0] dark:bg-slate-800 h-2.5 rounded-full overflow-hidden relative">
                                <!-- Target Line Marker (78%) -->
                                <div class="absolute top-0 bottom-0 left-[78%] w-0.5 bg-slate-900 dark:bg-white z-10" title="Target Standar Balai: 78.00"></div>
                                <!-- Actual Value Bar -->
                                <div class="bg-primary dark:bg-blue-500 h-full rounded-full transition-all duration-300" :style="'width: ' + Math.min(100, Math.max(0, average)) + '%'"></div>
                            </div>
                            <div class="flex justify-between text-[10px] font-mono text-secondary dark:text-slate-400">
                                <span>0.00</span>
                                <span class="font-bold text-on-surface dark:text-slate-200">Target Balai: 78.00</span>
                                <span>100.00</span>
                            </div>
                        </div>

                        <!-- Breakdown Mini Horizontal Bars per Aspect -->
                        <div class="space-y-2.5 pt-3 border-t border-outline-variant/20 dark:border-slate-800">
                            <p class="text-[10px] font-bold text-secondary dark:text-slate-400 uppercase tracking-wider">Distribusi 6 Aspek</p>

                            <!-- Aspect 1 -->
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs">
                                    <span class="text-on-surface dark:text-slate-300">1. Kedisiplinan</span>
                                    <span class="font-mono font-bold text-on-surface dark:text-slate-200" x-text="dis"></span>
                                </div>
                                <div class="w-full bg-[#E2E8F0] dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-primary dark:bg-blue-500 h-full transition-all" :style="'width: ' + dis + '%'"></div>
                                </div>
                            </div>

                            <!-- Aspect 2 -->
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs">
                                    <span class="text-on-surface dark:text-slate-300">2. Kinerja</span>
                                    <span class="font-mono font-bold text-on-surface dark:text-slate-200" x-text="kin"></span>
                                </div>
                                <div class="w-full bg-[#E2E8F0] dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-primary dark:bg-blue-500 h-full transition-all" :style="'width: ' + kin + '%'"></div>
                                </div>
                            </div>

                            <!-- Aspect 3 -->
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs">
                                    <span class="text-on-surface dark:text-slate-300">3. Sikap Perilaku</span>
                                    <span class="font-mono font-bold text-on-surface dark:text-slate-200" x-text="sik"></span>
                                </div>
                                <div class="w-full bg-[#E2E8F0] dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-primary dark:bg-blue-500 h-full transition-all" :style="'width: ' + sik + '%'"></div>
                                </div>
                            </div>

                            <!-- Aspect 4 -->
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs">
                                    <span class="text-on-surface dark:text-slate-300">4. Kepemimpinan</span>
                                    <span class="font-mono font-bold text-on-surface dark:text-slate-200" x-text="kep"></span>
                                </div>
                                <div class="w-full bg-[#E2E8F0] dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-primary dark:bg-blue-500 h-full transition-all" :style="'width: ' + kep + '%'"></div>
                                </div>
                            </div>

                            <!-- Aspect 5 -->
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs">
                                    <span class="text-on-surface dark:text-slate-300">5. Loyalitas</span>
                                    <span class="font-mono font-bold text-on-surface dark:text-slate-200" x-text="loy"></span>
                                </div>
                                <div class="w-full bg-[#E2E8F0] dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-primary dark:bg-blue-500 h-full transition-all" :style="'width: ' + loy + '%'"></div>
                                </div>
                            </div>

                            <!-- Aspect 6 -->
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs">
                                    <span class="text-on-surface dark:text-slate-300">6. Penguasaan IT</span>
                                    <span class="font-mono font-bold text-on-surface dark:text-slate-200" x-text="itVal"></span>
                                </div>
                                <div class="w-full bg-[#E2E8F0] dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-[#F59E0B] h-full transition-all" :style="'width: ' + itVal + '%'"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Aturan Regulasi Singkat -->
                    <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl border border-outline-variant/30 dark:border-slate-800 p-5 space-y-3 shadow-xs transition-colors">
                        <div class="flex items-center gap-2 text-primary dark:text-blue-400">
                            <span class="material-symbols-outlined text-lg">verified_user</span>
                            <h4 class="text-xs font-bold text-on-surface dark:text-slate-100 uppercase tracking-wider">Ketentuan SKP PermenPAN</h4>
                        </div>
                        <p class="text-xs text-secondary dark:text-slate-400 leading-relaxed">
                            Penilaian bersifat final setelah dikonfirmasi atasan langsung. Ambang batas kelulusan standar kompetensi ASN Bapekom VIII adalah <strong>78.00 poin</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
