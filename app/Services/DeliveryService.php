<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Order;

class DeliveryService
{
    /**
     * Расчет стоимости доставки
     *
     * @param float $distance
     * @return float
     */
    public function calculateDeliveryCost(float $distance): float
    {
        return Order::COST_PER_KM * $distance;
    }

    public function getAddressDistance(string $departure_address, string $delivery_address)
    {
        //TODO: Можно проверять используя этот сервис https://developers.google.com/maps/documentation/distance-matrix/start#maps_http_distancematrix_start-sh
        //Но замокаем результат

        $distance = fake()->randomFloat(2, 0, 25);
        //Сохраняем адресс с расстоянием если его нет в базе
        Address::updateOrCreate(['address' => $delivery_address], ['distance' => $distance]);
        
        return $distance;
    }
}