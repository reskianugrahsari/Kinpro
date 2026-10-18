<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-[#0F2747] leading-tight">
            {{ __('Buat Laporan Perjalanan Dinas / Pajokka (SPPD)') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        expenses: [
            { uraian: 'Uang Harian & Transport', nominal: 1500000, metode_pembayaran: 'KKP' }
        ],
        addExpense() {
            this.expenses.push({ uraian: '', nominal: 0, metode_pembayaran: 'Cash' });
        },
        removeExpense(index) {
            this.expenses.splice(index, 1);
        }
    }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200 p-6">
                <form action="{{ route('pajokka.store') }}" method="POST" class="space-y-6">
                    @csrf

                    @if(auth()->user()->role === 'admin')
                        <div>
                            <x-input-label for="pegawai_id" :value="__('Pegawai Pelaksana')" />
                            <select id="pegawai_id" name="pegawai_id" class="block mt-1 w-full border-slate-300 focus:border-[#1769AA] focus:ring-[#1769AA] rounded-md shadow-sm" required autofocus>
                                <option value="">-- Pilih Pegawai --</option>
                                @foreach($pegawais as $p)
                                    <option value="{{ $p->id }}" {{ old('pegawai_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }} (NIP. {{ $p->nip }}) - {{ $p->unitKerja->nama ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('pegawai_id')" class="mt-2" />
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="nomor_surat" :value="__('Nomor Surat Tugas / SPPD')" />
                            <x-text-input id="nomor_surat" class="block mt-1 w-full" type="text" name="nomor_surat" :value="old('nomor_surat', 'ST/' . date('Y') . '/001')" required />
                            <x-input-error :messages="$errors->get('nomor_surat')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="master_ppk_id" :value="__('Pejabat Pembuat Komitmen (PPK)')" />
                            <select id="master_ppk_id" name="master_ppk_id" class="block mt-1 w-full border-slate-300 focus:border-[#1769AA] focus:ring-[#1769AA] rounded-md shadow-sm" required>
                                <option value="">-- Pilih PPK --</option>
                                @foreach($ppks as $ppk)
                                    <option value="{{ $ppk->id }}" {{ old('master_ppk_id') == $ppk->id ? 'selected' : '' }}>
                                        {{ $ppk->nama }} (NIP. {{ $ppk->nip }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('master_ppk_id')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="tanggal_berangkat" :value="__('Tanggal Berangkat')" />
                            <x-text-input id="tanggal_berangkat" class="block mt-1 w-full" type="date" name="tanggal_berangkat" :value="old('tanggal_berangkat', date('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('tanggal_berangkat')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tanggal_kembali" :value="__('Tanggal Kembali')" />
                            <x-text-input id="tanggal_kembali" class="block mt-1 w-full" type="date" name="tanggal_kembali" :value="old('tanggal_kembali', date('Y-m-d', strtotime('+2 days')))" required />
                            <x-input-error :messages="$errors->get('tanggal_kembali')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="tujuan" :value="__('Kota Tujuan Perjalanan Dinas')" />
                        <x-text-input id="tujuan" class="block mt-1 w-full" type="text" name="tujuan" :value="old('tujuan', 'Bandung, Jawa Barat')" required />
                        <x-input-error :messages="$errors->get('tujuan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="maksud_perjalanan" :value="__('Maksud Perjalanan Dinas / Kegiatan')" />
                        <textarea id="maksud_perjalanan" name="maksud_perjalanan" rows="3" class="block mt-1 w-full border-slate-300 focus:border-[#1769AA] focus:ring-[#1769AA] rounded-md shadow-sm" required>{{ old('maksud_perjalanan', 'Koordinasi dan monitoring pelaksanaan pekerjaan fisik infrastruktur.') }}</textarea>
                        <x-input-error :messages="$errors->get('maksud_perjalanan')" class="mt-2" />
                    </div>

                    <!-- Per-item Expense Payment Method (KKP & Cash per item) -->
                    <div class="border-t border-slate-200 pt-4">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-bold text-base text-[#0F2747]">Rincian Pembiayaan & Metode Pembayaran (KKP / Cash)</h3>
                            <button type="button" @click="addExpense()" class="bg-[#1769AA] text-white px-3 py-1 rounded text-xs font-bold">+ Tambah Item Biaya</button>
                        </div>
                        <p class="text-xs text-slate-500 mb-3">Setiap item pengeluaran dapat dikombinasikan antara Kartu Kredit Pemerintah (KKP) dan Cash sesuai kebutuhan.</p>

                        <div class="space-y-3">
                            <template x-for="(exp, index) in expenses" :key="index">
                                <div class="flex items-center space-x-2 bg-slate-50 p-3 rounded-lg border border-slate-200">
                                    <div class="flex-1">
                                        <input type="text" :name="`expenses[${index}][uraian]`" x-model="exp.uraian" placeholder="Uraian (Contoh: Tiket Pesawat / Uang Harian)" class="w-full border-slate-300 rounded-md text-sm" required>
                                    </div>
                                    <div class="w-36">
                                        <input type="number" :name="`expenses[${index}][nominal]`" x-model="exp.nominal" placeholder="Nominal (Rp)" class="w-full border-slate-300 rounded-md text-sm" required>
                                    </div>
                                    <div class="w-36">
                                        <select :name="`expenses[${index}][metode_pembayaran]`" x-model="exp.metode_pembayaran" class="w-full border-slate-300 rounded-md text-sm" required>
                                            <option value="KKP">KKP</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Keduanya">Keduanya</option>
                                        </select>
                                    </div>
                                    <button type="button" @click="removeExpense(index)" class="text-rose-600 hover:text-rose-800 font-bold px-2 py-1 text-sm" x-show="expenses.length > 1">&times;</button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6 space-x-2">
                        <a href="{{ route('pajokka.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-800 px-4 py-2 rounded-lg text-sm font-medium transition">Batal</a>
                        <x-primary-button class="bg-[#1769AA] hover:bg-[#0F2747]">
                            {{ __('Simpan Perjalanan Dinas') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
