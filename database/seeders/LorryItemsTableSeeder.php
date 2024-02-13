<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LorryItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing data in the table

        // Seed data
        $lorryItems = [
            [
                'date' => now(),
                'trip' => 'Trip 1',
                'vehicle_no' => 'ABC123',
                'quantity' => '20',
                'users_id' => 1,
                'lorry_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'date' => now(),
                'trip' => 'Trip 2',
                'vehicle_no' => 'XYZ789',
                'quantity' => '15',
                'users_id' => 1,
                'lorry_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more sample data as needed
        ];

        // Insert data into the 'lorry_items' table
        DB::table('lorry_items')->insert($lorryItems);
    }
}
