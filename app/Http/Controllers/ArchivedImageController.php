<?php

namespace App\Http\Controllers;

use App\Models\ArchivedImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ArchivedImageController extends Controller
{
    private const MAX_UPLOAD_BYTES = 500 * 1024 * 1024;
    private const MAX_UPLOAD_KILOBYTES = 500 * 1024;

    public function create(): View
    {
        return view('images.create', [
            'maxFileBytes' => self::MAX_UPLOAD_BYTES,
            'maxTotalBytes' => self::MAX_UPLOAD_BYTES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $files = array_values(array_merge(
            (array) $request->file('images', []),
            (array) $request->file('folder_images', []),
        ));

        $data = $request->all();
        $data['images'] = $files;

        $validator = Validator::make($data, [
            'images' => ['required', 'array', 'min:1', 'max:20'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:'.self::MAX_UPLOAD_KILOBYTES],
            'caption' => ['nullable', 'string', 'max:250'],
        ], [
            'images.required' => 'Pilih setidaknya satu gambar untuk diunggah.',
            'images.max' => 'Maksimal 20 gambar dalam satu kali unggah, termasuk gambar dari folder.',
            'images.*.image' => 'File yang dipilih harus berupa gambar.',
            'images.*.mimes' => 'Format gambar yang didukung: JPG, PNG, GIF, dan WebP.',
            'images.*.max' => 'Ukuran setiap file maksimal 500 MB.',
        ]);
        $validator->after(function ($validator) use ($files): void {
            $totalBytes = array_sum(array_map(fn ($file) => $file->getSize(), $files));

            if ($totalBytes > self::MAX_UPLOAD_BYTES) {
                $validator->errors()->add(
                    'images',
                    'Total ukuran semua gambar maksimal 500 MB per unggahan.'
                );
            }
        });
        $validated = $validator->validate();

        foreach ($validated['images'] as $file) {
            $path = $file->store((string) $request->user()->id, 'archive');

            $request->user()->archivedImages()->create([
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'caption' => $validated['caption'] ?? null,
            ]);
        }

        $count = count($validated['images']);
        $message = $count === 1
            ? 'Gambar berhasil disimpan.'
            : "{$count} gambar berhasil disimpan.";

        return redirect()->route('dashboard')->with('status', $message);
    }

    public function file(Request $request, ArchivedImage $archivedImage): StreamedResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        abort_unless($archivedImage->user_id === $request->user()->id, 403);

        return response()->file(Storage::disk('archive')->path($archivedImage->path), [
            'Content-Type' => $archivedImage->mime_type,
            'Cache-Control' => 'private, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function destroy(Request $request, ArchivedImage $archivedImage): RedirectResponse
    {
        abort_unless($archivedImage->user_id === $request->user()->id, 403);

        Storage::disk('archive')->delete($archivedImage->path);
        $archivedImage->delete();

        return redirect()->route('dashboard')->with('status', 'Gambar berhasil dihapus.');
    }
}
