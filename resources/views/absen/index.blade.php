<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-[#0F2747] leading-tight">
                {{ __('Modul Lupa Absen / Don\'t Forget') }}
            </h2>
            <a href="{{ route('absen.create') }}" class="bg-[#1769AA] hover:bg-[#0F2747] text-white px-4 py-2 rounded-lg font-medium text-sm transition">
                + Ajukan Lupa Absen
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-emerald-50 border-l-4 border-emerald-500 p-4 text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-rose-50 border-l-4 border-rose-500 p-4 text-rose-700">
                    {{ session('error') }}
                </div>
            @endif

            @if(auth()->user()->role === 'admin')
                <div class="bg-white p-4 rounded-xl border border-slate-200 mb-6 shadow-sm">
                    <form method="GET" action="{{ route('absen.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Cari Pegawai / NIP</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama / NIP..." class="w-full border-slate-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Status Verifikasi</label>
                            <select name="status" class="w-full border-slate-300 rounded-md text-sm">
                                <option value="">-- Semua Status --</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved (Disetujui)</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                            </select>
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="bg-[#1769AA] hover:bg-[#0F2747] text-white px-4 py-2 rounded-md text-sm font-bold w-full">Filter Data</button>
                            <a href="{{ route('absen.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-md text-sm font-medium text-center">Reset</a>
                        </div>
                    </form>
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
                                    <th class="p-3">Tanggal Absen</th>
                                    <th class="p-3">Alasan</th>
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
                                            {{ $item->tanggal_absen }}
                                        </td>
                                        <td class="p-3 font-medium text-xs">
                                            {{ ucwords(str_replace('_', ' ', $item->alasan)) }}
                                        </td>
                                        <td class="p-3 max-w-xs truncate" title="{{ $item->keterangan }}">
                                            {{ $item->keterangan }}
                                        </td>
                                        <td class="p-3">
                                            @if($item->bukti_path)
                                                <a href="{{ asset($item->bukti_path) }}" target="_blank" class="text-[#1769AA] hover:underline text-xs font-semibold">Lihat Berkas</a>
                                            @else
                                                <span class="text-slate-400 text-xs">Tidak ada</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-center">
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $item->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($item->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                            @if($item->catatan_admin)
                                                <div class="text-[10px] text-slate-500 mt-1 italic">Catatan: {{ $item->catatan_admin }}</div>
                                            @endif
                                        </td>
                                        <td class="p-3 text-center space-x-1">
                                            <a href="{{ route('absen.show', $item) }}" target="_blank" class="text-emerald-700 hover:underline font-medium text-xs bg-emerald-50 px-2 py-1 rounded inline-block">Cetak Surat</a>

                                            @if(auth()->user()->role === 'admin')
                                                <div x-data="{ openModal: false }" class="inline-block">
                                                    <button @click="openModal = true" class="text-[#1769AA] hover:underline font-medium text-xs bg-slate-100 px-2.5 py-1 rounded">Review</button>
                                                    
                                                    <!-- Review Modal -->
                                                    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
                                                        <div @click.away="openModal = false" class="bg-white p-6 rounded-xl shadow-lg max-w-md w-full text-left">
                                                            <h3 class="font-bold text-lg text-[#0F2747] mb-4">Review Pengajuan Lupa Absen</h3>
                                                            <form action="{{ route('absen.status', $item) }}" method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <div class="mb-4">
                                                                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Status</label>
                                                                    <select name="status" class="w-full border-slate-300 rounded-md text-sm">
                                                                        <option value="approved" {{ $item->status === 'approved' ? 'selected' : '' }}>Setujui (Approved)</option>
                                                                        <option value="rejected" {{ $item->status === 'rejected' ? 'selected' : '' }}>Tolak (Rejected)</option>
                                                                        <option value="pending" {{ $item->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-4">
                                                                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Catatan Admin</label>
                                                                    <textarea name="catatan_admin" rows="3" class="w-full border-slate-300 rounded-md text-sm">{{ $item->catatan_admin }}</textarea>
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
                                                <form action="{{ route('absen.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-600 hover:underline font-medium text-xs">Hapus</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->role === 'admin' ? 8 : 7 }}" class="p-4 text-center text-slate-500">Belum ada riwayat pengajuan lupa absen.</td>
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
