<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-emerald-600">Seller center · Katalog produk</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">Manajemen produk</h1>
                <p class="mt-1 text-sm text-gray-500">Tambah, edit, dan pantau barang marketplace dalam satu halaman.</p>
            </div>
            <a href="{{ route('products.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14" />
                </svg>
                Tambah produk
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 py-8 sm:py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800" role="status">
                    <svg class="h-5 w-5 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6" />
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <section aria-label="Ringkasan produk" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total produk</p>
                            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($totalProducts, 0, ',', '.') }}</p>
                        </div>
                        <span class="rounded-xl bg-blue-50 p-3 text-blue-600">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m12 3 9 5-9 5-9-5 9-5Zm-9 9 9 5 9-5M3 16l9 5 9-5" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">Semua barang di katalogmu</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Produk aktif</p>
                            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($activeProducts, 0, ',', '.') }}</p>
                        </div>
                        <span class="rounded-xl bg-emerald-50 p-3 text-emerald-600">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">Siap ditampilkan di marketplace</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Stok menipis</p>
                            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($lowStockProducts, 0, ',', '.') }}</p>
                        </div>
                        <span class="rounded-xl bg-amber-50 p-3 text-amber-600">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 4h.01M10.3 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.7 3.86a2 2 0 0 0-3.4 0Z" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">Produk dengan stok 5 atau kurang</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Nilai persediaan</p>
                            <p class="mt-3 text-2xl font-bold tracking-tight text-slate-900">Rp{{ number_format((float) $inventoryValue, 0, ',', '.') }}</p>
                        </div>
                        <span class="rounded-xl bg-violet-50 p-3 text-violet-600">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v12m4-9.5c0-1.38-1.79-2.5-4-2.5s-4 1.12-4 2.5 1.79 2.5 4 2.5 4 1.12 4 2.5-1.79 2.5-4 2.5-4-1.12-4-2.5" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">Perkiraan harga dikali jumlah stok</p>
                </div>
            </section>

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 p-5 sm:p-6">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Index produk</h2>
                            <p class="mt-1 text-sm text-slate-500">Data produk dan aksi CRUD tersedia langsung di halaman ini.</p>
                        </div>
                        <form method="GET" action="{{ route('dashboard') }}" class="grid gap-2 sm:grid-cols-3">
                            <label class="sr-only" for="search-products">Cari produk atau SKU</label>
                            <div class="relative sm:col-span-1">
                                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                    <circle cx="11" cy="11" r="7" stroke-width="2" />
                                    <path stroke-linecap="round" stroke-width="2" d="m20 20-4-4" />
                                </svg>
                                <input id="search-products" type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama atau SKU"
                                    class="w-full rounded-xl border-slate-200 py-2.5 pl-9 pr-3 text-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <label class="sr-only" for="filter-category">Filter kategori</label>
                            <select id="filter-category" name="category" class="rounded-xl border-slate-200 py-2.5 text-sm text-slate-600 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Semua kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                                @endforeach
                            </select>
                            <div class="flex gap-2">
                                <label class="sr-only" for="filter-status">Filter status</label>
                                <select id="filter-status" name="status" class="min-w-0 flex-1 rounded-xl border-slate-200 py-2.5 text-sm text-slate-600 focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="">Semua status</option>
                                    <option value="active" @selected(request('status') === 'active')>Aktif</option>
                                    <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                                </select>
                                <button type="submit" class="rounded-xl border border-slate-200 px-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Cari</button>
                            </div>
                        </form>
                    </div>
                </div>

                @if ($products->isEmpty())
                    <div class="px-6 py-16 text-center">
                        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="m12 3 9 5-9 5-9-5 9-5Zm-9 9 9 5 9-5M3 16l9 5 9-5" />
                            </svg>
                        </span>
                        <h3 class="mt-4 text-base font-semibold text-slate-900">{{ request()->hasAny(['q', 'category', 'status']) ? 'Produk tidak ditemukan' : 'Belum ada produk' }}</h3>
                        <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">
                            {{ request()->hasAny(['q', 'category', 'status']) ? 'Coba ubah kata kunci atau filter pencarianmu.' : 'Mulai isi katalog marketplace-mu dengan menambahkan produk pertama.' }}
                        </p>
                        @if (request()->hasAny(['q', 'category', 'status']))
                            <a href="{{ route('products.index') }}" class="mt-5 inline-flex rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Reset pencarian</a>
                        @else
                            <a href="{{ route('products.create') }}" class="mt-5 inline-flex rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">Tambah produk pertama</a>
                        @endif
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[760px] text-left">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <tr>
                                    <th scope="col" class="px-6 py-3.5">Produk</th>
                                    <th scope="col" class="px-4 py-3.5">Marketplace</th>
                                    <th scope="col" class="px-4 py-3.5">Kategori</th>
                                    <th scope="col" class="px-4 py-3.5">Harga</th>
                                    <th scope="col" class="px-4 py-3.5">Stok</th>
                                    <th scope="col" class="px-4 py-3.5">Status</th>
                                    <th scope="col" class="px-6 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($products as $product)
                                    <tr class="transition hover:bg-slate-50/70">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-sm font-bold text-emerald-700">
                                                    {{ mb_strtoupper(mb_substr($product->name, 0, 1)) }}
                                                </span>
                                                <div class="min-w-0">
                                                    <p class="truncate font-semibold text-slate-900">{{ $product->name }}</p>
                                                    <p class="mt-0.5 text-xs text-slate-500">Kode: {{ $product->sku ?: '-' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-slate-600">{{ $product->marketplace ?: '-' }}</td>
                                        <td class="px-4 py-4 text-sm text-slate-600">{{ $product->category }}</td>
                                        <td class="px-4 py-4 text-sm font-semibold text-slate-800">Rp{{ number_format((float) $product->price, 0, ',', '.') }}</td>
                                        <td class="px-4 py-4">
                                            <span class="text-sm font-medium {{ $product->stock <= 5 ? 'text-amber-700' : 'text-slate-700' }}">{{ number_format($product->stock, 0, ',', '.') }} unit</span>
                                            @if ($product->stock <= 5)
                                                <span class="ml-1 text-xs text-amber-600">Menipis</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4">
                                            @if ($product->status === 'active')
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>Draft
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('products.edit', $product) }}"
                                                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:border-rose-200 hover:bg-rose-50">
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
                    @if ($products->hasPages())
                        <div class="border-t border-slate-100 px-5 py-4 sm:px-6">
                            {{ $products->links() }}
                        </div>
                    @endif
                @endif
            </section>
        </div>

    </div>
</x-app-layout>
