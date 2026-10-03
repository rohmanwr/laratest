<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-emerald-700">ARSIP GAMBAR</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Unggah gambar</h1>
            <p class="mt-1 text-sm text-slate-600">Pilih satu atau beberapa gambar untuk disimpan secara pribadi.</p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl">
            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900" role="alert">
                    <p class="font-semibold">Gambar belum dapat diunggah.</p>
                    <ul class="mt-1 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('images.store') }}" enctype="multipart/form-data" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                @csrf
                <div class="space-y-6 p-5 sm:p-8">
                    <div>
                        <label for="images" class="mb-2 block text-sm font-semibold text-slate-800">Pilih gambar <span class="text-rose-700">*</span></label>
                        <input id="images" type="file" name="images[]" accept=".jpg,.jpeg,.png,.gif,.webp,image/jpeg,image/png,image/gif,image/webp" multiple required
                            class="block w-full cursor-pointer rounded-xl border border-slate-300 bg-white text-sm text-slate-700 file:mr-4 file:border-0 file:bg-emerald-50 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-emerald-900 hover:file:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-700">
                        <p class="mt-2 text-sm leading-6 text-slate-600">Pilih sampai 20 file sekali unggah. Format JPG, PNG, GIF, atau WebP; ukuran maksimal 10 MB per file.</p>
                        @error('images')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
                        @error('images.*')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="caption" class="mb-2 block text-sm font-semibold text-slate-800">Keterangan <span class="font-normal text-slate-500">(opsional)</span></label>
                        <input id="caption" name="caption" type="text" maxlength="250" value="{{ old('caption') }}" placeholder="Keterangan yang digunakan untuk semua gambar terpilih"
                            class="w-full rounded-xl border-slate-300 text-sm text-slate-900 placeholder:text-slate-500 focus:border-emerald-700 focus:ring-emerald-700">
                        @error('caption')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
                    </div>
                    <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm leading-6 text-blue-950">
                        File disimpan pada penyimpanan privat server dan tidak memiliki tautan publik. Hanya akunmu yang dapat melihat atau menghapus gambar tersebut.
                    </div>
                </div>
                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-5 py-4 sm:flex-row sm:justify-end sm:px-8">
                    <a href="{{ route('dashboard') }}#images" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Kembali ke arsip</a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:ring-offset-2">Simpan gambar</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
