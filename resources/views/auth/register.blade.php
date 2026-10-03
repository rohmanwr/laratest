<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" value="Nama lengkap" />
            <x-text-input id="name" class="mt-1 block w-full rounded-xl border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama lengkap" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="username" value="Username" />
            <x-text-input id="username" class="mt-1 block w-full rounded-xl border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700" type="text" name="username" :value="old('username')" required minlength="3" maxlength="30" autocomplete="username" autocapitalize="none" spellcheck="false" placeholder="Contoh: nama_pengguna" />
            <p class="mt-1 text-xs text-slate-600">3–30 karakter; gunakan huruf, angka, tanda hubung, atau garis bawah.</p>
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="Alamat email" />
            <x-text-input id="email" class="mt-1 block w-full rounded-xl border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700" type="email" name="email" :value="old('email')" required autocomplete="email" placeholder="nama@contoh.com" />
            <p class="mt-1 text-xs text-slate-600">Email digunakan untuk verifikasi dan pemulihan password.</p>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" class="mt-1 block w-full rounded-xl border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="Buat password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi password" />
            <x-text-input id="password_confirmation" class="mt-1 block w-full rounded-xl border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-5 flex flex-col-reverse items-stretch gap-4 sm:flex-row sm:items-center sm:justify-between">
            <a class="rounded-md text-sm font-medium text-emerald-800 underline hover:text-emerald-950 focus:outline-none focus:ring-2 focus:ring-emerald-700" href="{{ route('login') }}">
                Sudah punya akun? Masuk
            </a>

            <x-primary-button class="justify-center rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold hover:bg-emerald-800 focus:bg-emerald-800 active:bg-emerald-900">
                Buat akun
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
