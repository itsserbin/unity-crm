<?php

namespace App\Models\Enums;

class StatusGroups
{
    const state = [
        [
            'title' => 'Новий',
            'slug' => 'new',
            'hex' => '#3f8c7f',
            'is_system_status' => true
        ],
        [
            'title' => 'Погодження',
            'hex' => '#b5ac84',
            'slug' => 'coordination',
            'is_system_status' => true
        ],
        [
            'title' => 'Виробництво',
            'hex' => '#ffc68c',
            'slug' => 'production',
            'is_system_status' => true
        ],
        [
            'title' => 'Доставка',
            'hex' => '#5b586f',
            'slug' => 'delivery',
            'is_system_status' => true
        ],
        [
            'title' => 'Виконано',
            'hex' => '#a1ff99',
            'slug' => 'done',
            'is_system_status' => true
        ],
        [
            'title' => 'Скасовано',
            'hex' => '#ff8c8c',
            'slug' => 'canceled',
            'is_system_status' => true
        ],
    ];
}
