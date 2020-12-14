<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'addresses';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title' ,
        'address_address' ,
        'address_type' ,
        'address_latitude' ,
        'address_longitude' ,
        'address_description' ,
        'icon',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
