<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 30)->nullable()->unique()->after('name');
        });

        $usedUsernames = [];

        DB::table('users')
            ->select(['id', 'email'])
            ->orderBy('id')
            ->get()
            ->each(function (object $user) use (&$usedUsernames): void {
                $baseUsername = Str::slug(Str::before($user->email, '@'), '_');
                $baseUsername = Str::limit($baseUsername !== '' ? $baseUsername : 'user', 24, '');

                if (strlen($baseUsername) < 3) {
                    $baseUsername .= '_user';
                }

                $username = $baseUsername;
                $suffix = 1;

                while (isset($usedUsernames[$username])) {
                    $suffixText = '_'.$suffix;
                    $username = Str::limit($baseUsername, 30 - strlen($suffixText), '').$suffixText;
                    $suffix++;
                }

                $usedUsernames[$username] = true;

                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['username' => $username]);
            });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
