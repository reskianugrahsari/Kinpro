<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-extrabold text-2xl text-[#0F2747] tracking-tight">
                    {{ auth()->user()->role === 'admin' ? __('Review Laporan Perjalanan Dinas (Pajokka)') : __('Modul Perjalanan Dinas / Pajokka (SPPD)') }}
                </h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Sistem pengelolaan laporan pertanggungjawaban dinas, OCR surat tugas & ekspor dokumen</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-xl text-emerald-800 text-xs font-medium shadow-xs">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-rose-50 border-l-4 border-rose-500 rounded-xl text-rose-800 text-xs font-medium shadow-xs">
                    {{ session('error') }}
                </div>
            @endif

            @if(auth()->user()->role === 'pegawai')
                <!-- Toolbar Tombol Buat Laporan Dinas (Di Bawah Header, Di Atas Tabel Riwayat) -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/90 dark:border-slate-800 p-5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-colors">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-slate-800 border border-blue-200 dark:border-slate-700 flex items-center justify-center text-[#1769AA] dark:text-blue-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-slate-800 dark:text-slate-100">
                                Laporan Pertanggungjawaban Perjalanan Dinas (Pajokka)
                            </div>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                Susun laporan dinas dengan scan otomatis OCR surat tugas, rincian biaya KKP & Cash, serta ekspor resmi Word/PDF
                            </p>
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('pajokka.create') }}" class="inline-flex items-center space-x-1.5 px-4 py-2.5 bg-[#1769AA] hover:bg-[#0F2747] text-white text-xs font-bold rounded-xl shadow-xs transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Buat Laporan Dinas Baru</span>
                        </a>
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-xs sm:rounded-2xl border border-slate-200 dark:border-slate-800 transition-colors">
                <div class="p-6 text-slate-900 dark:text-slate-100">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-100 dark:bg-slate-800 text-[#0F2747] dark:text-slate-200 uppercase text-xs tracking-wider border-b border-slate-200 dark:border-slate-700">
                                    <th class="p-3">No</th>
                                    @if(auth()->user()->role === 'admin')
                                        <th class="p-3">Pegawai / Pelaksana</th>
                                    @endif
                                    <th class="p-3">Nomor Surat</th>
                                    <th class="p-3">Tujuan</th>
                                    <th class="p-3">Tanggal</th>
                                    <th class="p-3">PPK</th>
                                    <th class="p-3 text-center">Status</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                                @forelse($reports as $index => $item)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                        <td class="p-3 text-slate-500 dark:text-slate-400">{{ $reports->firstItem() + $index }}</td>
                                        @if(auth()->user()->role === 'admin')
                                            <td class="p-3">
                                                <div class="font-bold text-[#0F2747] dark:text-slate-100">{{ $item->pegawai->nama ?? '-' }}</div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400 font-mono">{{ $item->pegawai->nip ?? '-' }}</div>
                                            </td>
                                        @endif
                                        <td class="p-3 font-semibold text-[#1769AA] dark:text-blue-400">
                                            {{ $item->nomor_surat_tugas ?? $item->nomor_surat }}
                                        </td>
                                        <td class="p-3 font-medium text-slate-800 dark:text-slate-200">
                                            {{ $item->kota_tujuan ?? $item->tujuan }}
                                        </td>
                                        <td class="p-3 text-xs text-slate-600 dark:text-slate-400">
                                            {{ ($item->tgl_mulai_dinas ?? $item->tanggal_berangkat)?->format('d/m/Y') ?? '-' }} s.d. {{ ($item->tgl_selesai_dinas ?? $item->tanggal_kembali)?->format('d/m/Y') ?? '-' }}
                                        </td>
                                        <td class="p-3 text-xs text-slate-600 dark:text-slate-400">
                                            {{ $item->ppk->nama ?? ($item->masterPpk->nama ?? '-') }}
                                        </td>
                                        <td class="p-3 text-center">
                                            @php
                                                $st = $item->status;
                                                $bgClass = match($st) {
                                                    'approved' => 'bg-emerald-100 dark:bg-emerald-950/70 text-emerald-800 dark:text-emerald-300',
                                                    'rejected' => 'bg-rose-100 dark:bg-rose-950/70 text-rose-800 dark:text-rose-300',
                                                    'revision_required', 'revision' => 'bg-amber-100 dark:bg-amber-950/70 text-amber-800 dark:text-amber-300',
                                                    'submitted' => 'bg-blue-100 dark:bg-blue-950/70 text-blue-800 dark:text-blue-300',
                                                    default => 'bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-300',
                                                };
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase {{ $bgClass }}">
                                                {{ str_replace('_', ' ', $st) }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-center space-x-1">
                                            <a href="{{ route('pajokka.show', $item) }}" class="text-[#1769AA] dark:text-blue-400 hover:underline font-medium text-xs bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-lg inline-block">Detail</a>
                                            <a href="{{ route('pajokka.docx', $item) }}" class="text-indigo-700 dark:text-indigo-400 hover:underline font-medium text-xs bg-indigo-50 dark:bg-indigo-950/70 px-2 py-1 rounded-lg inline-block" title="Download Word (.docx)">DOCX</a>
                                            <a href="{{ route('pajokka.pdf', $item) }}" class="text-rose-700 dark:text-rose-400 hover:underline font-medium text-xs bg-rose-50 dark:bg-rose-950/70 px-2 py-1 rounded-lg inline-block" title="Download PDF">PDF</a>

                                            @if(auth()->user()->role === 'admin')
                                                <div x-data="{ openModal: false }" class="inline-block">
                                                    <button @click="openModal = true" class="text-emerald-700 dark:text-emerald-400 hover:underline font-medium text-xs bg-emerald-50 dark:bg-emerald-950/70 px-2.5 py-1 rounded-lg">Review</button>
                                                    
                                                    <!-- Review Modal -->
                                                    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60" style="display: none;">
                                                        <div @click.away="openModal = false" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-xl max-w-md w-full text-left">
                                                            <h3 class="font-bold text-lg text-[#0F2747] dark:text-slate-100 mb-4">Review Perjalanan Dinas</h3>
                                                            <form action="{{ route('pajokka.status', $item) }}" method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <div class="mb-4">
                                                                    <label class="block text-xs font-bold uppercase text-slate-600 dark:text-slate-300 mb-1">Status Laporan</label>
                                                                    <select name="status" class="w-full border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 rounded-xl text-sm">
                                                                        <option value="approved" {{ $item->status === 'approved' ? 'selected' : '' }}>Setujui (Approved)</option>
                                                                        <option value="revision_required" {{ $item->status === 'revision_required' ? 'selected' : '' }}>Perlu Revisi (Revision)</option>
                                                                        <option value="rejected" {{ $item->status === 'rejected' ? 'selected' : '' }}>Tolak (Rejected)</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-4">
                                                                    <label class="block text-xs font-bold uppercase text-slate-600 dark:text-slate-300 mb-1">Catatan / Arahan Revisi</label>
                                                                    <textarea name="admin_notes" rows="3" class="w-full border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 rounded-xl text-sm" placeholder="Tambahkan catatan jika perlu revisi...">{{ $item->admin_notes }}</textarea>
                                                                </div>
                                                                <div class="flex justify-end space-x-2">
                                                                    <button type="button" @click="openModal = false" class="bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 px-3.5 py-1.5 rounded-xl text-xs font-bold">Batal</button>
                                                                    <button type="submit" class="bg-[#1769AA] text-white px-3.5 py-1.5 rounded-xl text-xs font-bold shadow-xs">Simpan Status</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($item->status === 'draft' || $item->status === 'submitted')
                                                <form action="{{ route('pajokka.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin membatalkan laporan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-600 dark:text-rose-400 hover:underline font-medium text-xs">Batalkan</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->role === 'admin' ? 8 : 7 }}" class="p-6 text-center text-slate-500 dark:text-slate-400">Belum ada riwayat laporan perjalanan dinas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $reports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
