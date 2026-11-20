<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(101, 110) as $roomNumber) {
            Room::firstOrCreate(
                ['room_number' => (string) $roomNumber],
                [
                    'room_type' => 'Standard',
                    'price_per_night' => 100,
                    'status' => 'available',
                ]
            );
        }
    }
}
