<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function __construct(protected OrderService $order_service)
    {
    }

    /**
     * Список активных заказов
     *
     * @return false|string
     */
    public function index(Request $request)
    {
        $role_id = Auth::user()->role_id;
        $user_id = Auth::user()->id;
        $client_id = $request->get('client_id', 0);
        try {
            $orders = $this->order_service->listOrders($user_id, $role_id, $client_id);

            return OrderResource::collection($orders);
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage());
        }

        return $this->responseErrors('Something goes wrong!');
    }


    /**
     * Сохраняем заказ
     *
     * @param StoreOrderRequest $request
     * @return string
     */
    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();
        $delivery_address = $validated['delivery_address_id'];
        $products = $validated['products'];
        $user_id = Auth::user()->id ?? 1;
        try {
            if ($this->order_service->createOrder($delivery_address, $products, $user_id)) {
                return $this->responseSuccess('The order has been created!');
            }
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage());
        }

        return $this->responseErrors('Something goes wrong!');
    }

    /**
     * Детали заказа
     *
     * @param $order_id
     * @return OrderResource|false|string
     */
    public function show($order_id)
    {
        $role_id = Auth::user()->role_id;
        $user_id = Auth::user()->id;
        try {
            $order = $this->order_service->showOrder($role_id, $user_id, $order_id);

            if ($order) {
                return new OrderResource($order);
            }
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage());
        }

        return $this->responseErrors('Access denied!');
    }

    public function changeCourier(Request $request, int $order_id)
    {
        $validator = Validator::make([...$request->all(), ...['order_id' => $order_id]], [
            'courier_id' => 'required|exists:users,id',
            'order_id' => 'required|exists:orders,id',
        ]);

        if ($validator->fails()) {
            return $this->responseErrors($validator->errors());
        }

        $courier_id = $request->get('courier_id');
        $user_id = Auth::id();
        try {
            if ($this->order_service->attachCourier($order_id, $courier_id, $user_id)) {
                return $this->responseSuccess('Courier is attached!');
            }
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage());
        }

        return $this->responseErrors('Something goes wrong!');
    }
}
