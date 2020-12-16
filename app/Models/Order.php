<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
    protected $fillable = [
        'user_id',
        'total_price',
        'payment_id',
        'address_id',
        'delivery_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
