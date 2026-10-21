<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-[#0F2747] dark:text-slate-100 tracking-tight">
                    {{ __('Manajemen Master PPK') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">Daftar Pejabat Pembuat Komitmen (PPK) penandatangan surat tugas & SPPD</p>
            </div>
            <div>
                <a href="{{ route('admin.master-ppk.create') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-[#1769AA] hover:bg-[#0F2747] text-white text-xs font-bold rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Tambah PPK</span>
                </a>
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

            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-xs sm:rounded-2xl border border-slate-200 dark:border-slate-800 transition-colors">
                <div class="p-6 text-slate-900 dark:text-slate-100">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-100 dark:bg-slate-800 text-[#0F2747] dark:text-slate-200 uppercase text-xs tracking-wider border-b border-slate-200 dark:border-slate-700">
                                    <th class="p-3">No</th>
                                    <th class="p-3">Nama PPK</th>
                                    <th class="p-3">NIP</th>
                                    <th class="p-3">Jabatan</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                                @forelse($ppks as $index => $ppk)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                        <td class="p-3 text-slate-500 dark:text-slate-400">{{ $ppks->firstItem() + $index }}</td>
                                        <td class="p-3 font-semibold text-[#1769AA] dark:text-blue-400">{{ $ppk->nama }}</td>
                                        <td class="p-3 font-mono text-xs text-slate-600 dark:text-slate-400">{{ $ppk->nip }}</td>
                                        <td class="p-3 text-slate-700 dark:text-slate-300">{{ $ppk->jabatan }}</td>
                                        <td class="p-3 text-center space-x-2">
                                            <a href="{{ route('admin.master-ppk.edit', $ppk) }}" class="text-amber-600 dark:text-amber-400 hover:underline font-medium text-xs">Edit</a>
                                            <form action="{{ route('admin.master-ppk.destroy', $ppk) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus PPK ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-600 dark:text-rose-400 hover:underline font-medium text-xs">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-6 text-center text-slate-500 dark:text-slate-400">Belum ada data Master PPK.</td>
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
