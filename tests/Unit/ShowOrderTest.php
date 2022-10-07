<?php

namespace Tests\Unit;

use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест создания заказа
     *
     * @return void
     */
    public function test_create_order()
    {
        $product = Product::factory()->create();
        $address = Address::factory()->create();
        $user = User::factory()->create(['role_id' => 1]);
        $order_service = new OrderService;

        $result = $order_service->createOrder($address->id, [$product->id], $user->id);
        $this->assertTrue($result);
        $this->assertDatabaseHas('orders', ['address_id' => $address->id]);
    }

    /**
     * Тест деталей заказа для продавца
     *
     * @return void
     */
    public function test_show_order()
    {
        $product = Product::factory()->create();
        $address = Address::factory()->create();
        $user = User::factory()->create(['role_id' => 1]);
        $order_service = new OrderService;

        $order_service->createOrder($address->id, [$product->id], $user->id);
        $order = Order::first();
        
        $order_service = new OrderService;

        $result = $order_service->showOrder($user->role_id, $user->id, $order->id);

        $this->assertTrue((bool)$result);
    }

}
