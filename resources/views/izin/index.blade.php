<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-[#0F2747] leading-tight">
                {{ __('Modul Izin & Cuti Pegawai') }}
            </h2>
            <div class="flex items-center space-x-4">
                <span class="bg-slate-100 text-[#0F2747] px-3 py-1.5 rounded-lg text-xs font-bold border border-slate-200">
                    Kuota Bulan Ini: {{ $sisaKuota }} / 4 Tersedia (Terpakai: {{ $jumlahBulanIni }})
                </span>
                @if($sisaKuota > 0 || auth()->user()->role === 'admin')
                    <a href="{{ route('izin.create') }}" class="bg-[#1769AA] hover:bg-[#0F2747] text-white px-4 py-2 rounded-lg font-medium text-sm transition">
                        + Ajukan Izin / Cuti
                    </a>
                @endif
            </div>
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
                                    <th class="p-3">Jenis Izin</th>
                                    <th class="p-3">Tanggal Mulai - Selesai</th>
                                    <th class="p-3">Keterangan</th>
                                    <th class="p-3">Dokumen</th>
                                    <th class="p-3 text-center">Status</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 text-sm">
                                @forelse($izins as $index => $item)
                                    <tr>
                                        <td class="p-3">{{ $izins->firstItem() + $index }}</td>
                                        @if(auth()->user()->role === 'admin')
                                            <td class="p-3">
                                                <div class="font-bold text-[#0F2747]">{{ $item->pegawai->nama ?? '-' }}</div>
                                                <div class="text-xs text-slate-500 font-mono">{{ $item->pegawai->nip ?? '-' }}</div>
                                            </td>
                                        @endif
                                        <td class="p-3 font-semibold text-[#1769AA]">
                                            {{ ucwords(str_replace('_', ' ', $item->jenis_izin)) }}
                                        </td>
                                        <td class="p-3 font-medium text-xs">
                                            {{ $item->tanggal_mulai }} s/d {{ $item->tanggal_selesai }}
                                        </td>
                                        <td class="p-3 max-w-xs truncate" title="{{ $item->keterangan }}">
                                            {{ $item->keterangan }}
                                        </td>
                                        <td class="p-3">
                                            @if($item->dokumen)
                                                <a href="{{ asset($item->dokumen) }}" target="_blank" class="text-[#1769AA] hover:underline text-xs font-semibold">Lihat Berkas</a>
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
                                        <td class="p-3 text-center space-x-2">
                                            @if(auth()->user()->role === 'admin')
                                                <div x-data="{ openModal: false }" class="inline-block">
                                                    <button @click="openModal = true" class="text-[#1769AA] hover:underline font-medium text-xs bg-slate-100 px-2.5 py-1 rounded">Review</button>
                                                    
                                                    <!-- Review Modal -->
                                                    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
                                                        <div @click.away="openModal = false" class="bg-white p-6 rounded-xl shadow-lg max-w-md w-full text-left">
                                                            <h3 class="font-bold text-lg text-[#0F2747] mb-4">Review Pengajuan Izin</h3>
                                                            <form action="{{ route('izin.status', $item) }}" method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <div class="mb-4">
                                                                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Status</label>
                                                                    <select name="status" class="w-full border-slate-300 rounded-md text-sm">
                                                                        <option value="approved" {{ $item->status === 'approved' ? 'selected' : '' }}>Setujui (Approved)</option>
                                                                        <option value="rejected" {{ $item->status === 'rejected' ? 'selected' : '' }}>Tolak (Rejected)</option>
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

                                            @if($item->status === 'pending')
                                                <form action="{{ route('izin.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin membatalkan pengajuan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-600 hover:underline font-medium text-xs">Batalkan</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->role === 'admin' ? 8 : 7 }}" class="p-4 text-center text-slate-500">Belum ada riwayat pengajuan izin atau cuti.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $izins->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
