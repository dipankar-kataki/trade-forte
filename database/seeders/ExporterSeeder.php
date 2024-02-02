<?php

namespace Database\Seeders;

use App\Models\Exporter;
use App\Models\User;

use Illuminate\Database\Seeder;

class ExporterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Assuming you have some users in the 'users' table
        // $users = User::all();

        // foreach ($users as $user) {
        Exporter::create([
            'account_created_by' => 1,
            'name' => 'Exporter Name', // Replace with actual exporter name
            'email' => 'exporter@example.com', // Replace with actual email
            'addresses' => 'Exporter Address', // Replace with actual address
            'pincode' => '123456', // Replace with actual pincode
            'phone' => '1234567890', // Replace with actual phone number
            'gst_no' => 'ABC123456789', // Replace with actual GST number
            'iec_no' => 'IEC123456', // Replace with actual IEC number
            'logo' => 'path/to/logo.jpg', // Replace with actual logo path
            'logo_height' => 123, // Replace with actual LUT number
            'logo_width' => 100, // Replace with actual PPC license number
            'pan_no' => 'CFK10JAP10', // Replace with actual seed license number
            'status' => 1, // Replace with actual status
        ]);

    }
}
