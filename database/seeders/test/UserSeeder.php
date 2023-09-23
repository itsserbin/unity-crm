<?php

namespace Database\Seeders\test;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    final public function run(): void
    {
        User::factory()->count(100)->create();
    }
}
