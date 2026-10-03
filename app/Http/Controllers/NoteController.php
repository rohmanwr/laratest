<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $request->user()->notes()->create($validated);

        return redirect()->to(route('dashboard').'#notes')->with('status', 'Catatan berhasil disimpan.');
    }

    public function update(Request $request, Note $note): RedirectResponse
    {
        abort_unless($note->user_id === $request->user()->id, 403);

        $note->update($this->validatedData($request));

        return redirect()->to(route('dashboard').'#notes')->with('status', 'Catatan berhasil diperbarui.');
    }

    public function destroy(Request $request, Note $note): RedirectResponse
    {
        abort_unless($note->user_id === $request->user()->id, 403);

        $note->delete();

        return redirect()->to(route('dashboard').'#notes')->with('status', 'Catatan berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'body' => ['required', 'string', 'max:5000'],
        ]);
    }
}
