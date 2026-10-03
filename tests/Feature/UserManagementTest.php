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

    public function test_authenticated_user_can_open_the_create_user_page(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('users.create'))
            ->assertOk()
            ->assertSee('Tambah pengguna')
            ->assertSee('Konfirmasi password');
    }

    public function test_authenticated_user_can_create_a_user(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('users.store'), [
                'name' => 'Pengguna Baru',
                'email' => 'baru@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('users.create'))
            ->assertSessionHas('status');

        $user = User::where('email', 'baru@example.com')->firstOrFail();
        $this->assertSame('Pengguna Baru', $user->name);
        $this->assertTrue(Hash::check('password123', $user->password));
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
            ->assertSee('Registrasi sekarang')
            ->assertSee(route('register'));
    }
}
