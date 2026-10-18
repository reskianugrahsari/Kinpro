<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-[#0F2747] leading-tight">
                {{ __('Manajemen Unit Kerja') }}
            </h2>
            <a href="{{ route('admin.unit-kerja.create') }}" class="bg-[#1769AA] hover:bg-[#0F2747] text-white px-4 py-2 rounded-lg font-medium text-sm transition">
                + Tambah Unit Kerja
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200">
                <div class="p-6 text-slate-900">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-100 text-[#0F2747] uppercase text-xs tracking-wider border-b border-slate-200">
                                    <th class="p-3">No</th>
                                    <th class="p-3">Kode</th>
                                    <th class="p-3">Nama Unit Kerja</th>
                                    <th class="p-3">Jumlah Pegawai</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 text-sm">
                                @forelse($unitKerjas as $index => $uk)
                                    <tr>
                                        <td class="p-3">{{ $unitKerjas->firstItem() + $index }}</td>
                                        <td class="p-3 font-semibold text-[#1769AA]">{{ $uk->kode }}</td>
                                        <td class="p-3 font-medium">{{ $uk->nama }}</td>
                                        <td class="p-3">
                                            <span class="bg-slate-100 text-[#0F2747] px-2.5 py-1 rounded-full text-xs font-bold">
                                                {{ $uk->pegawai_count ?? 0 }} Pegawai
                                            </span>
                                        </td>
                                        <td class="p-3 text-center space-x-2">
                                            <a href="{{ route('admin.unit-kerja.edit', $uk) }}" class="text-amber-600 hover:underline font-medium">Edit</a>
                                            <form action="{{ route('admin.unit-kerja.destroy', $uk) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus unit kerja ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-600 hover:underline font-medium">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-4 text-center text-slate-500">Belum ada data unit kerja.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $unitKerjas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
