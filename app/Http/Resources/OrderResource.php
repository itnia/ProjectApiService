<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'products' => ProductResource::collection($this->products),
            'user' => new UserResource($this->user)
            //TODO:: Добавь тоже самое и для пользователя
        ];
    }
}
