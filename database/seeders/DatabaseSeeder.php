<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    // php artisan db:seed --class=SuperAdminSeeder
    // php artisan db:seed --class=CountrySeeder
    // php artisan db:seed --class=ExporterSeeder
    // php artisan db:seed --class=ConsigneeSeeder
    // php artisan db:seed --class=BankAccountsTableSeeder
    // php artisan db:seed --class=InvoiceDetailsTableSeeder
    // php artisan db:seed --class=InvoiceItemsTableSeeder

    public function run()
    {
        $this->call([
            SuperAdminSeeder::class,
            CountrySeeder::class,
            ExporterSeeder::class,
            ConsigneeSeeder::class,
            BankAccountsTableSeeder::class,
            InvoiceDetailsTableSeeder::class,
            InvoiceItemsTableSeeder::class,
            PackagingDetailsTableSeeder::class,
            LorriesTableSeeder::class,
            LorryItemsTableSeeder::class,
            Hsnseeder::class,
        ]);
    }
}
