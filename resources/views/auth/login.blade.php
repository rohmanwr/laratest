<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="username" value="Username" />
            <x-text-input id="username" class="mt-1 block w-full rounded-xl border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" autocapitalize="none" spellcheck="false" placeholder="Masukkan username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" class="mt-1 block w-full rounded-xl border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="Masukkan password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-emerald-700 shadow-sm focus:ring-emerald-700" name="remember">
                <span class="ms-2 text-sm text-slate-600">Ingat saya</span>
            </label>
        </div>

        <div class="mt-5 flex flex-col-reverse items-stretch gap-4 sm:flex-row sm:items-center sm:justify-between">
            @if (Route::has('password.request'))
                <a class="rounded-md text-sm font-medium text-emerald-800 underline hover:text-emerald-950 focus:outline-none focus:ring-2 focus:ring-emerald-700" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif

            <x-primary-button class="justify-center rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold hover:bg-emerald-800 focus:bg-emerald-800 active:bg-emerald-900">
                Masuk
            </x-primary-button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="mt-6 border-t border-slate-200 pt-5 text-center">
            <span class="text-sm text-slate-600">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="ms-1 rounded text-sm font-semibold text-emerald-800 underline hover:text-emerald-950 focus:outline-none focus:ring-2 focus:ring-emerald-700">
                Daftar sekarang
            </a>
        </div>
    @endif
</x-guest-layout>
