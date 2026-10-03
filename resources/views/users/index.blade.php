<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-emerald-700">ADMINISTRATOR</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Manajemen pengguna</h1>
                <p class="mt-1 text-sm text-slate-600">Kelola akun, hak akses, dan reset password.</p>
            </div>
            <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:ring-offset-2">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14" /></svg>
                Tambah user baru
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50">
        <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900" role="status">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900" role="alert">
                    <p class="font-semibold">Perubahan tidak dapat disimpan.</p>
                    <ul class="mt-1 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section aria-label="Ringkasan pengguna" class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-600">Total pengguna</p>
                    <p class="mt-2 text-3xl font-bold text-slate-950">{{ number_format($totalUsers, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-600">Administrator aktif</p>
                    <p class="mt-2 text-3xl font-bold text-slate-950">{{ number_format($totalAdmins, 0, ',', '.') }}</p>
                </div>
            </section>

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-4 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950">Daftar pengguna</h2>
                        <p class="mt-1 text-sm text-slate-600">Atur hak akses tiap akun. User tidak dapat membuka halaman ini.</p>
                    </div>
                    <form method="GET" action="{{ route('users.index') }}" class="flex gap-2 sm:w-96">
                        <label for="user-search" class="sr-only">Cari pengguna</label>
                        <input id="user-search" type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama, username, atau email"
                            class="min-w-0 flex-1 rounded-xl border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700">
                        <button type="submit" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cari</button>
                    </form>
                </div>

                @if ($users->isEmpty())
                    <div class="px-6 py-14 text-center">
                        <h3 class="font-semibold text-slate-900">Pengguna tidak ditemukan</h3>
                        <p class="mt-1 text-sm text-slate-600">Coba kata kunci pencarian yang lain.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[850px] text-left">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-600">
                                <tr>
                                    <th scope="col" class="px-5 py-3.5 sm:px-6">Pengguna</th>
                                    <th scope="col" class="px-4 py-3.5">Email</th>
                                    <th scope="col" class="px-4 py-3.5">Dibuat</th>
                                    <th scope="col" class="px-4 py-3.5">Hak akses</th>
                                    <th scope="col" class="px-5 py-3.5 text-right sm:px-6">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach ($users as $user)
                                    <tr class="align-top hover:bg-slate-50/70">
                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center gap-3">
                                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-900">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</span>
                                                <div class="min-w-0">
                                                    <p class="font-semibold text-slate-900">{{ $user->name }} @if ($user->is(auth()->user()))<span class="text-xs font-medium text-slate-500">(Anda)</span>@endif</p>
                                                    <p class="mt-0.5 text-sm text-slate-600">{{ '@'.$user->username }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-slate-700">{{ $user->email }}</td>
                                        <td class="px-4 py-4 text-sm text-slate-700">{{ $user->created_at->format('d M Y') }}</td>
                                        <td class="px-4 py-4">
                                            <form method="POST" action="{{ route('users.role.update', $user) }}" class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <label for="role-{{ $user->id }}" class="sr-only">Hak akses {{ $user->username }}</label>
                                                <select id="role-{{ $user->id }}" name="role" class="rounded-lg border-slate-300 py-1.5 pl-2 pr-7 text-sm focus:border-emerald-700 focus:ring-emerald-700">
                                                    <option value="user" @selected($user->role === 'user')>User</option>
                                                    <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                                </select>
                                                <button type="submit" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-emerald-800 hover:bg-emerald-50">Simpan</button>
                                            </form>
                                        </td>
                                        <td class="px-5 py-4 text-right sm:px-6">
                                            <a href="{{ route('users.password.edit', $user) }}" class="inline-flex whitespace-nowrap rounded-lg border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-700">Reset password</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($users->hasPages())
                        <div class="border-t border-slate-200 px-5 py-4 sm:px-6">{{ $users->links() }}</div>
                    @endif
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
