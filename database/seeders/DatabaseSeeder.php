<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // If you want to create demo users automatically:
        // \App\Models\User::factory(10)->create();

        $this->call([
            BrandSeeder::class,
            InfluencerSeeder::class,
        ]);
    }
}
