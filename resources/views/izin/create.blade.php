<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-[#0F2747] leading-tight">
            {{ __('Ajukan Izin atau Cuti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200 p-6">
                <form action="{{ route('izin.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    @if(auth()->user()->role === 'admin')
                        <div>
                            <x-input-label for="pegawai_id" :value="__('Ajukan Untuk Pegawai')" />
                            <select id="pegawai_id" name="pegawai_id" class="block mt-1 w-full border-slate-300 focus:border-[#1769AA] focus:ring-[#1769AA] rounded-md shadow-sm" required autofocus>
                                <option value="">-- Pilih Pegawai --</option>
                                @foreach(\App\Models\Pegawai::all() as $p)
                                    <option value="{{ $p->id }}" {{ old('pegawai_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }} (NIP. {{ $p->nip }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('pegawai_id')" class="mt-2" />
                        </div>
                    @endif

                    <div>
                        <x-input-label for="jenis_izin" :value="__('Jenis Izin / Cuti')" />
                        <select id="jenis_izin" name="jenis_izin" class="block mt-1 w-full border-slate-300 focus:border-[#1769AA] focus:ring-[#1769AA] rounded-md shadow-sm" required autofocus>
                            <option value="cuti_tahunan" {{ old('jenis_izin') == 'cuti_tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                            <option value="sakit" {{ old('jenis_izin') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="izin_khusus" {{ old('jenis_izin') == 'izin_khusus' ? 'selected' : '' }}>Izin Khusus</option>
                            <option value="dinas_luar" {{ old('jenis_izin') == 'dinas_luar' ? 'selected' : '' }}>Dinas Luar</option>
                        </select>
                        <x-input-error :messages="$errors->get('jenis_izin')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="tanggal_mulai" :value="__('Tanggal Mulai')" />
                            <x-text-input id="tanggal_mulai" class="block mt-1 w-full" type="date" name="tanggal_mulai" :value="old('tanggal_mulai')" required />
                            <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="tanggal_selesai" :value="__('Tanggal Selesai')" />
                            <x-text-input id="tanggal_selesai" class="block mt-1 w-full" type="date" name="tanggal_selesai" :value="old('tanggal_selesai')" required />
                            <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="keterangan" :value="__('Keterangan / Alasan')" />
                        <textarea id="keterangan" name="keterangan" rows="4" class="block mt-1 w-full border-slate-300 focus:border-[#1769AA] focus:ring-[#1769AA] rounded-md shadow-sm" required>{{ old('keterangan') }}</textarea>
                        <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="dokumen" :value="__('Unggah Berkas Pendukung / Surat (Opsional - PDF/JPG/PNG maks 2MB)')" />
                        <input id="dokumen" type="file" name="dokumen" class="block mt-1 w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#1769AA] file:text-white hover:file:bg-[#0F2747]" />
                        <x-input-error :messages="$errors->get('dokumen')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4 space-x-2">
                        <a href="{{ route('izin.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-800 px-4 py-2 rounded-lg text-sm font-medium transition">Batal</a>
                        <x-primary-button class="bg-[#1769AA] hover:bg-[#0F2747]">
                            {{ __('Kirim Pengajuan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
