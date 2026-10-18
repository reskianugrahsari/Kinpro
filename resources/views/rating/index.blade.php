<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-[#0F2747] leading-tight">
            {{ __('Ranking & Rating Kinerja Pegawai') }}
        </h2>
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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($pegawais as $index => $pegawai)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200 p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <span class="bg-[#1769AA]/10 text-[#1769AA] px-3 py-1 rounded-full text-xs font-bold">
                                    #{{ $pegawais->firstItem() + $index }} Ranking
                                </span>
                                <div class="text-amber-500 font-bold flex items-center text-sm">
                                    ★ {{ number_format($pegawai->ratings_avg_rating ?? 0, 1) }} 
                                    <span class="text-slate-400 font-normal text-xs ml-1">({{ $pegawai->ratings_count ?? 0 }} review)</span>
                                </div>
                            </div>

                            <h3 class="font-bold text-lg text-[#0F2747]">{{ $pegawai->nama }}</h3>
                            <p class="text-xs text-slate-500 font-mono mb-2">NIP. {{ $pegawai->nip }}</p>
                            <p class="text-xs text-slate-600 mb-4 font-medium">{{ $pegawai->jabatan ?? 'Pegawai' }}</p>

                            @if($pegawai->ratings->count() > 0)
                                <div class="bg-slate-50 p-3 rounded-lg mb-4 space-y-2 max-h-32 overflow-y-auto border border-slate-100">
                                    @foreach($pegawai->ratings as $r)
                                        <div class="text-xs border-b border-slate-200 pb-1 last:border-0 last:pb-0">
                                            <div class="flex justify-between text-amber-500 font-bold">
                                                <span>{{ str_repeat('★', $r->rating) }}</span>
                                                @if(auth()->user()->role === 'admin' || $r->penilai_id === auth()->id())
                                                    <form action="{{ route('rating.destroy', $r) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-rose-600 hover:underline text-[10px]">Hapus</button>
                                                    </form>
                                                @endif
                                            </div>
                                            <p class="text-slate-700 italic mt-0.5">"{{ $r->komentar ?? 'Tanpa komentar' }}"</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-slate-400 italic mb-4">Belum ada ulasan rating.</p>
                            @endif
                        </div>

                        <div class="pt-4 border-t border-slate-100" x-data="{ openForm: false }">
                            <button @click="openForm = !openForm" class="w-full bg-slate-100 hover:bg-[#1769AA] hover:text-white text-[#0F2747] text-xs font-bold py-2 rounded-lg transition">
                                Berikan Rating & Komentar
                            </button>

                            <form action="{{ route('rating.store') }}" method="POST" class="mt-3 space-y-2" x-show="openForm" style="display: none;">
                                @csrf
                                <input type="hidden" name="pegawai_id" value="{{ $pegawai->id }}">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-600">Rating Bintang (1-5)</label>
                                    <select name="rating" class="w-full text-xs border-slate-300 rounded" required>
                                        <option value="5">★★★★★ (5 - Sangat Baik)</option>
                                        <option value="4">★★★★☆ (4 - Baik)</option>
                                        <option value="3">★★★☆☆ (3 - Cukup)</option>
                                        <option value="2">★★☆☆☆ (2 - Kurang)</option>
                                        <option value="1">★☆☆☆☆ (1 - Buruk)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-600">Komentar</label>
                                    <textarea name="komentar" rows="2" class="w-full text-xs border-slate-300 rounded" placeholder="Tulis ulasan kinerja..."></textarea>
                                </div>
                                <button type="submit" class="w-full bg-[#1769AA] text-white text-xs font-bold py-1.5 rounded hover:bg-[#0F2747]">Kirim</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-slate-500 py-12">
                        Belum ada data pegawai untuk diberi rating.
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $pegawais->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
