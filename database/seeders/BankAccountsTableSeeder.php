<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankAccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Seed data
        $bankAccounts = [
            [
                'exporter_id' => 1,
                'account_created_by' => 1,
                'bank_name' => 'Sample Bank',
                'account_name' => 'John Doe',
                'account_no' => '1234567890',
                'ifsc_code' => 'SAMPLEIFSC',
                'swift_code' => 'SAMPLESWIFT',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more sample data as needed
        ];

        // Insert data into the 'bank_accounts' table
        DB::table('bank_accounts')->insert($bankAccounts);
    }
}
