<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    //Простой механизм ролей
    public const CLIENT = 0;
    public const CUSTOMER = 1;
    public const COURIER = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function scopeCourier($query): mixed
    {
        return $query->where('role_id', self::COURIER);
    }

    public function scopeCustomer($query): mixed
    {
        return $query->where('role_id', self::CUSTOMER);
    }

    public function scopeClient($query): mixed
    {
        return $query->where('role_id', self::CLIENT);
    }
}
