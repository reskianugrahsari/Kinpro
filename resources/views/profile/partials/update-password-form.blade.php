<section>
    <div class="border-b border-slate-100 dark:border-slate-800 pb-3 mb-5">
        <h3 class="text-sm font-bold text-[#0F2747] dark:text-slate-100">
            Perbarui Kata Sandi
        </h3>
        <p class="text-xs text-slate-400 dark:text-slate-400 mt-0.5">
            Gunakan kombinasi kata sandi yang kuat dan aman untuk melindungi akun Anda.
        </p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Kata Sandi Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full border border-slate-300 dark:border-slate-700 dark:bg-slate-800/90 dark:text-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-[#1769AA] focus:border-[#1769AA] outline-none" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Kata Sandi Baru</label>
            <input id="update_password_password" name="password" type="password" class="w-full border border-slate-300 dark:border-slate-700 dark:bg-slate-800/90 dark:text-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-[#1769AA] focus:border-[#1769AA] outline-none" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi Baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full border border-slate-300 dark:border-slate-700 dark:bg-slate-800/90 dark:text-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-[#1769AA] focus:border-[#1769AA] outline-none" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="pt-2 flex items-center justify-between">
            <button type="submit" class="px-5 py-2.5 bg-[#1769AA] hover:bg-[#0F2747] text-white text-xs font-bold rounded-xl shadow-xs transition cursor-pointer">
                Perbarui Password
            </button>

            @if (session('status') === 'password-updated')
                <span
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-lg border border-emerald-200"
                >Password Berhasil Diperbarui!</span>
            @endif
        </div>
    </form>
</section>
