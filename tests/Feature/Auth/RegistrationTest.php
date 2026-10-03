<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertOk()
            ->assertSee('Username')
            ->assertSee('Alamat email');
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'username' => 'test_user',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'username' => 'test_user',
        ]);
    }

    public function test_registration_normalizes_username_and_rejects_duplicates(): void
    {
        $this->post('/register', [
            'name' => 'First User',
            'username' => 'First_User',
            'email' => 'first@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('dashboard', absolute: false));

        auth()->logout();

        $this->from('/register')->post('/register', [
            'name' => 'Second User',
            'username' => 'FIRST_USER',
            'email' => 'second@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors('username');

        $this->assertDatabaseCount('users', 1);
    }
}
