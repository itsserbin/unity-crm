<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckTokenRoute extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        // Отримати токен користувача (вам потрібно додати цю частину коду)
        $userToken = 'YOUR_USER_TOKEN';

        // Спробувати відправити GET-запит на /api/check-token з токеном у заголовку Authorization
        $response = $this->withHeaders([
            'Authorization' => "Bearer $userToken",
        ])->get('/api/check-token');

        // Перевірити, чи відповідь має код 200 (OK)
        $response->assertStatus(200);

        // Вивести JSON-відповідь на консоль
        $jsonResponse = $response->json();
        dump($jsonResponse);
    }
}
