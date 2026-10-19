<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-[#0F2747] leading-tight">
            {{ __('Edit Data Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200 p-6">
                <form action="{{ route('admin.pegawai.update', $pegawai) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-input-label for="nama" :value="__('Nama Lengkap')" />
                        <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama" :value="old('nama', $pegawai->nama)" required autofocus />
                        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="nip" :value="__('NIP')" />
                        <x-text-input id="nip" class="block mt-1 w-full" type="text" name="nip" :value="old('nip', $pegawai->nip)" required />
                        <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="jabatan" :value="__('Jabatan')" />
                            <x-text-input id="jabatan" class="block mt-1 w-full" type="text" name="jabatan" :value="old('jabatan', $pegawai->jabatan)" />
                            <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="pangkat_gol" :value="__('Pangkat / Golongan')" />
                            <x-text-input id="pangkat_gol" class="block mt-1 w-full" type="text" name="pangkat_gol" :value="old('pangkat_gol', $pegawai->pangkat_gol)" />
                            <x-input-error :messages="$errors->get('pangkat_gol')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="unit_kerja_id" :value="__('Unit Kerja')" />
                        <select id="unit_kerja_id" name="unit_kerja_id" class="block mt-1 w-full border-slate-300 focus:border-[#1769AA] focus:ring-[#1769AA] rounded-md shadow-sm">
                            <option value="">-- Pilih Unit Kerja --</option>
                            @foreach($unitKerjas as $uk)
                                <option value="{{ $uk->id }}" {{ old('unit_kerja_id', $pegawai->unit_kerja_id) == $uk->id ? 'selected' : '' }}>{{ $uk->nama }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('unit_kerja_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="no_hp" :value="__('No. HP / WhatsApp')" />
                        <x-text-input id="no_hp" class="block mt-1 w-full" type="text" name="no_hp" :value="old('no_hp', $pegawai->no_hp)" />
                        <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="foto" :value="__('Foto Pegawai (JPG, PNG, WebP max 3MB)')" />
                        <div class="mt-2 flex items-center space-x-4">
                            @if($pegawai->foto_url)
                                <img src="{{ $pegawai->foto_url }}" alt="{{ $pegawai->nama }}" class="w-16 h-16 rounded-xl object-cover border border-slate-300 shadow-xs">
                            @endif
                            <input id="foto" name="foto" type="file" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#1769AA] hover:file:bg-blue-100 cursor-pointer" />
                        </div>
                        <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4 space-x-2">
                        <a href="{{ route('admin.pegawai.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-800 px-4 py-2 rounded-lg text-sm font-medium transition">Batal</a>
                        <x-primary-button class="bg-[#1769AA] hover:bg-[#0F2747]">
                            {{ __('Perbarui Pegawai') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
