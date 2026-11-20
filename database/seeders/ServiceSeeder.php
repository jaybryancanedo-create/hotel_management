<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Breakfast', 'description' => 'Daily breakfast service', 'price' => 25],
            ['name' => 'Airport transfer', 'description' => 'One-way airport transfer', 'price' => 45],
            ['name' => 'Spa treatment', 'description' => 'Relaxing spa treatment', 'price' => 80],
            ['name' => 'Laundry', 'description' => 'Standard laundry service', 'price' => 15],
            ['name' => 'Room service', 'description' => 'In-room dining service', 'price' => 20],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(
                ['name' => $service['name']],
                [
                    'description' => $service['description'],
                    'price' => $service['price'],
                    'status' => 'available',
                ]
            );
        }
    }
}
