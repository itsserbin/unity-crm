<?php

namespace Database\Seeders;

use App\Models\Enums\Accounts;
use App\Models\Finance\Account;
use Illuminate\Database\Seeder;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    final public function run(): void
    {
        foreach (Accounts::state as $item) {
            if (!Account::where('title', $item['title'])->first()) {
                $model = new Account();
                $model->title = $item['title'];
                $model->save();
            }
        }
    }
}
