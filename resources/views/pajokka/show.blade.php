<x-app-layout>
    <div class="max-w-5xl mx-auto space-y-6" x-data="{ revisionModalOpen: false }">

        <!-- Top Header & Action Buttons -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-[#11223A] p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-xl font-bold text-[#0F2747] dark:text-white">Detail Laporan Perjalanan Dinas</h1>
                    @php
                        $badges = [
                            'draft' => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-600',
                            'submitted' => 'bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 border-blue-200 dark:border-blue-700',
                            'under_review' => 'bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-700',
                            'revision_required' => 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300 border-red-200 dark:border-red-700',
                            'approved' => 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-700',
                            'generated' => 'bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-300 border-purple-200 dark:border-purple-700',
                            'completed' => 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-700',
                            'rejected' => 'bg-red-600 text-white border-transparent',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold border uppercase tracking-wider {{ $badges[$report->status] ?? 'bg-slate-100' }}">
                        {{ str_replace('_', ' ', $report->status) }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Nomor Surat Tugas: <span class="font-bold text-[#0F2747] dark:text-white">{{ $report->nomor_surat_tugas }}</span></p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                @if (!auth()->user()->isPegawai() && in_array($report->status, ['submitted', 'under_review', 'revision_required']))
                    <!-- Approve Button -->
                    <form action="{{ route('reports.approve', $report->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui laporan ini?')">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center gap-1">
                            &check; Approve
                        </button>
                    </form>

                    <!-- Request Revision Modal Trigger -->
                    <button type="button" @click="revisionModalOpen = true" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center gap-1">
                        &circle; Request Revisi
                    </button>
                @endif

                <!-- Export Buttons -->
                <a href="{{ route('reports.export.pdf', $report->id) }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download PDF
                </a>
                <a href="{{ route('reports.export.docx', $report->id) }}" class="px-4 py-2 bg-[#1769AA] hover:bg-[#0F2747] text-white rounded-xl text-xs font-bold transition flex items-center gap-1 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download Word
                </a>
                @if (auth()->user()->isAdmin() || auth()->user()->isVerifikator() || $report->user_id === auth()->id())
                    <form action="{{ route('reports.destroy', $report->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-900/80 font-bold rounded-xl text-xs transition shadow-sm flex items-center gap-1">
                            Hapus Laporan
                        </button>
                    </form>
                @endif

                <a href="{{ route('reports.index') }}" class="px-3 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition">
                    &larr; Kembali
                </a>
            </div>
        </div>

        <!-- Revision History Notice (If any) -->
        @if ($report->revisions->count() > 0)
            <div class="bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 rounded-2xl p-5 space-y-3">
                <h3 class="font-bold text-xs text-red-800 dark:text-red-300 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Catatan Revisi Verifikator / Admin
                </h3>
                <div class="space-y-2">
                    @foreach ($report->revisions as $rev)
                        <div class="bg-white dark:bg-[#11223A] p-3 rounded-xl border border-red-200 dark:border-red-800 text-xs">
                            <div class="flex justify-between font-bold text-slate-700 dark:text-slate-300">
                                <span>Oleh: {{ $rev->requester->name ?? 'Verifikator' }} (Bagian: {{ $rev->section }})</span>
                                <span class="text-slate-400 font-normal">{{ $rev->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $rev->notes }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Main Report Content Card -->
        <div class="bg-white dark:bg-[#11223A] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-8 space-y-6">

            <!-- Title Header -->
            <div class="text-center border-b-2 border-slate-900 dark:border-slate-500 pb-4">
                <h2 class="text-lg font-bold uppercase tracking-wide text-[#0F2747] dark:text-white">LAPORAN PELAKSANAAN PERJALANAN DINAS</h2>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">BPSDM KEMENTERIAN PEKERJAAN UMUM</p>
            </div>

            <!-- Metadata Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs bg-slate-50 dark:bg-[#0B1727] p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                <div>
                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Nomor Surat Tugas</span>
                    <span class="font-bold text-slate-900 dark:text-white">{{ $report->nomor_surat_tugas }}</span>
                </div>
                <div>
                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Tanggal Surat Tugas</span>
                    <span class="font-semibold text-slate-900 dark:text-slate-200">{{ $report->tanggal_surat_tugas ? $report->tanggal_surat_tugas->translatedFormat('j M Y') : '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Kota Tujuan</span>
                    <span class="font-semibold text-slate-900 dark:text-slate-200">{{ $report->kota_tujuan }}</span>
                </div>
                <div>
                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Hal / Perihal Perjalanan Dinas</span>
                    <span class="font-semibold text-[#0F2747] dark:text-sky-300">{{ $report->isi_pointer ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Periode Perjalanan</span>
                    <span class="font-semibold text-slate-900 dark:text-slate-200">{{ $report->tgl_mulai_dinas->translatedFormat('j M Y') }} s.d {{ $report->tgl_selesai_dinas->translatedFormat('j M Y') }}</span>
                </div>
                <div>
                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Rincian Metode Pembayaran</span>
                    <span class="font-bold text-[#0F2747] dark:text-blue-400">
                        KKP: Rp {{ number_format($report->total_kkp, 0, ',', '.') }} | Cash: Rp {{ number_format($report->total_cash, 0, ',', '.') }}
                    </span>
                </div>
                <div>
                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Pejabat Pembuat Komitmen</span>
                    <span class="font-semibold text-slate-900 dark:text-slate-200">{{ $report->ppk->nama ?? '-' }}</span>
                </div>
            </div>

            <!-- Pelaksana -->
            <div>
                <h3 class="font-bold text-xs text-[#0F2747] dark:text-white uppercase tracking-wider mb-2 border-b border-slate-200 dark:border-slate-700 pb-1">Pelaksana Perjalanan Dinas</h3>
                <ol class="list-decimal list-inside text-xs space-y-1 text-slate-800 dark:text-slate-300">
                    @foreach ($report->daftar_pelaksana as $p)
                        <li>
                            <span class="font-bold">{{ $p['name'] }}</span>
                            <span class="text-slate-500 dark:text-slate-400">(NIP. {{ $p['nip'] }})</span>
                        </li>
                    @endforeach
                </ol>
            </div>

            <!-- Uraian Kegiatan Table -->
            <div>
                <h3 class="font-bold text-xs text-[#0F2747] dark:text-white uppercase tracking-wider mb-2 border-b border-slate-200 dark:border-slate-700 pb-1">Jadwal & Kronologis Kegiatan Harian</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border-collapse border border-slate-300 dark:border-slate-600">
                        <thead>
                            <tr class="bg-slate-100 dark:bg-[#0B1727] text-slate-700 dark:text-slate-300 font-bold">
                                <th class="border border-slate-300 dark:border-slate-600 p-2 text-center w-10">No</th>
                                <th class="border border-slate-300 dark:border-slate-600 p-2">Uraian Kegiatan</th>
                                <th class="border border-slate-300 dark:border-slate-600 p-2 w-48">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($report->schedules as $idx => $s)
                                <tr>
                                    <td class="border border-slate-300 dark:border-slate-600 p-2 text-center font-bold text-slate-800 dark:text-slate-200">{{ $idx + 1 }}</td>
                                    <td class="border border-slate-300 dark:border-slate-600 p-2">
                                        <div class="font-bold text-slate-900 dark:text-white mb-0.5">{{ $s->tanggal->format('l, d F Y') }}</div>
                                        <div class="whitespace-pre-line text-slate-700 dark:text-slate-300">{{ str_replace('➢', '-', $s->uraian_kegiatan) }}</div>
                                    </td>
                                    <td class="border border-slate-300 dark:border-slate-600 p-2 text-slate-600 dark:text-slate-400">{{ $s->lokasi_keterangan ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Rincian Pengeluaran Table (Per-Item Payment Method) -->
            @if ($report->expenses->count() > 0)
                <div>
                    <h3 class="font-bold text-xs text-[#0F2747] dark:text-white uppercase tracking-wider mb-2 border-b border-slate-200 dark:border-slate-700 pb-1">Rincian Pengeluaran & Metode Pembayaran</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left border-collapse border border-slate-300 dark:border-slate-600">
                            <thead>
                                <tr class="bg-slate-100 dark:bg-[#0B1727] text-slate-700 dark:text-slate-300 font-bold">
                                    <th class="border border-slate-300 dark:border-slate-600 p-2 text-center w-10">No</th>
                                    <th class="border border-slate-300 dark:border-slate-600 p-2">Komponen / Uraian Biaya</th>
                                    <th class="border border-slate-300 dark:border-slate-600 p-2 text-center w-36">Metode Bayar</th>
                                    <th class="border border-slate-300 dark:border-slate-600 p-2 text-right w-36">Nominal (Rp)</th>
                                    <th class="border border-slate-300 dark:border-slate-600 p-2">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @foreach ($report->expenses as $idx => $exp)
                                    <tr>
                                        <td class="border border-slate-300 dark:border-slate-600 p-2 text-center font-bold text-slate-800 dark:text-slate-200">{{ $idx + 1 }}</td>
                                        <td class="border border-slate-300 dark:border-slate-600 p-2 font-medium text-slate-900 dark:text-slate-200">{{ $exp->expense_type }}</td>
                                        <td class="border border-slate-300 dark:border-slate-600 p-2 text-center">
                                            @if ($exp->payment_method === 'kkp')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 dark:bg-blue-900/40 text-[#1769AA] dark:text-blue-300 border border-blue-200 dark:border-blue-700 uppercase">KKP</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-700 uppercase">CASH</span>
                                            @endif
                                        </td>
                                        <td class="border border-slate-300 dark:border-slate-600 p-2 text-right font-mono font-bold text-slate-800 dark:text-slate-200">
                                            Rp {{ number_format($exp->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="border border-slate-300 dark:border-slate-600 p-2 text-slate-600 dark:text-slate-400">{{ $exp->description ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-blue-50/50 dark:bg-blue-900/20 font-bold text-[#0F2747] dark:text-blue-300">
                                    <td colspan="3" class="border border-slate-300 dark:border-slate-600 p-2 text-right">Subtotal KKP (Kartu Kredit Govt):</td>
                                    <td class="border border-slate-300 dark:border-slate-600 p-2 text-right font-mono text-[#1769AA] dark:text-blue-400">Rp {{ number_format($report->total_kkp, 0, ',', '.') }}</td>
                                    <td class="border border-slate-300 dark:border-slate-600 p-2"></td>
                                </tr>
                                <tr class="bg-amber-50/50 dark:bg-amber-900/20 font-bold text-[#0F2747] dark:text-amber-300">
                                    <td colspan="3" class="border border-slate-300 dark:border-slate-600 p-2 text-right">Subtotal Cash / Reimburse (Tunai):</td>
                                    <td class="border border-slate-300 dark:border-slate-600 p-2 text-right font-mono text-amber-700 dark:text-amber-400">Rp {{ number_format($report->total_cash, 0, ',', '.') }}</td>
                                    <td class="border border-slate-300 dark:border-slate-600 p-2"></td>
                                </tr>
                                <tr class="bg-emerald-100/60 dark:bg-emerald-900/20 font-extrabold text-[#0F2747] dark:text-emerald-300">
                                    <td colspan="3" class="border border-slate-300 dark:border-slate-600 p-2 text-right uppercase">Grand Total Biaya Perjalanan:</td>
                                    <td class="border border-slate-300 dark:border-slate-600 p-2 text-right font-mono text-emerald-800 dark:text-emerald-400 text-sm">Rp {{ number_format($report->total_cost, 0, ',', '.') }}</td>
                                    <td class="border border-slate-300 dark:border-slate-600 p-2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif
            @if ($report->pointer_substansi)
                <div>
                    <h3 class="font-bold text-xs text-[#0F2747] dark:text-white uppercase tracking-wider mb-2 border-b border-slate-200 dark:border-slate-700 pb-1">Hasil Pembahasan & Catatan Substansi</h3>
                    <div class="p-4 bg-slate-50 dark:bg-[#0B1727] rounded-xl border border-slate-200 dark:border-slate-700 text-xs leading-relaxed text-slate-800 dark:text-slate-300 whitespace-pre-line">
                        {{ $report->pointer_substansi }}
                    </div>
                </div>
            @endif

            <!-- Attachments & Dokumentasi -->
            @if ($report->attachments->count() > 0)
                <div>
                    <h3 class="font-bold text-xs text-[#0F2747] dark:text-white uppercase tracking-wider mb-3 border-b border-slate-200 dark:border-slate-700 pb-1">Lampiran Berkas & Dokumentasi Foto</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach ($report->attachments as $att)
                            <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-2 bg-slate-50 dark:bg-[#0B1727] text-center text-xs space-y-1">
                                @if (in_array(strtolower(pathinfo($att->file_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']))
                                    <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank" class="block p-1 bg-white dark:bg-[#11223A] rounded-lg border border-slate-200 dark:border-slate-700">
                                        <img src="{{ asset('storage/' . $att->file_path) }}" class="max-h-48 max-w-full object-contain mx-auto rounded-md hover:opacity-90 transition">
                                    </a>
                                @else
                                    <div class="h-28 bg-slate-200 dark:bg-slate-800 rounded-lg flex items-center justify-center font-bold text-slate-500 dark:text-slate-400 uppercase">
                                        {{ pathinfo($att->file_path, PATHINFO_EXTENSION) }}
                                    </div>
                                @endif
                                <span class="font-bold text-slate-700 dark:text-slate-300 block truncate" title="{{ $att->category }}">{{ strtoupper(str_replace('_', ' ', $att->category)) }}</span>
                                <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank" class="text-[#1769AA] dark:text-blue-400 hover:underline text-[11px] font-semibold block">Buka File &rarr;</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <!-- Request Revision Modal -->
        <div x-show="revisionModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="bg-white dark:bg-[#11223A] rounded-2xl p-6 max-w-md w-full shadow-2xl space-y-4 border border-slate-200 dark:border-slate-700">
                <h3 class="font-bold text-sm text-[#0F2747] dark:text-white">Kirim Permintaan Revisi Laporan</h3>
                <form action="{{ route('reports.revision', $report->id) }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="block font-semibold mb-1 text-slate-700 dark:text-slate-300">Bagian yang Perlu Diperbaiki</label>
                        <select name="section" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-[#0B1727] text-slate-900 dark:text-slate-100 text-xs">
                            <option value="Informasi Perjalanan">Informasi Perjalanan</option>
                            <option value="Uraian Kegiatan">Uraian Kegiatan</option>
                            <option value="Dokumen Tiket / Taksi">Dokumen Tiket / Taksi</option>
                            <option value="Dokumentasi Foto">Dokumentasi Foto</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1 text-slate-700 dark:text-slate-300">Catatan Revisi untuk Pegawai <span class="text-red-500">*</span></label>
                        <textarea name="notes" rows="4" required placeholder="Tuliskan catatan perbaikan secara detail..." class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-[#0B1727] text-slate-900 dark:text-slate-100 text-xs"></textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="revisionModalOpen = false" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl font-bold">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold">Kirim Catatan Revisi</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
