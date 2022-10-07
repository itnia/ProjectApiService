<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    // Цена за 1 км(пока фиксированная)
    const COST_PER_KM = 0.5;
    
    //1 Статический адресс отправки
    const DEPARTURE_ADDRESS = 'Tolstogo 10 street';

    public $timestamps = false;

    protected $fillable = [
        'address_id',
        'delivery_date',
        'delivery_price',
        'courier_id',
        'active',
        'created_by',
        'updated_by'
    ];

    /**
     * Только активные заказы
     * @param $query
     * @return mixed
     */
    public function scopeActive($query): mixed
    {
        return $query->where('active', 1);
    }

    /**
     * Продукты заказа
     *
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_products');
    }

    /**
     * Пользователель к которому привязан заказ
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
