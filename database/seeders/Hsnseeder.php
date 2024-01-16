<?php

namespace Database\Seeders;

use App\Models\HsnCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Hsnseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Path to your CSV file
        $csvFilePath = base_path('HSN_SAC_csv.csv');


        // Open the CSV file for reading
        $csvFile = fopen($csvFilePath, 'r');

        // Skip the header row
        $header = fgetcsv($csvFile);

        // Insert data into the MySQL database
        while (($data = fgetcsv($csvFile)) !== false) {
            $rowData = array_combine($header, $data);
            // Adjust column names based on your CSV structure
            $hsnCode = $rowData['HSN Code'];
            $hsnDescription = $rowData['HSN Description'];

            // Insert data into the MySQL table
            DB::table('hsn_table')->insert([
                'hsn_code' => $hsnCode,
                'hsn_description' => $hsnDescription,
            ]);
        }

        // Close the CSV file
        fclose($csvFile);
    }
}
