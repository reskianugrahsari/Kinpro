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
                <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center text-[#1769AA] shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-slate-800">
                                Laporan Pertanggungjawaban Perjalanan Dinas (Pajokka)
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200">
                <div class="p-6 text-slate-900">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-100 text-[#0F2747] uppercase text-xs tracking-wider border-b border-slate-200">
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
                            <tbody class="divide-y divide-slate-200 text-sm">
                                @forelse($reports as $index => $item)
                                    <tr>
                                        <td class="p-3">{{ $reports->firstItem() + $index }}</td>
                                        @if(auth()->user()->role === 'admin')
                                            <td class="p-3">
                                                <div class="font-bold text-[#0F2747]">{{ $item->pegawai->nama ?? '-' }}</div>
                                                <div class="text-xs text-slate-500 font-mono">{{ $item->pegawai->nip ?? '-' }}</div>
                                            </td>
                                        @endif
                                        <td class="p-3 font-semibold text-[#1769AA]">
                                            {{ $item->nomor_surat_tugas ?? $item->nomor_surat }}
                                        </td>
                                        <td class="p-3 font-medium">
                                            {{ $item->kota_tujuan ?? $item->tujuan }}
                                        </td>
                                        <td class="p-3 text-xs">
                                            {{ ($item->tgl_mulai_dinas ?? $item->tanggal_berangkat)?->format('d/m/Y') ?? '-' }} s.d. {{ ($item->tgl_selesai_dinas ?? $item->tanggal_kembali)?->format('d/m/Y') ?? '-' }}
                                        </td>
                                        <td class="p-3 text-xs">
                                            {{ $item->ppk->nama ?? ($item->masterPpk->nama ?? '-') }}
                                        </td>
                                        <td class="p-3 text-center">
                                            @php
                                                $st = $item->status;
                                                $bgClass = match($st) {
                                                    'approved' => 'bg-emerald-100 text-emerald-800',
                                                    'rejected' => 'bg-rose-100 text-rose-800',
                                                    'revision_required', 'revision' => 'bg-amber-100 text-amber-800',
                                                    'submitted' => 'bg-blue-100 text-blue-800',
                                                    default => 'bg-slate-100 text-slate-800',
                                                };
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase {{ $bgClass }}">
                                                {{ str_replace('_', ' ', $st) }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-center space-x-1">
                                            <a href="{{ route('pajokka.show', $item) }}" class="text-[#1769AA] hover:underline font-medium text-xs bg-slate-100 px-2.5 py-1 rounded inline-block">Detail</a>
                                            <a href="{{ route('pajokka.docx', $item) }}" class="text-indigo-700 hover:underline font-medium text-xs bg-indigo-50 px-2 py-1 rounded inline-block" title="Download Word (.docx)">DOCX</a>
                                            <a href="{{ route('pajokka.pdf', $item) }}" class="text-rose-700 hover:underline font-medium text-xs bg-rose-50 px-2 py-1 rounded inline-block" title="Download PDF">PDF</a>

                                            @if(auth()->user()->role === 'admin')
                                                <div x-data="{ openModal: false }" class="inline-block">
                                                    <button @click="openModal = true" class="text-emerald-700 hover:underline font-medium text-xs bg-emerald-50 px-2.5 py-1 rounded">Review</button>
                                                    
                                                    <!-- Review Modal -->
                                                    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
                                                        <div @click.away="openModal = false" class="bg-white p-6 rounded-xl shadow-lg max-w-md w-full text-left">
                                                            <h3 class="font-bold text-lg text-[#0F2747] mb-4">Review Perjalanan Dinas</h3>
                                                            <form action="{{ route('pajokka.status', $item) }}" method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <div class="mb-4">
                                                                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Status</label>
                                                                    <select name="status" class="w-full border-slate-300 rounded-md text-sm">
                                                                        <option value="approved" {{ $item->status === 'approved' ? 'selected' : '' }}>Setujui (Approved)</option>
                                                                        <option value="revision" {{ $item->status === 'revision' ? 'selected' : '' }}>Revisi (Revision)</option>
                                                                        <option value="rejected" {{ $item->status === 'rejected' ? 'selected' : '' }}>Tolak (Rejected)</option>
                                                                        <option value="submitted" {{ $item->status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                                                        <option value="draft" {{ $item->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                                                    </select>
                                                                </div>
                                                                <div class="flex justify-end space-x-2">
                                                                    <button type="button" @click="openModal = false" class="bg-slate-200 text-slate-800 px-3 py-1.5 rounded text-xs">Batal</button>
                                                                    <button type="submit" class="bg-[#1769AA] text-white px-3 py-1.5 rounded text-xs font-bold">Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($item->status === 'draft' || auth()->user()->role === 'admin')
                                                <form action="{{ route('pajokka.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-600 hover:underline font-medium text-xs">Hapus</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->role === 'admin' ? 8 : 7 }}" class="p-4 text-center text-slate-500">Belum ada laporan perjalanan dinas.</td>
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
