<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resident;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResidentSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $barangays = range(1, 22); // Barangay IDs 1-22
        $categories = ['Regular', 'Senior', 'PWD', 'Student']; 
        $civil_statuses = ['Single', 'Married', 'Widowed', 'Separated'];

        foreach ($barangays as $barangay_id) {
            for ($i = 1; $i <= 10; $i++) { // 10 residents per barangay
                $birthdate = $faker->dateTimeBetween('-80 years', '-1 years');
                Resident::create([
                    'first_name' => $faker->firstName,
                    'middle_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'suffix' => $faker->optional()->suffix,
                    'gender' => $faker->randomElement(['Male', 'Female']),
                    'birthdate' => $birthdate->format('Y-m-d'),
                    'age' => now()->diff($birthdate)->y,
                    'civil_status' => $faker->randomElement($civil_statuses),
                    'category' => $faker->randomElement($categories),
                    'email' => $faker->unique()->safeEmail,
                    'phone' => $faker->phoneNumber,
                    'barangay_id' => $barangay_id,
                    'zone' => rand(1, 10),
                    'proof_of_residency' => $faker->word,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'password' => Hash::make('password123'),
                    'status' => 'approved',
                    'reject_reason' => null,
                    'previously_rejected' => false,
                    'remember_token' => Str::random(10),
                    'profile_picture' => null,
                    'voter' => $faker->boolean ? 'Yes' : 'No',
                    'proof_type' => $faker->word,
                    'must_change_password' => false,
                ]);
            }
        }
    }
}
