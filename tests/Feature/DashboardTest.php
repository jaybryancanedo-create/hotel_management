<?php

namespace Tests\Feature;

use App\Models\GuestProfile;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservation_form_remains_visible_when_no_rooms_are_available(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('href="#add-reservation"', false)
            ->assertSee('id="add-reservation"', false)
            ->assertSee('No rooms are currently available.')
            ->assertSee('name="check_in"', false)
            ->assertSee('No available rooms')
            ->assertSee('Add profile')
            ->assertSee('name="phone"', false)
            ->assertSeeInOrder([
                '<h2>Guest profile</h2>',
                '<h2>Add reservation</h2>',
                '<h2>Your reservations (0)</h2>',
                '<h2>Available rooms (0)</h2>',
                '<h2>Available services (0)</h2>',
            ], false);
    }

    public function test_dashboard_displays_available_models_and_only_the_signed_in_guests_data(): void
    {
        $user = User::factory()->create(['name' => 'Taylor Guest']);
        $room = Room::create([
            'room_number' => '101',
            'room_type' => 'Deluxe',
            'price_per_night' => 180,
            'status' => 'available',
        ]);
        Room::create([
            'room_number' => '999',
            'room_type' => 'Private',
            'price_per_night' => 500,
            'status' => 'unavailable',
        ]);
        Service::create([
            'name' => 'Breakfast',
            'description' => 'Daily breakfast',
            'price' => 25,
            'status' => 'available',
        ]);

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'check_in' => '2026-12-01',
            'check_out' => '2026-12-03',
            'status' => 'confirmed',
            'total_amount' => 360,
        ]);
        Payment::create([
            'reservation_id' => $reservation->id,
            'amount' => 360,
            'payment_method' => 'card',
            'status' => 'paid',
        ]);
        GuestProfile::create([
            'user_id' => $user->id,
            'phone' => '555-0100',
            'address' => '12 Main Street',
            'date_of_birth' => '1990-04-15',
        ]);

        $otherUser = User::factory()->create();
        $privateRoom = Room::where('room_number', '999')->firstOrFail();
        Reservation::create([
            'user_id' => $otherUser->id,
            'room_id' => $privateRoom->id,
            'check_in' => '2027-01-10',
            'check_out' => '2027-01-12',
            'status' => 'confirmed',
            'total_amount' => 1000,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Taylor Guest')
            ->assertSee('Add reservation')
            ->assertSee('name="check_in"', false)
            ->assertSee('Your reservations (1)')
            ->assertSee('Room 101')
            ->assertSee('Breakfast')
            ->assertSee('555-0100')
            ->assertSee('Payment: $360.00')
            ->assertDontSee('Add profile')
            ->assertDontSee('Update profile')
            ->assertDontSee('name="phone"', false)
            ->assertDontSee('name="address"', false)
            ->assertDontSee('name="date_of_birth"', false)
            ->assertDontSee('Room 999')
            ->assertDontSee('2027-01-10');
    }
}
