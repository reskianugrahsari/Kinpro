<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#0F2747] dark:text-white">Laporan Perjalanan Dinas</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar seluruh laporan hasil perjalanan dinas terdaftar di sistem.</p>
            </div>
            @if(auth()->user()->isPegawai())
                <a href="{{ route('reports.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-[#1769AA] hover:bg-[#0F2747] text-white rounded-xl font-semibold text-xs transition shadow-sm gap-2">
                    <svg class="w-4 h-4 text-[#00A6C7]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Laporan Baru
                </a>
            @endif
        </div>

        <!-- Search & Filter Bar -->
        <div class="bg-white dark:bg-[#11223A] p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
            <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap items-center gap-3 w-full">
                <!-- Search Input -->
                <div class="relative flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor ST / kota tujuan..." class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-[#071322] border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-xl text-xs focus:bg-white dark:focus:bg-[#0B1727] focus:border-[#1769AA] focus:ring-[#1769AA]">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                </div>

                <!-- Status Filter -->
                <select name="status" onchange="this.form.submit()" class="rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#071322] text-slate-900 dark:text-slate-100 text-xs focus:bg-white dark:focus:bg-[#0B1727] focus:border-[#1769AA] focus:ring-[#1769AA]">
                    <option value="">Semua Status</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="revision_required" {{ request('status') === 'revision_required' ? 'selected' : '' }}>Perlu Revisi</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-[#0F2747] dark:bg-[#1769AA] text-white font-semibold rounded-xl hover:bg-[#1769AA] dark:hover:bg-[#0F2747] transition">Cari</button>
            </form>
        </div>

        <!-- Table Container -->
        <div class="bg-white dark:bg-[#11223A] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
            @if($reports->isEmpty())
                <div class="p-12 text-center text-slate-500 dark:text-slate-400 text-xs">
                    Tidak ada laporan ditemukan.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300">
                        <thead class="bg-slate-50 dark:bg-[#071322] text-slate-500 dark:text-slate-400 uppercase tracking-wider font-semibold border-b border-slate-200 dark:border-slate-800">
                            <tr>
                                <th class="p-3.5 pl-5">Nomor ST</th>
                                <th class="p-3.5">Pembuat</th>
                                <th class="p-3.5">Kota Tujuan</th>
                                <th class="p-3.5">Periode</th>
                                <th class="p-3.5">Status</th>
                                <th class="p-3.5 text-right pr-5">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($reports as $report)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition">
                                    <td class="p-3.5 pl-5 font-bold text-[#0F2747] dark:text-white">{{ $report->nomor_surat_tugas }}</td>
                                    <td class="p-3.5 font-medium">{{ $report->user->name ?? '-' }}</td>
                                    <td class="p-3.5 font-medium">{{ $report->kota_tujuan }}</td>
                                    <td class="p-3.5 text-slate-500 dark:text-slate-400">{{ $report->tgl_mulai_dinas->translatedFormat('j M Y') }} – {{ $report->tgl_selesai_dinas->translatedFormat('j M Y') }}</td>
                                    <td class="p-3.5">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border uppercase tracking-wider bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 border-blue-200 dark:border-blue-700">
                                            {{ strtoupper(str_replace('_', ' ', $report->status)) }}
                                        </span>
                                    </td>
                                    <td class="p-3.5 text-right pr-5">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('reports.show', $report->id) }}" class="text-[#1769AA] dark:text-blue-400 font-bold hover:underline">Detail &rarr;</a>
                                            <a href="{{ route('reports.export.pdf', $report->id) }}" title="Download PDF" class="px-2.5 py-1 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 font-bold rounded-lg hover:bg-red-100 transition flex items-center gap-1 text-[11px] border border-red-200 dark:border-red-800">
                                                PDF
                                            </a>
                                            <a href="{{ route('reports.export.docx', $report->id) }}" title="Download Word" class="px-2.5 py-1 bg-blue-50 dark:bg-blue-950/40 text-[#1769AA] dark:text-blue-400 font-bold rounded-lg hover:bg-blue-100 transition flex items-center gap-1 text-[11px] border border-blue-200 dark:border-blue-800">
                                                Word
                                            </a>
                                            <form action="{{ route('reports.destroy', $report->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus Laporan" class="px-2.5 py-1 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 font-bold rounded-lg hover:bg-red-100 dark:hover:bg-red-900/60 transition flex items-center gap-1 text-[11px] border border-red-200 dark:border-red-800">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
