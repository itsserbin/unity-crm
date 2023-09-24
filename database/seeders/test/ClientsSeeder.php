<?php

namespace Database\Seeders\test;

use App\Models\CRM\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    final public function run(): void
    {
        Client::factory()->count(100)->create();
    }
}
