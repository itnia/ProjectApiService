<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeliveryCostResource;
use App\Models\Address;
use App\Models\Order;
use App\Services\DeliveryService;
use \App\Http\Requests\DeliveryRequest;
use Illuminate\Support\Facades\Log;

class DeliveryController extends Controller
{
    public function __construct(protected DeliveryService $delivery_service)
    {
    }

    /**
     * Метод для расчтета стоимости доставки
     *
     * @param DeliveryRequest $request
     * @return false|string
     */
    public function calculateDeliveryCost(DeliveryRequest $request)
    {
        $delivery_address = $request->validated()['delivery_address'];

        $address = Address::where('address', $delivery_address)->first();
        try {
            if ($address) {
                $delivery_cost = $this->delivery_service->calculateDeliveryCost($address->distance);
            } else {
                $delivery_cost = $this->delivery_service->getAddressDistance(
                    Order::DEPARTURE_ADDRESS,
                    $delivery_address
                );
            }

            return new DeliveryCostResource($delivery_cost);
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage());
        }

        return $this->responseErrors('Something goes wrong!');
    }
}
