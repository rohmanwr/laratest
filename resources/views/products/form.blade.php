<x-app-layout>
    @php($isEditing = isset($product))

    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-emerald-600">Seller center · Katalog produk</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">
                    {{ $isEditing ? 'Edit produk' : 'Tambah produk' }}
                </h1>
                <p class="mt-1 text-sm text-gray-500">Lengkapi informasi barang dan marketplace tempat kamu berjualan.</p>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                Kembali ke daftar
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 py-8 sm:py-10">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <form method="POST"
                action="{{ $isEditing ? route('products.update', $product) : route('products.store') }}"
                class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                @csrf
                @if ($isEditing)
                    @method('PUT')
                @endif

                <div class="border-b border-slate-100 px-6 py-5 sm:px-8">
                    <h2 class="text-lg font-bold text-slate-900">Informasi barang</h2>
                    <p class="mt-1 text-sm text-slate-500">Kolom bertanda <span class="text-rose-500">*</span> wajib diisi.</p>
                </div>

                <div class="grid gap-5 px-6 py-6 sm:grid-cols-2 sm:px-8">
                    @if ($errors->any())
                        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 sm:col-span-2" role="alert">
                            <p class="font-semibold">Data belum bisa disimpan.</p>
                            <ul class="mt-1 list-inside list-disc">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div>
                        <label for="product-sku" class="mb-1.5 block text-sm font-medium text-slate-700">Kode barang <span class="text-rose-500">*</span></label>
                        <input id="product-sku" type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" required maxlength="50" placeholder="Contoh: BRG-001"
                            class="w-full rounded-xl border-slate-200 text-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-emerald-500">
                        @error('sku')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="product-marketplace" class="mb-1.5 block text-sm font-medium text-slate-700">Marketplace <span class="text-rose-500">*</span></label>
                        <select id="product-marketplace" name="marketplace" required
                            class="w-full rounded-xl border-slate-200 text-sm text-slate-700 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="" disabled @selected(old('marketplace', $product->marketplace ?? '') === '')>Pilih marketplace</option>
                            @foreach ($marketplaces as $marketplace)
                                <option value="{{ $marketplace }}" @selected(old('marketplace', $product->marketplace ?? '') === $marketplace)>{{ $marketplace }}</option>
                            @endforeach
                        </select>
                        @error('marketplace')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="product-name" class="mb-1.5 block text-sm font-medium text-slate-700">Nama barang <span class="text-rose-500">*</span></label>
                        <input id="product-name" type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required maxlength="150" placeholder="Contoh: Tas selempang kanvas"
                            class="w-full rounded-xl border-slate-200 text-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-emerald-500">
                        @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="product-price" class="mb-1.5 block text-sm font-medium text-slate-700">Harga barang <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-sm font-medium text-slate-500">Rp</span>
                            <input id="product-price" type="number" name="price" value="{{ old('price', isset($product) ? (int) $product->price : '') }}" required min="0" max="999999999" step="1" placeholder="0"
                                class="w-full rounded-xl border-slate-200 pl-10 text-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                        <p class="mt-1 text-xs text-slate-400">Masukkan nominal Rupiah tanpa titik pemisah.</p>
                        @error('price')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="product-category" class="mb-1.5 block text-sm font-medium text-slate-700">Kategori <span class="text-rose-500">*</span></label>
                        <select id="product-category" name="category" required
                            class="w-full rounded-xl border-slate-200 text-sm text-slate-700 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="" disabled @selected(old('category', $product->category ?? '') === '')>Pilih kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" @selected(old('category', $product->category ?? '') === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                        @error('category')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="product-stock" class="mb-1.5 block text-sm font-medium text-slate-700">Jumlah stok <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input id="product-stock" type="number" name="stock" value="{{ old('stock', $product->stock ?? '') }}" required min="0" max="99999999" step="1" placeholder="0"
                                class="w-full rounded-xl border-slate-200 pr-14 text-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-emerald-500">
                            <span class="absolute inset-y-0 right-3 flex items-center text-sm text-slate-400">unit</span>
                        </div>
                        @error('stock')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="product-status" class="mb-1.5 block text-sm font-medium text-slate-700">Status produk <span class="text-rose-500">*</span></label>
                        <select id="product-status" name="status" required
                            class="w-full rounded-xl border-slate-200 text-sm text-slate-700 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="active" @selected(old('status', $product->status ?? 'active') === 'active')>Aktif</option>
                            <option value="draft" @selected(old('status', $product->status ?? 'active') === 'draft')>Draft</option>
                        </select>
                        @error('status')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="product-description" class="mb-1.5 block text-sm font-medium text-slate-700">Deskripsi <span class="font-normal text-slate-400">(opsional)</span></label>
                        <textarea id="product-description" name="description" rows="4" maxlength="1000" placeholder="Tambahkan detail atau keunggulan barang..."
                            class="w-full rounded-xl border-slate-200 text-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-emerald-500">{{ old('description', $product->description ?? '') }}</textarea>
                        @error('description')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4 sm:flex-row sm:justify-end sm:px-8">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Batal</a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        {{ $isEditing ? 'Simpan perubahan' : 'Simpan produk' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
