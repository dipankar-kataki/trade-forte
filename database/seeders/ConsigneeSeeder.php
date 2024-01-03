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
            'name' => 'Consignee Name',
            'address' => 'Consignee Address',
            'country' => 'India',
            'tpn_no' => 'TPN123456',
            'pin_code' => '123456',
            'status' => 1,
        ]);
    }

}
