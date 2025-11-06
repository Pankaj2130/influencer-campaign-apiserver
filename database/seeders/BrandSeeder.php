<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('brands')->insert([
            ['name' => 'Nike', 'email' => 'contact@nike.com', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Adidas', 'email' => 'support@adidas.com', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Puma', 'email' => 'hello@puma.com', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Reebok', 'email' => 'info@reebok.com', 'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'Under Armour', 'email' => 'team@underarmour.com', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
