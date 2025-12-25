<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ABCAdminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'abcadmin@example.com'],
            [
                'first_name'    => 'ABC',
                'middle_name'   => null, // optional
                'last_name'     => 'President',
                'suffix'        => null, // optional
                'password'      => Hash::make('secret123'),
                'role'          => 'abc_admin',
                'gender'        => 'male',
                'barangay_id'   => null,
            ]
        );
    }
}
