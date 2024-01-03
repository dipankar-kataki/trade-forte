<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceItemsTableSeeder extends Seeder
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
        $items = [
            [
                'invoice_id' => 1,
                'items_added_by' => 1,
                'hsn_code' => '123456',
                'description' => 'Sample Item 1',
                'unit_type' => 'Piece',
                'unit_value' => 10,
                'quantity' => 100,
                'weight' => 50,
                'net_weight' => 45,
                'total_value' => 1500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'invoice_id' => 1,
                'items_added_by' => 1,
                'hsn_code' => '654321',
                'description' => 'Sample Item 2',
                'unit_type' => 'Kg',
                'unit_value' => 5,
                'quantity' => 20,
                'weight' => 100,
                'net_weight' => 90,
                'total_value' => 800,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'invoice_id' => 1,
                'items_added_by' => 1,
                'hsn_code' => '987654',
                'description' => 'Sample Item 3',
                'unit_type' => 'Box',
                'unit_value' => 8,
                'quantity' => 30,
                'weight' => 120,
                'net_weight' => 110,
                'total_value' => 1200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more sample data as needed
        ];

        // Insert data into the 'invoice_items' table
        DB::table('invoice_items')->insert($items);
    }
}
