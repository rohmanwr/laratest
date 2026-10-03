<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ruang Data') }} · Pusat data pribadi</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-700">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-800 text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7.5 12 3l9 4.5-9 4.5-9-4.5Zm0 5L12 17l9-4.5M3 17.5l9 4.5 9-4.5" /></svg>
                </span>
                <span class="text-base font-bold tracking-tight">Ruang Data</span>
            </a>
            <nav aria-label="Navigasi akun" class="flex items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800">Buka pusat data</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800">Buat akun</a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    <main>
        <section class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-16 sm:px-6 sm:py-24 lg:grid-cols-[1.05fr_0.95fr] lg:px-8">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-sm font-semibold text-emerald-900">
                    <span class="h-2 w-2 rounded-full bg-emerald-700"></span>
                    Arsip privat untuk keseharianmu
                </span>
                <h1 class="mt-6 max-w-2xl text-4xl font-bold leading-tight tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">Simpan yang penting. Temukan dengan mudah.</h1>
                <p class="mt-5 max-w-xl text-lg leading-8 text-slate-700">Satu ruang yang rapi untuk mengumpulkan gambar dan mencatat ide, pengingat, serta informasi pentingmu.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">Buka pusat data <span aria-hidden="true">→</span></a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">Masuk ke ruang data <span aria-hidden="true">→</span></a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-800 transition hover:bg-slate-100">Buat akun baru</a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-xl shadow-slate-200/70 sm:p-7">
                <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                    <div>
                        <p class="text-xs font-bold tracking-wider text-emerald-800">RUANG DATA</p>
                        <h2 class="mt-1 text-lg font-bold text-slate-950">Semua tersusun rapi</h2>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-800" aria-hidden="true">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5Zm0 12 5-5 4 4 3-3 4 4" /></svg>
                    </span>
                </div>
                <div class="grid gap-4 py-5 sm:grid-cols-2">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-900" aria-hidden="true">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5Zm0 12 5-5 4 4 3-3 4 4M9 8h.01" /></svg>
                        </span>
                        <h3 class="mt-3 font-bold text-slate-950">Arsip gambar</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-700">Unggah banyak gambar sekaligus dan cari kembali saat dibutuhkan.</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-950" aria-hidden="true">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 4h11a2 2 0 0 1 2 2v13a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Zm-3 4H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h11M10 9h6m-6 4h6m-6 4h4" /></svg>
                        </span>
                        <h3 class="mt-3 font-bold text-slate-950">Catatan pribadi</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-700">Tulis dan kelola informasi penting dalam tampilan yang mudah dibaca.</p>
                    </div>
                </div>
                <p class="border-t border-slate-200 pt-4 text-sm text-slate-600">File gambar disimpan secara privat dan dikelola dari satu dashboard.</p>
            </div>
        </section>
    </main>
    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-5 text-sm text-slate-600 sm:px-6 lg:px-8">Ruang Data · Penyimpanan gambar dan catatan pribadimu.</div>
    </footer>
</body>
</html>
