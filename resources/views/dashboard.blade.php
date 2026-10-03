<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-emerald-700">RUANG DATA PRIBADI</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">Pusat data</h1>
                <p class="mt-1 text-sm text-slate-600">Simpan gambar dan catatan penting dalam satu tempat yang rapi.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m16 0v-2a4 4 0 0 0-3-3.87M14 3.13a4 4 0 0 1 0 7.75M14 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" /></svg>
                    Tambah pengguna
                </a>
                <a href="{{ route('images.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:ring-offset-2">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16V4m0 0L7 9m5-5 5 5M5 14v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-5" /></svg>
                    Unggah gambar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50">
        <div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900" role="status">
                    <svg class="h-5 w-5 shrink-0 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6" /></svg>
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900" role="alert">
                    <p class="font-semibold">Ada data yang perlu diperiksa.</p>
                    <ul class="mt-1 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section aria-label="Ringkasan penyimpanan" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-600">Total gambar</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-slate-950">{{ number_format($totalImages, 0, ',', '.') }}</p>
                    <p class="mt-1 text-sm text-slate-500">File tersimpan di arsip</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-600">Ruang terpakai</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-slate-950">
                        @if ($totalImageBytes >= 1073741824)
                            {{ number_format($totalImageBytes / 1073741824, 1, ',', '.') }} GB
                        @elseif ($totalImageBytes >= 1048576)
                            {{ number_format($totalImageBytes / 1048576, 1, ',', '.') }} MB
                        @elseif ($totalImageBytes >= 1024)
                            {{ number_format($totalImageBytes / 1024, 0, ',', '.') }} KB
                        @else
                            {{ number_format($totalImageBytes, 0, ',', '.') }} B
                        @endif
                    </p>
                    <p class="mt-1 text-sm text-slate-500">Ukuran semua gambar tersimpan</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-600">Diunggah bulan ini</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-slate-950">{{ number_format($recentImages, 0, ',', '.') }}</p>
                    <p class="mt-1 text-sm text-slate-500">Gambar baru bulan ini</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-600">Catatan</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-slate-950">{{ number_format($totalNotes, 0, ',', '.') }}</p>
                    <p class="mt-1 text-sm text-slate-500">Catatan yang kamu simpan</p>
                </div>
            </section>

            <section id="images" class="scroll-mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-4 border-b border-slate-200 px-5 py-5 sm:px-6 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950">Arsip gambar</h2>
                        <p class="mt-1 text-sm text-slate-600">Gambar hanya dapat dilihat oleh akun yang mengunggahnya.</p>
                    </div>
                    <form method="GET" action="{{ route('dashboard') }}" class="flex w-full gap-2 lg:max-w-md">
                        <label for="image-search" class="sr-only">Cari nama file atau keterangan gambar</label>
                        <input id="image-search" type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama file atau keterangan"
                            class="min-w-0 flex-1 rounded-xl border-slate-300 text-sm text-slate-900 placeholder:text-slate-500 focus:border-emerald-700 focus:ring-emerald-700">
                        <button type="submit" class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-700">Cari</button>
                    </form>
                </div>

                @if ($images->isEmpty())
                    <div class="px-6 py-16 text-center">
                        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-800">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5Zm0 12 5-5 4 4 3-3 4 4M9 8h.01" /></svg>
                        </span>
                        <h3 class="mt-4 text-base font-semibold text-slate-950">{{ request()->filled('q') ? 'Gambar tidak ditemukan' : 'Arsip gambar masih kosong' }}</h3>
                        <p class="mx-auto mt-1 max-w-md text-sm text-slate-600">{{ request()->filled('q') ? 'Coba kata kunci lain atau hapus pencarian.' : 'Unggah beberapa gambar sekaligus untuk mulai membangun arsip pribadimu.' }}</p>
                        @if (request()->filled('q'))
                            <a href="{{ route('dashboard') }}#images" class="mt-5 inline-flex rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Hapus pencarian</a>
                        @else
                            <a href="{{ route('images.create') }}" class="mt-5 inline-flex rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800">Unggah gambar pertama</a>
                        @endif
                    </div>
                @else
                    <div class="grid gap-4 p-5 sm:grid-cols-2 sm:p-6 lg:grid-cols-3 xl:grid-cols-4">
                        @foreach ($images as $image)
                            <article class="group overflow-hidden rounded-xl border border-slate-200 bg-white">
                                <a href="{{ route('images.file', $image) }}" target="_blank" rel="noopener" aria-label="Buka gambar {{ $image->original_name }}" class="block aspect-[4/3] overflow-hidden bg-slate-100">
                                    <img src="{{ route('images.file', $image) }}" alt="{{ $image->caption ?: $image->original_name }}" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]">
                                </a>
                                <div class="p-3.5">
                                    <p class="truncate text-sm font-semibold text-slate-900" title="{{ $image->original_name }}">{{ $image->original_name }}</p>
                                    @if ($image->caption)
                                        <p class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $image->caption }}</p>
                                    @endif
                                    <div class="mt-3 flex items-center justify-between gap-2">
                                        <span class="text-xs text-slate-500">{{ $image->created_at->format('d M Y') }} · {{ number_format($image->size / 1048576, 1, ',', '.') }} MB</span>
                                        <form method="POST" action="{{ route('images.destroy', $image) }}" onsubmit="return confirm('Hapus gambar ini dari arsip?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-600">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    @if ($images->hasPages())
                        <div class="border-t border-slate-200 px-5 py-4 sm:px-6">{{ $images->links() }}</div>
                    @endif
                @endif
            </section>

            <section id="notes" class="scroll-mt-8 grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,0.8fr)]">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="mb-5">
                        <h2 class="text-lg font-bold text-slate-950">{{ $editingNote ? 'Edit catatan' : 'Catatan baru' }}</h2>
                        <p class="mt-1 text-sm text-slate-600">Simpan ide, pengingat, atau informasi penting.</p>
                    </div>
                    <form method="POST" action="{{ $editingNote ? route('notes.update', $editingNote) : route('notes.store') }}" class="space-y-4">
                        @csrf
                        @if ($editingNote)
                            @method('PUT')
                        @endif
                        <div>
                            <label for="note-title" class="mb-1.5 block text-sm font-semibold text-slate-800">Judul</label>
                            <input id="note-title" name="title" type="text" required maxlength="120" value="{{ old('title', $editingNote?->title) }}" placeholder="Contoh: Ide untuk minggu depan"
                                class="w-full rounded-xl border-slate-300 text-sm text-slate-900 placeholder:text-slate-500 focus:border-emerald-700 focus:ring-emerald-700">
                            @error('title')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="note-body" class="mb-1.5 block text-sm font-semibold text-slate-800">Isi catatan</label>
                            <textarea id="note-body" name="body" rows="5" required maxlength="5000" placeholder="Tulis catatanmu di sini..."
                                class="w-full rounded-xl border-slate-300 text-sm text-slate-900 placeholder:text-slate-500 focus:border-emerald-700 focus:ring-emerald-700">{{ old('body', $editingNote?->body) }}</textarea>
                            @error('body')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:ring-offset-2">{{ $editingNote ? 'Simpan perubahan' : 'Simpan catatan' }}</button>
                            @if ($editingNote)
                                <a href="{{ route('dashboard') }}#notes" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="mb-4 flex items-end justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950">Catatan tersimpan</h2>
                            <p class="mt-1 text-sm text-slate-600">Enam catatan terbaru</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ $totalNotes }} catatan</span>
                    </div>
                    @forelse ($notes as $note)
                        <article class="border-t border-slate-200 py-4 first:border-t-0 first:pt-0">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="break-words text-sm font-semibold text-slate-900">{{ $note->title }}</h3>
                                    <p class="mt-1 whitespace-pre-line break-words text-sm leading-6 text-slate-700">{{ $note->body }}</p>
                                    <p class="mt-2 text-xs text-slate-500">Diperbarui {{ $note->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="flex shrink-0 items-center gap-1">
                                    <a href="{{ route('dashboard', ['edit_note' => $note->id]) }}#notes" class="rounded-lg px-2 py-1 text-xs font-semibold text-emerald-800 hover:bg-emerald-50">Edit</a>
                                    <form method="POST" action="{{ route('notes.destroy', $note) }}" onsubmit="return confirm('Hapus catatan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg px-2 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-50">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-xl bg-slate-50 px-4 py-8 text-center">
                            <p class="text-sm font-semibold text-slate-800">Belum ada catatan</p>
                            <p class="mt-1 text-sm text-slate-600">Catatan yang kamu simpan akan muncul di sini.</p>
                        </div>
                    @endforelse
                    @if ($totalNotes > 6)
                        <p class="border-t border-slate-200 pt-3 text-xs text-slate-500">Menampilkan 6 catatan terbaru.</p>
                    @endif
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
