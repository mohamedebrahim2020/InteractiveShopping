<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCancellationReason extends Model
{
    use HasFactory;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cancel_order_reasons';
    protected $fillable = [
        'reason_desc_en',
        'reason_desc_ar'
    ];
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}