<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // List of countries that share a border with India
        $borderCountries = [
            'Afghanistan', 'Bangladesh', 'Bhutan', 'China', 'Myanmar', 'Nepal', 'Pakistan', 'Sri Lanka'
            // Add more countries as needed
        ];

        foreach ($borderCountries as $countryName) {
            Country::create(['name' => $countryName]);
        }
    }
}
