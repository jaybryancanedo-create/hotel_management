<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_an_account_and_opens_the_dashboard(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Casey Guest',
            'username' => 'casey-guest',
            'email' => 'casey@example.com',
            'password' => 'password123',
        ])
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'username' => 'casey-guest',
            'email' => 'casey@example.com',
        ]);
    }

    public function test_failed_login_shows_the_error_on_the_username_field(): void
    {
        $this->from(route('login'))
            ->post(route('login.attempt'), [
                'username' => 'missing-guest',
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('username');
    }
}
