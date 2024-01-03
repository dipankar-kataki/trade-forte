<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeclarationsTableSeeder extends Seeder
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
        $declarations = [
            [
                'country_id' => 1,
                'declaration' => '1.We declare that the packing list shows the actual list of goods loaded for
                export and that all particulars are true and correct.',
                'type' => 'packing',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'country_id' => 1,
                'declaration' => '1. We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct. ACCOUNT NAME M/S JYOTSNA ENTERPRISE BANK NAME AXIS BANK LIMITED 2. For Delivered Supplies, if there are any complaints in goods, show it to the driver and take his signature on invoice copy and inform us immediately, otherwise the complaint will not be valid. ACCOUNT NUMBER 923020011626154 SWIFT CODE AXISINBB276 For JYOTSNA ENTERPRISE - Authorized Signatory 3. Pricings are subjected to current market rates and may increase / decrease in future consignments. 4. Packaging in export standard (paper cartoon) & Plastic Polypropylene (PP) Pouches as per buyer instructions. 5. “Seeds are for Sowing Purpose only.” 6. Delivery within 4 (four) months from the date of receipt of LC and partial shipment & Trans-shipment allowed. 7. I certify that the merchandise is of INDIAN ORIGIN',
                'type' => 'invoice',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'country_id' => 2,
                'declaration' => '1. We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct. 2. For Delivered Supplies, if there are any compliants in goods, show it to the driver and take his signature on invoice copy and inform us immediately, otherwise complain will not be valid.',
                'type' => 'invoice',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'country_id' => 2,
                'declaration' => '1. We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct. 2. For Delivered Supplies, if there are any compliants in goods, show it to the driver and take his signature on invoice copy and inform us immediately, otherwise complain will not be valid.',
                'type' => 'packing',
                'created_at' => now(),
                'updated_at' => now(),
            ],


        ];

        // Insert data into the 'declarations' table
        DB::table('declarations')->insert($declarations);
    }
}
