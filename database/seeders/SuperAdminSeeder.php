<?php

namespace Database\Seeders;

use App\Common\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@tradeforte.com',
            'password' => Hash::make('password'),
            'role' => Role::Super_Admin,
            'module_id' => json_encode(1),
        ]);
    }
}
