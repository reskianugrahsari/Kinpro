<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-[#0F2747] leading-tight">
            {{ __('Tambah Unit Kerja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200 p-6">
                <form action="{{ route('admin.unit-kerja.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="kode" :value="__('Kode Unit Kerja')" />
                        <x-text-input id="kode" class="block mt-1 w-full" type="text" name="kode" :value="old('kode')" required autofocus />
                        <x-input-error :messages="$errors->get('kode')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="nama" :value="__('Nama Unit Kerja')" />
                        <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama" :value="old('nama')" required />
                        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4 space-x-2">
                        <a href="{{ route('admin.unit-kerja.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-800 px-4 py-2 rounded-lg text-sm font-medium transition">Batal</a>
                        <x-primary-button class="bg-[#1769AA] hover:bg-[#0F2747]">
                            {{ __('Simpan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
