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
                'users_id' => 1,
                'exporter_id' => 1,
                'consignee_id' => 1,
                'invoice_id' => 'INV-001',
                'country_of_origin' => 'China',
                'country_of_export' => 'India',
                'country_of_destination' => 'Bhutan',
                'import_export_code' => 'IEC123456',
                'port_of_loading' => 'Sample Port',
                'port_of_destination' => 'Another Port',
                'freight' => 'Air Freight',
                'valid_upto' => '2025-01-01',
                'vehicle_no' => 'ABC123',
                'insurance' => 'Yes',
                'incoterm' => 'FOB', // Add the missing fields with appropriate values
                'invoice_date' => Carbon::now(),
                'eway_bill_id' => 'EWB-001',
                'po_contract_number' => 'PO123',
                'po_contract_date' => '2023-01-01',
                'remarks' => 'Sample remarks',

            ],
            // Add more sample data as needed
        ];

        // Insert data into the 'invoice_details' table
        DB::table('invoice_details')->insert($invoiceDetails);
    }
}
