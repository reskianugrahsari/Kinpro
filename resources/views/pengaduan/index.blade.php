<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-extrabold text-2xl text-[#0F2747] tracking-tight">
                    {{ auth()->user()->role === 'admin' ? __('Kelola Pengaduan Pegawai') : __('Modul Pengaduan & Aspirasi') }}
                </h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Kanal pelaporan aspirasi, kendala kerja, dan pengaduan pegawai</p>
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
                <!-- Toolbar Tombol Buat Pengaduan (Di Bawah Header, Di Atas Tabel Riwayat) -->
                <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center text-[#1769AA] shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-slate-800">
                                Layanan Pengaduan & Aspirasi
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                Sampaikan keluhan, aspirasi, atau kendala fasilitas kedinasan secara aman dan transparan
                            </p>
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('pengaduan.create') }}" class="inline-flex items-center space-x-1.5 px-4 py-2.5 bg-[#1769AA] hover:bg-[#0F2747] text-white text-xs font-bold rounded-xl shadow-xs transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Buat Pengaduan Baru</span>
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
                                        <th class="p-3">Pegawai</th>
                                    @endif
                                    <th class="p-3">Jenis Laporan</th>
                                    <th class="p-3">Tanggal Kejadian</th>
                                    <th class="p-3">Keterangan</th>
                                    <th class="p-3">Bukti</th>
                                    <th class="p-3 text-center">Status</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 text-sm">
                                @forelse($pengaduans as $index => $item)
                                    <tr>
                                        <td class="p-3">{{ $pengaduans->firstItem() + $index }}</td>
                                        @if(auth()->user()->role === 'admin')
                                            <td class="p-3">
                                                <div class="font-bold text-[#0F2747]">{{ $item->pegawai->nama ?? '-' }}</div>
                                                <div class="text-xs text-slate-500 font-mono">{{ $item->pegawai->nip ?? '-' }}</div>
                                            </td>
                                        @endif
                                        <td class="p-3 font-semibold text-[#1769AA]">
                                            {{ $item->jenis_laporan }}
                                        </td>
                                        <td class="p-3 font-medium text-xs">
                                            {{ $item->tanggal_kejadian }}
                                        </td>
                                        <td class="p-3 max-w-xs truncate" title="{{ $item->keterangan }}">
                                            {{ $item->keterangan }}
                                        </td>
                                        <td class="p-3">
                                            @if($item->bukti)
                                                <a href="{{ asset($item->bukti) }}" target="_blank" class="text-[#1769AA] hover:underline text-xs font-semibold">Lihat Berkas</a>
                                            @else
                                                <span class="text-slate-400 text-xs">Tidak ada</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-center">
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $item->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($item->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                            @if($item->alasan_penolakan)
                                                <div class="text-[10px] text-slate-500 mt-1 italic">Catatan/Alasan: {{ $item->alasan_penolakan }}</div>
                                            @endif
                                        </td>
                                        <td class="p-3 text-center space-x-2">
                                            @if(auth()->user()->role === 'admin')
                                                <div x-data="{ openModal: false }" class="inline-block">
                                                    <button @click="openModal = true" class="text-[#1769AA] hover:underline font-medium text-xs bg-slate-100 px-2.5 py-1 rounded">Review</button>
                                                    
                                                    <!-- Review Modal -->
                                                    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
                                                        <div @click.away="openModal = false" class="bg-white p-6 rounded-xl shadow-lg max-w-md w-full text-left">
                                                            <h3 class="font-bold text-lg text-[#0F2747] mb-4">Review Pengaduan</h3>
                                                            <form action="{{ route('pengaduan.status', $item) }}" method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <div class="mb-4">
                                                                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Status</label>
                                                                    <select name="status" class="w-full border-slate-300 rounded-md text-sm">
                                                                        <option value="approved" {{ $item->status === 'approved' ? 'selected' : '' }}>Tindak Lanjut / Selesai (Approved)</option>
                                                                        <option value="rejected" {{ $item->status === 'rejected' ? 'selected' : '' }}>Tolak (Rejected)</option>
                                                                        <option value="pending" {{ $item->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-4">
                                                                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Tanggapan / Alasan Penolakan</label>
                                                                    <textarea name="alasan_penolakan" rows="3" class="w-full border-slate-300 rounded-md text-sm">{{ $item->alasan_penolakan }}</textarea>
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

                                            @if($item->status === 'pending' || auth()->user()->role === 'admin')
                                                <form action="{{ route('pengaduan.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pengaduan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-600 hover:underline font-medium text-xs">Hapus</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->role === 'admin' ? 8 : 7 }}" class="p-4 text-center text-slate-500">Belum ada pengaduan atau aspirasi tercatat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $pengaduans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
