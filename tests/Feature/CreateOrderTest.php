<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Address;
use App\Models\User;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест для создания заказа
     *
     * @return void
     */
    public function test_create_order(): void
    {
        $product = Product::factory()->create();
        $address = Address::factory()->create();
        User::factory()->create();

        $response = $this->post('/api/orders', [
            "delivery_address_id" => $address->id,
            "products" => [$product->id]
        ]);

        $response->assertStatus(200);
    }
}
