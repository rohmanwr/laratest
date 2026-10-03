<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->trim()->toString();

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('users.index', [
            'users' => $users,
            'totalUsers' => User::count(),
            'totalAdmins' => User::where('role', 'admin')->count(),
        ]);
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'username' => Str::lower(trim((string) $request->input('username'))),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'alpha_dash', 'min:3', 'max:30', Rule::unique(User::class)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', Rule::in(['admin', 'user'])],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create($validated);

        return redirect()->route('users.index')->with('status', "Pengguna {$user->username} berhasil ditambahkan.");
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'user'])],
        ]);

        if ($user->isAdmin() && $validated['role'] === 'user' && User::where('role', 'admin')->count() <= 1) {
            return back()->withErrors([
                'role' => 'Role admin terakhir tidak dapat diubah. Tetapkan admin lain terlebih dahulu.',
            ]);
        }

        $user->update(['role' => $validated['role']]);

        if ($user->is($request->user()) && $validated['role'] === 'user') {
            return redirect()->route('dashboard')->with('status', 'Hak akses berhasil diperbarui. Kamu sekarang menggunakan akun user.');
        }

        return redirect()->route('users.index')->with('status', "Hak akses {$user->username} berhasil diperbarui.");
    }

    public function editPassword(User $user): View
    {
        return view('users.reset-password', ['user' => $user]);
    }

    public function updatePassword(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('users.index')->with('status', "Password {$user->username} berhasil direset.");
    }

    public function updateStatus(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);
        $isActive = (bool) $validated['is_active'];

        if ($user->is($request->user()) && ! $isActive) {
            return back()->withErrors([
                'account_status' => 'Kamu tidak dapat menonaktifkan akun yang sedang digunakan.',
            ]);
        }

        if ($user->isAdmin() && ! $isActive && User::where('role', 'admin')->where('is_active', true)->count() <= 1) {
            return back()->withErrors([
                'account_status' => 'Admin aktif terakhir tidak dapat dinonaktifkan.',
            ]);
        }

        $user->update(['is_active' => $isActive]);

        $status = $isActive ? 'diaktifkan kembali' : 'dinonaktifkan';

        return redirect()->route('users.index')->with('status', "Akun {$user->username} berhasil {$status}.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->withErrors([
                'account_status' => 'Kamu tidak dapat menghapus akun yang sedang digunakan.',
            ]);
        }

        if ($user->isAdmin() && $user->is_active && User::where('role', 'admin')->where('is_active', true)->count() <= 1) {
            return back()->withErrors([
                'account_status' => 'Admin aktif terakhir tidak dapat dihapus.',
            ]);
        }

        $username = $user->username;
        $user->delete();

        return redirect()->route('users.index')->with('status', "Pengguna {$username} berhasil dihapus.");
    }
}
