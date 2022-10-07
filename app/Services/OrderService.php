<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Address;
use Carbon\Carbon;

class OrderService
{

    /**
     * Создать заказ
     *
     * @param string $delivery_address
     * @param array $products
     * @param int $user_id
     * @return bool
     */
    public function createOrder(int $delivery_address_id, array $products, int $user_id)
    {
        $address = Address::find($delivery_address_id);
        if (!$address) {
            return false;
        }
        $delivery_service = new DeliveryService();
        $delivery_cost = $delivery_service->calculateDeliveryCost($address->distance);

        $order = Order::create([
            'address_id' => $address->id,
            'delivery_date' => Carbon::now(),
            'delivery_price' => $delivery_cost,
            'active' => true,
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ]);

        if ($order) {
            $order->products()->attach($products);
            return true;
        }

        return false;
    }

    /**
     * Привязка курьера к заказу
     *
     * @param int $order_id
     * @param int $courier_id
     * @param int $user_id
     * @return mixed
     */
    public function attachCourier(int $order_id, int $courier_id, int $user_id)
    {
        return Order::updateOrCreate(['id' => $order_id], ['courier_id' => $courier_id, 'updated_by' => $user_id]);
    }

    /**
     * Список заказов
     *
     * @param int $user_id
     * @param int $role_id
     * @return mixed
     */
    public function listOrders(int $user_id, int $role_id, int $client_id)
    {
        return Order::active()
            ->where(function ($query) use ($role_id, $user_id, $client_id) {
                switch ($role_id) {
                    case 0:
                        $query->where('created_by', $user_id);
                        break;
                    case 1:
                        if ($client_id > 0) {
                            $query->where('created_by', $client_id);
                        }
                        break;
                    case 2:
                        $query->where('courier_id', $user_id);
                        break;
                }
            })->get()->toArray();
    }

    public function showOrder(int $role_id, int $user_id, int $order_id)
    {
        return Order::active()
            ->where(function ($query) use ($role_id, $user_id) {
                switch ($role_id) {
                    case 0:
                        $query->where('created_by', $user_id);
                        break;
                    case 2:
                        $query->where('courier_id', $user_id);
                        break;
                }
            })->find($order_id);
    }
}
