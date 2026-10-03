<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $images = $user->archivedImages()
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->trim()->toString();

                $query->where(function ($query) use ($search) {
                    $query->where('original_name', 'like', "%{$search}%")
                        ->orWhere('caption', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $editingNote = null;
        if ($request->filled('edit_note')) {
            $editingNote = $user->notes()->findOrFail($request->integer('edit_note'));
        }

        return view('dashboard', [
            'images' => $images,
            'notes' => $user->notes()->latest()->limit(6)->get(),
            'editingNote' => $editingNote,
            'totalImageBytes' => $user->archivedImages()->sum('size'),
        ]);
    }
}
