<?php

namespace App\Models;

use Gloudemans\Shoppingcart\Cart as ShoppingcartCart;
use Gloudemans\Shoppingcart\Facades\Cart as FacadesCart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    
    public function __construct($product)
    {
        $this->product = $product;
    }
    public function add(){
        $product = Product::find($this->product);
        
        if (! $product) {
            return "not a valid product";
            
        }
        if (FacadesCart::instance('main')->merge(auth()->user()->id) == true) {
            $checkExist = FacadesCart::instance('main')->search(function ($cartItem, $rowId) use ($product) {
              
                return $cartItem->id === $product->id;
            });
            if ($checkExist->isNotEmpty()) {
                return "product is already in the cart";
            } else {
            $cartSession= FacadesCart::instance('main');
            $item = $cartSession->add($product->id, $product->name, 1,$product->price)->associate('\App\Product');
            $cartSession->erase(auth()->user()->id);
            $cartSession->store(auth()->user()->id);
            return $cartSession->content();
        }
        } else {
             $cartSession= FacadesCart::instance('main');
             $item = $cartSession->add($product->id, $product->name, 1,$product->price)->associate('\App\Models\Product');
             $cartSession->store(auth()->user()->id);
             return $cartSession->content;            }
    }

    public function remove(){
        $product = Product::find($this->product);
        if (! $product) {
            return "not a valid product";
        }
        if (FacadesCart::instance('main')->merge(auth()->user()->id) == true) {
            $checkExist = FacadesCart::instance('main')->search(function ($cartItem, $rowId) use ($product) {
                return $cartItem->id === $product->id;
            });
            if ($checkExist->isEmpty()) {
               return "product is already not in the cart";
            } else {
            $cartSession= FacadesCart::instance('main');
            foreach ($checkExist as $check) {
                $row = $check->rowId;
            }
            $remove = FacadesCart::instance('main')->remove($row);
            $cartSession->erase(auth()->user()->id);
            $cartSession->store(auth()->user()->id);
            return $cartSession->content();
            }
        } else {

             return  "no cart for this user";
            }
    }

    public function increase(){
        $product = Product::find($this->product);
        if (! $product) {
            return "not a valid product";
        }
        if (FacadesCart::instance('main')->merge(auth()->user()->id) == true) {
            $checkExist = FacadesCart::instance('main')->search(function ($cartItem, $rowId) use ($product) {
                return $cartItem->id === $product->id;
            });
      
            if ($checkExist->isEmpty()) {
               return "product is already not in the cart";
            } else {
            $cartSession= FacadesCart::instance('main');
            foreach ($checkExist as $check) {
                $row = $check->rowId;
                $qty = $check->qty;
            }
            $updateQuantity = FacadesCart::instance('main')->update($row, ++$qty);;
            $cartSession->erase(auth()->user()->id);
            $cartSession->store(auth()->user()->id);
            return $cartSession->content();
        }
        } else {

             return "no cart ";
            }
    }

    public function decrease(){
        $product = Product::find($this->product);
        if (! $product) {
            return "not a valid product";
        }
        if (FacadesCart::instance('main')->merge(auth()->user()->id) == true) {
            $checkExist = FacadesCart::instance('main')->search(function ($cartItem, $rowId) use ($product) {
                return $cartItem->id === $product->id;
            });
      
            if ($checkExist->isEmpty()) {
               return "product is already not in the cart";
            } else {
            $cartSession= FacadesCart::instance('main');
            foreach ($checkExist as $check) {
                $row = $check->rowId;
                $qty = $check->qty;
            }
            $updateQuantity = FacadesCart::instance('main')->update($row, --$qty);;
            $cartSession->erase(auth()->user()->id);
            $cartSession->store(auth()->user()->id);
            return $cartSession->content();
        }
        } else {

             return "no cart ";
            }
    }
}
