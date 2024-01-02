<?php

namespace Database\Seeders;

use App\Models\Consignee;
use App\Models\User;
use Illuminate\Database\Seeder;

class ConsigneeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Assuming you have some users in the 'users' table
        $users = User::find(1);

        Consignee::create([
            'account_created_by' => 1,
            'name' => 'Consignee Name', // Replace with actual consignee name
            'address' => 'Consignee Address', // Replace with actual address
            'country' => 'India', // Replace with actual country
            'tpn_no' => 'TPN123456', // Replace with actual TPN number
            'phone' => '1234567890', // Replace with actual phone number
            'pin_code' => '123456', // Replace with actual pin code
            'status' => 1, // Replace with actual status
        ]);
    }

}
