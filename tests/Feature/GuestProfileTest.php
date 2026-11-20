<?php

namespace Tests\Feature;

use App\Models\GuestProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_is_created_once_and_cannot_be_edited(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('guest-profile.store'), [
                'phone' => '555-0100',
                'address' => '12 Main Street',
                'date_of_birth' => '1990-04-15',
            ])
            ->assertRedirect(route('dashboard'));

        $profileId = GuestProfile::query()->value('id');

        $this->post(route('guest-profile.store'), [
            'phone' => '555-0199',
            'address' => '24 Oak Avenue',
            'date_of_birth' => '1990-04-15',
        ])->assertRedirect(route('dashboard'))
            ->assertSessionHas('status', 'A guest profile already exists and cannot be edited.');

        $this->assertDatabaseCount('guest_profiles', 1);
        $this->assertDatabaseHas('guest_profiles', [
            'id' => $profileId,
            'user_id' => $user->id,
            'phone' => '555-0100',
            'address' => '12 Main Street',
        ]);
    }
}
