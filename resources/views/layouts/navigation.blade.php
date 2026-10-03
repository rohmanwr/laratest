<nav x-data="{ open: false }" class="border-b border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:ring-offset-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-800 text-white">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7.5 12 3l9 4.5-9 4.5-9-4.5Zm0 5L12 17l9-4.5M3 17.5l9 4.5 9-4.5" /></svg>
                    </span>
                    <span class="text-base font-bold tracking-tight text-slate-950">Ruang Data</span>
                </a>
                <div class="hidden items-center gap-1 sm:flex">
                    <a href="{{ route('dashboard') }}#images" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 hover:text-slate-950">Arsip gambar</a>
                    <a href="{{ route('dashboard') }}#notes" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 hover:text-slate-950">Catatan</a>
                    <a href="{{ route('users.create') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 hover:text-slate-950">Tambah pengguna</a>
                </div>
            </div>

            <div class="hidden items-center gap-3 sm:flex">
                <span class="max-w-40 truncate text-sm font-medium text-slate-700">{{ Auth::user()->name }}</span>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-700">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-900">{{ mb_strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}</span>
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.22 7.47a.75.75 0 0 1 1.06 0L10 11.19l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" /></svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profil') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Keluar') }}</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <button type="button" @click="open = !open" :aria-expanded="open.toString()" aria-label="Buka menu navigasi"
                class="inline-flex items-center justify-center rounded-xl border border-slate-300 p-2 text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-700 sm:hidden">
                <svg x-show="!open" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                <svg x-show="open" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12M18 6 6 18" /></svg>
            </button>
        </div>
    </div>

    <div x-show="open" x-cloak class="border-t border-slate-200 bg-white px-4 pb-4 pt-2 sm:hidden">
        <div class="space-y-1">
            <a href="{{ route('dashboard') }}#images" class="block rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-800 hover:bg-slate-100">Arsip gambar</a>
            <a href="{{ route('dashboard') }}#notes" class="block rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-800 hover:bg-slate-100">Catatan</a>
            <a href="{{ route('users.create') }}" class="block rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-800 hover:bg-slate-100">Tambah pengguna</a>
            <a href="{{ route('profile.edit') }}" class="block rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-800 hover:bg-slate-100">Profil</a>
        </div>
        <div class="mt-3 border-t border-slate-200 pt-3">
            <p class="px-3 text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</p>
            <p class="px-3 pt-0.5 text-sm text-slate-600">{{ Auth::user()->email }}</p>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="block w-full rounded-lg px-3 py-2.5 text-left text-sm font-semibold text-slate-700 hover:bg-slate-100">Keluar</button>
            </form>
        </div>
    </div>
</nav>
