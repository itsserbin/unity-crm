<?php

namespace Tests\Feature;

use App\Models\Catalog\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $this->seed();

        $this->withoutMiddleware();
        $products = Product::factory()->count(1)->create();

        $requestData = [
            'source_id' => 1,
            'client' => [
                'phone' => fake()->phoneNumber
            ],
            'products' => $products->toArray(),
        ];

        $token = $this->getValidToken();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/v1/orders/create', $requestData);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Замовлення створене',
        ]);
    }

    private function getValidToken()
    {
        $user = User::factory()->create();
        return $user->createToken('Test Token')->plainTextToken;
    }
}
