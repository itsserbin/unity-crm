<?php

namespace App\Models\Enums;

class Statuses
{
    const state = [
        [
            'title' => 'Новий',
            'group_slug' => 'new'
        ],
        [
            'title' => 'Підтвердження наявності',
            'group_slug' => 'coordination'
        ],
        [
            'title' => 'Очікування передоплати',
            'group_slug' => 'coordination'
        ],
        [
            'title' => 'Передано на виробництво',
            'group_slug' => 'production'
        ],
        [
            'title' => 'Виробляється',
            'group_slug' => 'production'
        ],
        [
            'title' => 'Виготовлено',
            'group_slug' => 'production'
        ],
        [
            'title' => 'Передано в доставку',
            'group_slug' => 'delivery'
        ],
        [
            'title' => 'Доставляється',
            'group_slug' => 'delivery'
        ],
        [
            'title' => 'В дорозі',
            'group_slug' => 'delivery'
        ],
        [
            'title' => 'На пошті',
            'group_slug' => 'delivery'
        ],
        [
            'title' => 'Виконано',
            'group_slug' => 'done'
        ],
        [
            'title' => 'Некоректні дані',
            'group_slug' => 'canceled'
        ],
        [
            'title' => 'Недозвон',
            'group_slug' => 'canceled'
        ],
        [
            'title' => 'Немає в наявності',
            'group_slug' => 'canceled'
        ],
        [
            'title' => 'Не влаштувала ціна',
            'group_slug' => 'canceled'
        ],
        [
            'title' => 'Скасовано',
            'group_slug' => 'canceled'
        ],
    ];
}
