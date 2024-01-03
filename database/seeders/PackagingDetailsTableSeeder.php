<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackagingDetailsTableSeeder extends Seeder
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
        $packagingDetails = [
            [
                'invoice_item_id' => 1,
                'invoice_id' => 1,
                'details_added_by' => 1,
                'net_weight' => '30 Kg',
                'gross_weight' => '35 Kg',
                'each_box_weight' => 2.5,
                'packaging_type' => 'Box',
                'quantity' => 10,
                'total_gross_weight' => 35,
                'vehicle_no' => 'XYZ123',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'invoice_item_id' => 2,
                'invoice_id' => 1,
                'details_added_by' => 1,
                'net_weight' => '80 Kg',
                'gross_weight' => '85 Kg',
                'each_box_weight' => 4.5,
                'packaging_type' => 'Carton',
                'quantity' => 5,
                'total_gross_weight' => 85,
                'vehicle_no' => 'ABC456',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'invoice_item_id' => 3,
                'invoice_id' => 1,
                'details_added_by' => 1,
                'net_weight' => '100 Kg',
                'gross_weight' => '105 Kg',
                'each_box_weight' => 3.5,
                'packaging_type' => 'Drum',
                'quantity' => 8,
                'total_gross_weight' => 105,
                'vehicle_no' => 'PQR789',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert data into the 'packaging_details' table
        DB::table('packaging_details')->insert($packagingDetails);
    }
}
