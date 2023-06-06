<?php

namespace Database\Seeders;

use App\Models\Enums\MovementCategories;
use App\Models\Statistic\MovementCategory;
use Illuminate\Database\Seeder;

class MovementCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    final public function run(): void
    {
        foreach (MovementCategories::state as $item) {
            if (!MovementCategory::where('slug', $item['slug'])->first()) {
                $model = new MovementCategory();
                $model->title = $item['title'];
                $model->type = $item['type'];
                $model->slug = $item['slug'];
                $model->save();
            }
        }
    }
}
