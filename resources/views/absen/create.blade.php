<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-[#0F2747] leading-tight">
            {{ __('Form Pengajuan Lupa Absen / Don\'t Forget') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ selectedPegawai: '', unitKerja: '', jabatan: '' }">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200 p-6">
                <form action="{{ route('absen.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    @if(auth()->user()->role === 'admin')
                        <div>
                            <x-input-label for="pegawai_id" :value="__('Pegawai')" />
                            <select id="pegawai_id" name="pegawai_id" x-model="selectedPegawai" @change="
                                let opt = $el.selectedOptions[0];
                                unitKerja = opt.dataset.unit || '';
                                jabatan = opt.dataset.jabatan || '';
                            " class="block mt-1 w-full border-slate-300 focus:border-[#1769AA] focus:ring-[#1769AA] rounded-md shadow-sm" required autofocus>
                                <option value="">-- Pilih Pegawai --</option>
                                @foreach($pegawais as $p)
                                    <option value="{{ $p->id }}" data-unit="{{ $p->unitKerja->nama ?? '-' }}" data-jabatan="{{ $p->jabatan ?? 'Pegawai' }}" {{ old('pegawai_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }} (NIP. {{ $p->nip }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('pegawai_id')" class="mt-2" />
                        </div>
                    @endif

                    <!-- Auto-filled Info Preview -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 grid grid-cols-2 gap-4 text-xs">
                        <div>
                            <span class="font-bold text-slate-500 uppercase block">Unit Kerja:</span>
                            <span class="text-[#0F2747] font-semibold text-sm" x-text="unitKerja || '{{ auth()->user()->role !== 'admin' ? (auth()->user()->pegawai->unitKerja->nama ?? '-') : '-' }}'">-</span>
                        </div>
                        <div>
                            <span class="font-bold text-slate-500 uppercase block">Jabatan / Atasan:</span>
                            <span class="text-[#0F2747] font-semibold text-sm" x-text="jabatan || '{{ auth()->user()->role !== 'admin' ? (auth()->user()->pegawai->jabatan ?? 'Pegawai') : '-' }}'">-</span>
                        </div>
                    </div>

                    <div>
                        <x-input-label for="tanggal_absen" :value="__('Tanggal Lupa Absen')" />
                        <x-text-input id="tanggal_absen" class="block mt-1 w-full" type="date" name="tanggal_absen" :value="old('tanggal_absen', date('Y-m-d'))" required />
                        <x-input-error :messages="$errors->get('tanggal_absen')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="alasan" :value="__('Alasan Lupa Absen')" />
                        <select id="alasan" name="alasan" class="block mt-1 w-full border-slate-300 focus:border-[#1769AA] focus:ring-[#1769AA] rounded-md shadow-sm" required>
                            <option value="lupa_check_in">Lupa Check-In (Masuk)</option>
                            <option value="lupa_check_out">Lupa Check-Out (Pulang)</option>
                            <option value="kendala_sistem">Kendala Sistem / Jaringan</option>
                            <option value="tugas_luar_tanpa_surat">Tugas Luar Tanpa Surat Perintah</option>
                        </select>
                        <x-input-error :messages="$errors->get('alasan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="keterangan" :value="__('Keterangan / Penjelasan Rinci')" />
                        <textarea id="keterangan" name="keterangan" rows="4" class="block mt-1 w-full border-slate-300 focus:border-[#1769AA] focus:ring-[#1769AA] rounded-md shadow-sm" required>{{ old('keterangan') }}</textarea>
                        <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="bukti_path" :value="__('Unggah Bukti / Foto Pendukung (Opsional - PDF/JPG/PNG maks 2MB)')" />
                        <input id="bukti_path" type="file" name="bukti_path" class="block mt-1 w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#1769AA] file:text-white hover:file:bg-[#0F2747]" />
                        <x-input-error :messages="$errors->get('bukti_path')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4 space-x-2">
                        <a href="{{ route('absen.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-800 px-4 py-2 rounded-lg text-sm font-medium transition">Batal</a>
                        <x-primary-button class="bg-[#1769AA] hover:bg-[#0F2747]">
                            {{ __('Kirim Pengajuan Lupa Absen') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
