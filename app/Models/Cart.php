<?php

namespace App\Models;

use Gloudemans\Shoppingcart\Cart as ShoppingcartCart;
use Gloudemans\Shoppingcart\Facades\Cart as FacadesCart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Cart extends Model
{
    use HasFactory;

    public $product;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shoppingcart';
   
}
