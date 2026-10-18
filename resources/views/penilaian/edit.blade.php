<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-[#0F2747] leading-tight">
            {{ __('Edit Penilaian Kinerja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200 p-6">
                <form action="{{ route('penilaian.update', $penilaian) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-input-label for="pegawai_id" :value="__('Pegawai')" />
                        <select id="pegawai_id" name="pegawai_id" class="block mt-1 w-full border-slate-300 focus:border-[#1769AA] focus:ring-[#1769AA] rounded-md shadow-sm" required autofocus>
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach($pegawais as $p)
                                <option value="{{ $p->id }}" {{ old('pegawai_id', $penilaian->pegawai_id) == $p->id ? 'selected' : '' }}>{{ $p->nama }} (NIP. {{ $p->nip }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('pegawai_id')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="bulan" :value="__('Bulan')" />
                            <select id="bulan" name="bulan" class="block mt-1 w-full border-slate-300 focus:border-[#1769AA] focus:ring-[#1769AA] rounded-md shadow-sm" required>
                                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $b)
                                    <option value="{{ $b }}" {{ old('bulan', $penilaian->bulan) == $b ? 'selected' : '' }}>{{ $b }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('bulan')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="tahun" :value="__('Tahun')" />
                            <x-text-input id="tahun" class="block mt-1 w-full" type="number" name="tahun" :value="old('tahun', $penilaian->tahun)" required />
                            <x-input-error :messages="$errors->get('tahun')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="nilai_kedisiplinan" :value="__('Kedisiplinan (0-100)')" />
                            <x-text-input id="nilai_kedisiplinan" class="block mt-1 w-full" type="number" min="0" max="100" name="nilai_kedisiplinan" :value="old('nilai_kedisiplinan', $penilaian->nilai_kedisiplinan)" required />
                        </div>
                        <div>
                            <x-input-label for="kinerja" :value="__('Kinerja (0-100)')" />
                            <x-text-input id="kinerja" class="block mt-1 w-full" type="number" min="0" max="100" name="kinerja" :value="old('kinerja', $penilaian->kinerja)" required />
                        </div>
                        <div>
                            <x-input-label for="sikap" :value="__('Sikap (0-100)')" />
                            <x-text-input id="sikap" class="block mt-1 w-full" type="number" min="0" max="100" name="sikap" :value="old('sikap', $penilaian->sikap)" required />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="kepemimpinan" :value="__('Kepemimpinan (0-100)')" />
                            <x-text-input id="kepemimpinan" class="block mt-1 w-full" type="number" min="0" max="100" name="kepemimpinan" :value="old('kepemimpinan', $penilaian->kepemimpinan)" required />
                        </div>
                        <div>
                            <x-input-label for="loyalitas" :value="__('Loyalitas (0-100)')" />
                            <x-text-input id="loyalitas" class="block mt-1 w-full" type="number" min="0" max="100" name="loyalitas" :value="old('loyalitas', $penilaian->loyalitas)" required />
                        </div>
                        <div>
                            <x-input-label for="it" :value="__('Penguasaan IT (0-100)')" />
                            <x-text-input id="it" class="block mt-1 w-full" type="number" min="0" max="100" name="it" :value="old('it', $penilaian->it)" required />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="masukan_atasan" :value="__('Masukan / Catatan Atasan')" />
                        <textarea id="masukan_atasan" name="masukan_atasan" rows="3" class="block mt-1 w-full border-slate-300 focus:border-[#1769AA] focus:ring-[#1769AA] rounded-md shadow-sm">{{ old('masukan_atasan', $penilaian->masukan_atasan) }}</textarea>
                        <x-input-error :messages="$errors->get('masukan_atasan')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4 space-x-2">
                        <a href="{{ route('penilaian.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-800 px-4 py-2 rounded-lg text-sm font-medium transition">Batal</a>
                        <x-primary-button class="bg-[#1769AA] hover:bg-[#0F2747]">
                            {{ __('Perbarui Penilaian') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
