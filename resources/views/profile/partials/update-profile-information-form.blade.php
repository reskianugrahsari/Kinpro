<section>
    <div class="border-b border-slate-100 dark:border-slate-800 pb-3 mb-5">
        <h3 class="text-sm font-bold text-[#0F2747] dark:text-slate-100">
            Informasi Profil & Kepegawaian
        </h3>
        <p class="text-xs text-slate-400 dark:text-slate-400 mt-0.5">
            Perbarui nama akun, alamat email, jabatan, serta unit kerja Anda.
        </p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
            <input id="name" name="name" type="text" class="w-full border border-slate-300 dark:border-slate-700 dark:bg-slate-800/90 dark:text-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-[#1769AA] focus:border-[#1769AA] outline-none" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Alamat Email</label>
            <input id="email" name="email" type="email" class="w-full border border-slate-300 dark:border-slate-700 dark:bg-slate-800/90 dark:text-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-[#1769AA] focus:border-[#1769AA] outline-none" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-xs text-amber-700">
                        Email Anda belum diverifikasi.
                        <button form="send-verification" class="underline text-xs text-[#1769AA] hover:text-[#0F2747] font-semibold">
                            Kirim ulang email verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 font-semibold text-xs text-emerald-600">
                            Tautan verifikasi baru telah dikirimkan ke alamat email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        @if($pegawai)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                <div>
                    <label for="jabatan" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Jabatan</label>
                    <input id="jabatan" name="jabatan" type="text" class="w-full border border-slate-300 dark:border-slate-700 dark:bg-slate-800/90 dark:text-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-[#1769AA] focus:border-[#1769AA] outline-none" value="{{ old('jabatan', $pegawai->jabatan) }}" placeholder="Contoh: Analis SDM Aparatur Ahli Muda" />
                    <x-input-error class="mt-1" :messages="$errors->get('jabatan')" />
                </div>

                <div>
                    <label for="unit_kerja_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Unit Kerja</label>
                    <select id="unit_kerja_id" name="unit_kerja_id" class="w-full border border-slate-300 dark:border-slate-700 dark:bg-slate-800/90 dark:text-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-[#1769AA] focus:border-[#1769AA] outline-none">
                        <option value="">-- Pilih Unit Kerja --</option>
                        @foreach($unitKerjas ?? [] as $uk)
                            <option value="{{ $uk->id }}" {{ old('unit_kerja_id', $pegawai->unit_kerja_id) == $uk->id ? 'selected' : '' }}>
                                {{ $uk->nama_unit ?? $uk->nama }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-1" :messages="$errors->get('unit_kerja_id')" />
                </div>
            </div>
        @endif

        <div class="pt-2 flex items-center justify-between">
            <button type="submit" class="px-5 py-2.5 bg-[#1769AA] hover:bg-[#0F2747] text-white text-xs font-bold rounded-xl shadow-xs transition cursor-pointer">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <span
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-lg border border-emerald-200"
                >Tersimpan!</span>
            @endif
        </div>
    </form>
</section>
