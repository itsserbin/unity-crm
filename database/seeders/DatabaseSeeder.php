<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    final public function run(): void
    {
        if (tenant()) {
            $this->call([
                StatusesSeeder::class,
                RolesSeeder::class,
                MovementCategoriesSeeder::class,
                AccountsSeeder::class,
                SourcesSeeder::class,
            ]);
        } else {
            $this->call([
                TariffPlansSeeder::class,
            ]);
        }
    }
}
