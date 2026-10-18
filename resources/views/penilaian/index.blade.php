<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-[#0F2747] leading-tight">
                {{ __('Penilaian Kinerja Pegawai') }}
            </h2>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('penilaian.create') }}" class="bg-[#1769AA] hover:bg-[#0F2747] text-white px-4 py-2 rounded-lg font-medium text-sm transition">
                    + Tambah Penilaian
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-emerald-50 border-l-4 border-emerald-500 p-4 text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200">
                <div class="p-6 text-slate-900">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-100 text-[#0F2747] uppercase text-xs tracking-wider border-b border-slate-200">
                                    <th class="p-3">No</th>
                                    <th class="p-3">Pegawai / NIP</th>
                                    <th class="p-3">Periode</th>
                                    <th class="p-3 text-center">6 Aspek Nilai (Disiplin, Kinerja, Sikap, Kepemimpinan, Loyalitas, IT)</th>
                                    <th class="p-3 text-center">Rata-Rata</th>
                                    <th class="p-3 text-center">Predikat</th>
                                    @if(auth()->user()->role === 'admin')
                                        <th class="p-3 text-center">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 text-sm">
                                @forelse($penilaians as $index => $item)
                                    <tr>
                                        <td class="p-3">{{ $penilaians->firstItem() + $index }}</td>
                                        <td class="p-3">
                                            <div class="font-bold text-[#0F2747]">{{ $item->pegawai->nama ?? '-' }}</div>
                                            <div class="text-xs text-slate-500 font-mono">NIP. {{ $item->pegawai->nip ?? '-' }}</div>
                                        </td>
                                        <td class="p-3 font-medium">{{ $item->bulan }} {{ $item->tahun }}</td>
                                        <td class="p-3 text-center">
                                            <span class="text-xs font-mono bg-slate-100 px-2 py-1 rounded">
                                                {{ $item->nilai_kedisiplinan }} | {{ $item->kinerja }} | {{ $item->sikap }} | {{ $item->kepemimpinan }} | {{ $item->loyalitas }} | {{ $item->it }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-center font-bold text-[#1769AA]">{{ $item->rata_rata }}</td>
                                        <td class="p-3 text-center">
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ str_contains($item->predikat, 'A') ? 'bg-emerald-100 text-emerald-800' : (str_contains($item->predikat, 'B') ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                                {{ $item->predikat }}
                                            </span>
                                        </td>
                                        @if(auth()->user()->role === 'admin')
                                            <td class="p-3 text-center space-x-2">
                                                <a href="{{ route('penilaian.edit', $item) }}" class="text-amber-600 hover:underline font-medium">Edit</a>
                                                <form action="{{ route('penilaian.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus penilaian ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-600 hover:underline font-medium">Hapus</button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->role === 'admin' ? 7 : 6 }}" class="p-4 text-center text-slate-500">Belum ada data penilaian kinerja.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $penilaians->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
