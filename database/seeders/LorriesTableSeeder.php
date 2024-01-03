<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LorriesTableSeeder extends Seeder
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
        $lorries = [
            [
                'invoice_id' => 1,
                'exporter_id' => 1,
                'consignee_id' => 1,
                'bank_id' => 1,
                'details_added_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more sample data as needed
        ];

        // Insert data into the 'lorries' table
        DB::table('lorries')->insert($lorries);
    }
}
