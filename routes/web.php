<?php

use App\Http\Controllers\ArchivedImageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'active', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'active'])->group(function () {
    Route::prefix('users')->name('users.')->middleware('admin')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/create', [UserManagementController::class, 'create'])->name('create');
        Route::post('/', [UserManagementController::class, 'store'])->name('store');
        Route::patch('/{user}/role', [UserManagementController::class, 'updateRole'])->name('role.update');
        Route::patch('/{user}/status', [UserManagementController::class, 'updateStatus'])->name('status.update');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
        Route::get('/{user}/password/reset', [UserManagementController::class, 'editPassword'])->name('password.edit');
        Route::put('/{user}/password/reset', [UserManagementController::class, 'updatePassword'])->name('password.update');
    });
    Route::get('/images/create', [ArchivedImageController::class, 'create'])->name('images.create');
    Route::post('/images', [ArchivedImageController::class, 'store'])->name('images.store');
    Route::get('/images/{archivedImage}/file', [ArchivedImageController::class, 'file'])->name('images.file');
    Route::delete('/images/{archivedImage}', [ArchivedImageController::class, 'destroy'])->name('images.destroy');
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::put('/notes/{note}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
