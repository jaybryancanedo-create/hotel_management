<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_request_a_room_and_server_calculates_the_total(): void
    {
        $user = User::factory()->create();
        $room = Room::create([
            'room_number' => '202',
            'room_type' => 'Deluxe',
            'price_per_night' => 100.25,
            'status' => 'available',
        ]);
        $checkIn = today()->addDays(3)->toDateString();
        $checkOut = today()->addDays(5)->toDateString();

        $this->actingAs($user)
            ->post(route('reservations.store'), [
                'room_id' => $room->id,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'total_amount' => 1,
                'user_id' => 99999,
            ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'room_id' => $room->id,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'status' => 'pending',
            'total_amount' => '200.50',
        ]);
    }

    public function test_guest_cannot_request_dates_that_overlap_an_existing_reservation(): void
    {
        $owner = User::factory()->create();
        $guest = User::factory()->create();
        $room = Room::create([
            'room_number' => '303',
            'room_type' => 'Standard',
            'price_per_night' => 80,
            'status' => 'available',
        ]);
        $checkIn = today()->addDays(4)->toDateString();
        $checkOut = today()->addDays(6)->toDateString();

        Reservation::create([
            'user_id' => $owner->id,
            'room_id' => $room->id,
            'check_in' => today()->addDays(3)->toDateString(),
            'check_out' => today()->addDays(5)->toDateString(),
            'status' => 'confirmed',
            'total_amount' => 160,
        ]);

        $this->actingAs($guest)
            ->from(route('dashboard'))
            ->post(route('reservations.store'), [
                'room_id' => $room->id,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
            ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasErrors('room_id');

        $this->assertSame(1, Reservation::query()->count());
    }
}
