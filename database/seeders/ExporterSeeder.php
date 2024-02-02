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
            'gst_no' => 'ABC123456789', // Replace with actual GST number
            'iec_no' => 'IEC123456', // Replace with actual IEC number
            'logo' => '', // Replace with actual logo path
            'customer_category' => 'EXPORTER', // Replace with actual customer category
            'lut_no' => 'LUT123', // Replace with actual LUT number
            'state' => 'State Name', // Replace with actual state
            "organization_type" => "PRIVATE",
            'organization_reg_no' => 'ORG123', // Replace with actual organization registration number
            'authorised_signatory_name' => 'Authorized Signatory', // Replace with actual authorized signatory name
            'authorised_signatory_designation' => 'CEO', // Replace with actual designation
            'authorised_signatory_sex' => 'MALE', // Replace with actual sex
            'authorised_signatory_dob' => '1990-01-01', // Replace with actual date of birth
            'authorised_signatory_pan' => 'ABCDE1234F', // Replace with actual PAN number
            'authorised_signatory_aadhar' => '123456789012', // Replace with actual Aadhar number
            'organization_email' => 'organization@example.com', // Replace with actual organization email
            'organization_phone' => '9876543210', // Replace with actual organization phone number
            'firm_pan_no' => 'FIRMPAN123', // Replace with actual firm PAN number
            'status' => 1, // Replace with actual status
        ]);
    }
}
