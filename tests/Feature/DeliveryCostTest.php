<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Address;

class DeliveryCostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест для расчёта стоимости заказа
     *
     * @return void
     */
    public function test_calc_delivery_cost()
    {
        $address = Address::factory()->create();
        $response = $this->post('/api/delivery/cost-calculation', [
            "delivery_address" => $address->address
        ]);

        $response->assertStatus(200);
    }
}
