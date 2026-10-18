<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-[#0F2747] leading-tight">
            {{ __('Tambah Pegawai & Akun') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200 p-6">
                <form action="{{ route('admin.pegawai.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="nama" :value="__('Nama Lengkap')" />
                        <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama" :value="old('nama')" required autofocus />
                        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="nip" :value="__('NIP')" />
                        <x-text-input id="nip" class="block mt-1 w-full" type="text" name="nip" :value="old('nip')" required />
                        <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email (untuk Login)')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="jabatan" :value="__('Jabatan')" />
                            <x-text-input id="jabatan" class="block mt-1 w-full" type="text" name="jabatan" :value="old('jabatan')" />
                            <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="pangkat_gol" :value="__('Pangkat / Golongan')" />
                            <x-text-input id="pangkat_gol" class="block mt-1 w-full" type="text" name="pangkat_gol" :value="old('pangkat_gol')" />
                            <x-input-error :messages="$errors->get('pangkat_gol')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="unit_kerja_id" :value="__('Unit Kerja')" />
                            <select id="unit_kerja_id" name="unit_kerja_id" class="block mt-1 w-full border-slate-300 focus:border-[#1769AA] focus:ring-[#1769AA] rounded-md shadow-sm">
                                <option value="">-- Pilih Unit Kerja --</option>
                                @foreach($unitKerjas as $uk)
                                    <option value="{{ $uk->id }}" {{ old('unit_kerja_id') == $uk->id ? 'selected' : '' }}>{{ $uk->nama }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('unit_kerja_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="role" :value="__('Role Akses')" />
                            <select id="role" name="role" class="block mt-1 w-full border-slate-300 focus:border-[#1769AA] focus:ring-[#1769AA] rounded-md shadow-sm" required>
                                <option value="pegawai" {{ old('role') == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="no_hp" :value="__('No. HP / WhatsApp')" />
                        <x-text-input id="no_hp" class="block mt-1 w-full" type="text" name="no_hp" :value="old('no_hp')" />
                        <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4 space-x-2">
                        <a href="{{ route('admin.pegawai.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-800 px-4 py-2 rounded-lg text-sm font-medium transition">Batal</a>
                        <x-primary-button class="bg-[#1769AA] hover:bg-[#0F2747]">
                            {{ __('Simpan Pegawai') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
