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
        $this->call(StatusesSeeder::class);
        $this->call(MovementCategoriesSeeder::class);
        $this->call(AccountsSeeder::class);
        $this->call(SourcesSeeder::class);
    }
}
