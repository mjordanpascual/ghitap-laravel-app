<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a faker
        $faker = \Faker\Factory::create();

        // Create 100 patients using Query Builder
        for ($i = 1; $i < 100; $i++) {
            \DB::table('patients')->insert([
                'hospital_number' => str_pad($i, 15, '0', STR_PAD_LEFT),
                'patlast' => $faker->lastName,
                'patfirst' => $faker->firstName,
                'patmiddle' => $faker->optional()->firstName,
                'patsuffix' => $faker->optional()->randomElement(['Jr', 'Sr', 'II', 'III', 'IV', 'V']),
                'patbdate' => $faker->dateTimeBetween('-100 years', '-18 years')->format('Y-m-d'),
                'patsex' => $faker->randomElement(['M', 'F']),
            ]);
        }

        // Create 10 services using Query Builder
        $services = [
            ['DENTA', 'Dental'],
            ['ENTHN', 'ENT-HNS'],
            ['FAMED', 'FAMILY MEDICINE'],
            ['GYNEC', 'GYNECOLOGY'],
            ['MEDIC', 'MEDICINE'],
            ['OBSTE', 'OBSTETRICS'],
            ['OPTHA', 'OPHTHALMOLOGY'],
            ['ORTHO', 'ORTHOPEDICS'],
            ['PEDIA', 'PEDIATRICS'],
            ['PSYCH', 'Psychiatry'],
            ['SURGE', 'SURGERY']
        ];

        foreach ($services as $service) {
            \DB::table('departments')->insert([
                'code' => $service[0],
                'name' => $service[1],
            ]);
        }

        // Loop through each patient and create 5 encounters using Query Builder
        $patients = \DB::table('patients')->get();
        foreach ($patients as $patient) {
            for ($i = 0; $i < 5; $i++) {
                \DB::table('encounters')->insert([
                    'hospital_number' => $patient->hospital_number,
                    'timestamp' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
                    'department_code' => $faker->randomElement($services)[0],
                    'type' => $faker->randomElement(['R', 'T']),
                ]);
            }
        }
    }
}
