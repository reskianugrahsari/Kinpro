<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-[#0F2747] leading-tight">
            {{ __('Buat Pengaduan / Aspirasi Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200 p-6">
                <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    @if(auth()->user()->role === 'admin')
                        <div>
                            <x-input-label for="pegawai_id" :value="__('Buat Atas Nama Pegawai')" />
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
                        <x-input-label for="jenis_laporan" :value="__('Jenis Laporan / Aspirasi')" />
                        <x-text-input id="jenis_laporan" class="block mt-1 w-full" type="text" name="jenis_laporan" :value="old('jenis_laporan')" placeholder="Contoh: Kendala Absensi, Fasilitas Kantor, Pelayanan" required autofocus />
                        <x-input-error :messages="$errors->get('jenis_laporan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tanggal_kejadian" :value="__('Tanggal Kejadian')" />
                        <x-text-input id="tanggal_kejadian" class="block mt-1 w-full" type="date" name="tanggal_kejadian" :value="old('tanggal_kejadian', date('Y-m-d'))" required />
                        <x-input-error :messages="$errors->get('tanggal_kejadian')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="keterangan" :value="__('Keterangan / Rincian Pengaduan')" />
                        <textarea id="keterangan" name="keterangan" rows="5" class="block mt-1 w-full border-slate-300 focus:border-[#1769AA] focus:ring-[#1769AA] rounded-md shadow-sm" required>{{ old('keterangan') }}</textarea>
                        <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="bukti" :value="__('Unggah Bukti Pendukung (Opsional - PDF/JPG/PNG maks 2MB)')" />
                        <input id="bukti" type="file" name="bukti" class="block mt-1 w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#1769AA] file:text-white hover:file:bg-[#0F2747]" />
                        <x-input-error :messages="$errors->get('bukti')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4 space-x-2">
                        <a href="{{ route('pengaduan.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-800 px-4 py-2 rounded-lg text-sm font-medium transition">Batal</a>
                        <x-primary-button class="bg-[#1769AA] hover:bg-[#0F2747]">
                            {{ __('Kirim Pengaduan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
