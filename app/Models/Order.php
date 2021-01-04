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
        'order_status_id',
        'cancel_reason_id',
        'other_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_details', 'order_id', 'product_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'order_tag', 'order_id', 'tag_id');
    }

    public function cancellationReason()
    {
        return $this->belongsTo(OrderCancellationReason::class, 'cancel_reason_id');
    }

}
