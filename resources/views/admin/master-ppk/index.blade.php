<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-[#0F2747] leading-tight">
                {{ __('Manajemen Master PPK') }}
            </h2>
            <a href="{{ route('admin.master-ppk.create') }}" class="bg-[#1769AA] hover:bg-[#0F2747] text-white px-4 py-2 rounded-lg font-medium text-sm transition">
                + Tambah PPK
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
                                    <th class="p-3">Nama PPK</th>
                                    <th class="p-3">NIP</th>
                                    <th class="p-3">Jabatan</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 text-sm">
                                @forelse($ppks as $index => $ppk)
                                    <tr>
                                        <td class="p-3">{{ $ppks->firstItem() + $index }}</td>
                                        <td class="p-3 font-semibold text-[#1769AA]">{{ $ppk->nama }}</td>
                                        <td class="p-3">{{ $ppk->nip }}</td>
                                        <td class="p-3">{{ $ppk->jabatan }}</td>
                                        <td class="p-3 text-center space-x-2">
                                            <a href="{{ route('admin.master-ppk.edit', $ppk) }}" class="text-amber-600 hover:underline font-medium">Edit</a>
                                            <form action="{{ route('admin.master-ppk.destroy', $ppk) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus PPK ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-600 hover:underline font-medium">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-4 text-center text-slate-500">Belum ada data Master PPK.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $ppks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
