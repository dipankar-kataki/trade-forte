<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing data in the table
        DB::table('invoice_details')->truncate();

        // Seed data
        $invoiceDetails = [
            [
                'details_added_by' => 1,
                'exporter_id' => 1,
                'consignee_id' => 1,
                'invoice_id' => 'INV-001',
                'country_of_origin' => 'Sample Country',
                'country_of_export' => 'Another Country',
                'import_export_code' => 'IEC123456',
                'auth_dealer_code' => 'DEALER123',
                'port_of_loading' => 'Sample Port',
                'port_of_destination' => 'Another Port',
                'freight' => 'Air Freight',
                'valid_upto' => '2025-01-01',
                'vehicle_no' => 'ABC123',
                'insurance' => 'Yes',
                'buyer_no' => 'BUYER456',
                'invoice_date' => Carbon::now(),
                'eway_bill_id' => 'EWB-001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more sample data as needed
        ];

        // Insert data into the 'invoice_details' table
        DB::table('invoice_details')->insert($invoiceDetails);
    }
}
