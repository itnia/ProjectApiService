<?php

namespace Tests\Unit;

use App\Models\Address;
use App\Models\Order;
use App\Services\DeliveryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressDistanceTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Тест получения растояния до аддресса доставки
     *
     * @return void
     */
    public function test_get_address_distance()
    {
        $address = Address::factory()->create();

        $delivery_address = new DeliveryService;

        $result = $delivery_address->getAddressDistance(Order::DEPARTURE_ADDRESS, $address->address);

        $this->assertEquals($result, Address::find($address->id)->distance);
    }
}
