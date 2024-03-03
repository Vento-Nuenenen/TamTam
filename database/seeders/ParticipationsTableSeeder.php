<?php

namespace Database\Seeders;

use App\Models\Participant;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ParticipationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        Participant::create([
            ['scout_name' => $faker->userName, 'first_name' => $faker->firstName, 'last_name' => $faker->lastName, 'address' => $faker->address, 'plz' => $faker->postcode, 'place' => $faker->city, 'birthday' => $faker->date(), 'gender' => 'male'],
        ]);
    }
}
