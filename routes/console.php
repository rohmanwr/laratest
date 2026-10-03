<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('users:make-admin {username}', function (string $username): int {
    $user = User::where('username', $username)->first();

    if (! $user) {
        $this->error("Pengguna dengan username [{$username}] tidak ditemukan.");

        return self::FAILURE;
    }

    $user->update(['role' => 'admin']);

    $this->info("{$user->username} sekarang memiliki role admin.");

    return self::SUCCESS;
})->purpose('Berikan hak akses admin kepada pengguna yang sudah ada');
