<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InfluencerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('influencers')->insert([
            [
                'name' => 'Aditi Sharma',
                'category' => 'Fashion',
                'followers' => 12000,
                'platform' => 'Instagram',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Rohit Patel',
                'category' => 'Tech',
                'followers' => 8000,
                'platform' => 'YouTube',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Sneha Verma',
                'category' => 'Travel',
                'followers' => 15000,
                'platform' => 'Instagram',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Arjun Mehta',
                'category' => 'Fitness',
                'followers' => 20000,
                'platform' => 'TikTok',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Priya Desai',
                'category' => 'Food',
                'followers' => 9500,
                'platform' => 'YouTube',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
