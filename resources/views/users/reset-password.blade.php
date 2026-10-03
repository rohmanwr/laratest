<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-emerald-700">MANAJEMEN PENGGUNA</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Reset password</h1>
            <p class="mt-1 text-sm text-slate-600">Atur password baru untuk akun {{ $user->username }}.</p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl">
            <form method="POST" action="{{ route('users.password.update', $user) }}" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                @csrf
                @method('PUT')
                <div class="space-y-5 p-5 sm:p-8">
                    @if ($errors->any())
                        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900" role="alert">
                            <p class="font-semibold">Password belum dapat diubah.</p>
                            <ul class="mt-1 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-sm font-semibold text-slate-900">{{ $user->name }} · {{ '@'.$user->username }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-800">Password baru</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password" class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700">
                        @error('password')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-slate-800">Konfirmasi password baru</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700">
                    </div>
                </div>
                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-5 py-4 sm:flex-row sm:justify-end sm:px-8">
                    <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:ring-offset-2">Simpan password baru</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
