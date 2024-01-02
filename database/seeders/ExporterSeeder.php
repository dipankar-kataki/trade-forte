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
            'address' => 'Exporter Address', // Replace with actual address
            'pincode' => '123456', // Replace with actual pincode
            'phone' => '1234567890', // Replace with actual phone number
            'gst_no' => 'ABC123456789', // Replace with actual GST number
            'iec_no' => 'IEC123456', // Replace with actual IEC number
            'logo' => 'path/to/logo.jpg', // Replace with actual logo path
            'lut_no' => 'LUT123456', // Replace with actual LUT number
            'ppc_lic_no' => 'PPC123456', // Replace with actual PPC license number
            'seed_lic_no' => 'SEED123456', // Replace with actual seed license number
            'fertilizer_lic_no' => 'FERT123456', // Replace with actual fertilizer license number
            'status' => 1, // Replace with actual status
        ]);

    }
}
