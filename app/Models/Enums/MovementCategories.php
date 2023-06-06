<?php

namespace App\Models\Enums;

class MovementCategories
{
    public const state = [
        ['title' => 'Кол-центр', 'slug' => 'call-center', 'type' => 0],
        ['title' => 'Зарплата', 'slug' => 'salary', 'type' => 0],
        ['title' => 'Технічні витрати', 'slug' => 'technical-costs', 'type' => 0],
        ['title' => 'Маркетинг', 'slug' => 'marketing', 'type' => 0],
        ['title' => 'Дивіденди', 'slug' => 'dividends', 'type' => 0],
        ['title' => 'Податки', 'slug' => 'taxes', 'type' => 0],
        ['title' => 'Повернення інвестицій', 'slug' => 'return-investment', 'type' => 0],
        ['title' => 'Інші витрати', 'slug' => 'other', 'type' => 0],

        ['title' => 'Інвестиції', 'slug' => 'investments', 'type' => 1],
        ['title' => 'Продаж товару', 'slug' => 'sal-of-goods', 'type' => 1],
    ];
}
