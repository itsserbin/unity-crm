<?php

namespace App\Models\Enums;

class MovementCategories
{
    public const state = [
        ['title' => 'Кол-центр', 'slug' => 'call-center', 'type' => false],
        ['title' => 'Зарплата', 'slug' => 'salary', 'type' => false],
        ['title' => 'Технічні витрати', 'slug' => 'technical-costs', 'type' => false],
        ['title' => 'Маркетинг', 'slug' => 'marketing', 'type' => false],
        ['title' => 'Дивіденди', 'slug' => 'dividends', 'type' => false],
        ['title' => 'Податки', 'slug' => 'taxes', 'type' => false],
        ['title' => 'Повернення інвестицій', 'slug' => 'return-investment', 'type' => false],
        ['title' => 'Інші витрати', 'slug' => 'other', 'type' => false],

        ['title' => 'Інвестиції', 'slug' => 'investments', 'type' => true],
        ['title' => 'Продаж товару', 'slug' => 'sal-of-goods', 'type' => true],
    ];
}
