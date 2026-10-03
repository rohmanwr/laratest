<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_admin_can_open_user_management_and_create_user_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('users.index'))
            ->assertOk()
            ->assertSee('Manajemen pengguna')
            ->assertSee('Tambah user baru');

        $this->actingAs($admin)
            ->get(route('users.create'))
            ->assertOk()
            ->assertSee('Tambah pengguna baru')
            ->assertSee('Konfirmasi password')
            ->assertSee('Hak akses');
    }

    public function test_admin_can_create_user_with_selected_role(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->post(route('users.store'), [
                'name' => 'Pengguna Baru',
                'username' => 'pengguna_baru',
                'email' => 'baru@example.com',
                'role' => 'admin',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('status');

        $user = User::where('email', 'baru@example.com')->firstOrFail();
        $this->assertSame('Pengguna Baru', $user->name);
        $this->assertSame('pengguna_baru', $user->username);
        $this->assertSame('admin', $user->role);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_regular_user_cannot_open_or_submit_user_management_routes(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('users.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('users.create'))
            ->assertForbidden();

        $this->actingAs($user)
            ->post(route('users.store'), [])
            ->assertForbidden();
    }

    public function test_public_registration_cannot_assign_admin_role(): void
    {
        $this->post('/register', [
            'name' => 'Public User',
            'username' => 'public_user',
            'email' => 'public@example.com',
            'role' => 'admin',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('users', [
            'username' => 'public_user',
            'role' => 'user',
        ]);
    }

    public function test_admin_can_change_roles_but_cannot_remove_the_last_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($admin)
            ->patch(route('users.role.update', $user), ['role' => 'admin'])
            ->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'role' => 'admin']);

        $secondAdmin = User::factory()->create(['role' => 'admin']);
        $this->patch(route('users.role.update', $secondAdmin), ['role' => 'user'])
            ->assertRedirect(route('users.index'));

        $this->patch(route('users.role.update', $user), ['role' => 'user'])
            ->assertRedirect(route('users.index'));

        $this->patch(route('users.role.update', $admin), ['role' => 'user'])
            ->assertRedirect()
            ->assertSessionHasErrors('role');

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin']);
    }

    public function test_admin_can_reset_a_users_password(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($admin)
            ->get(route('users.password.edit', $user))
            ->assertOk()
            ->assertSee('Reset password')
            ->assertSee($user->username);

        $this->put(route('users.password.update', $user), [
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])->assertSessionHasNoErrors()
            ->assertRedirect(route('users.index'));

        $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));
    }

    public function test_admin_management_command_promotes_existing_user(): void
    {
        $user = User::factory()->create(['username' => 'promote_me', 'role' => 'user']);

        $this->artisan('users:make-admin', ['username' => $user->username])
            ->assertSuccessful();

        $this->assertDatabaseHas('users', ['id' => $user->id, 'role' => 'admin']);
    }

    public function test_guest_cannot_open_or_submit_the_create_user_page(): void
    {
        $this->get(route('users.create'))->assertRedirect(route('login'));
        $this->post(route('users.store'), [])->assertRedirect(route('login'));

        $this->assertDatabaseCount('users', 0);
    }

    public function test_login_page_has_a_registration_link(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Daftar sekarang')
            ->assertSee(route('register'));
    }
}
