<?php

namespace App\Repositories;

use App\Models\Cart;
use Gloudemans\Shoppingcart\Facades\Cart as FacadesCart;

class CartRepository
{
    public function add($product)
    {
        if (FacadesCart::instance('main')->merge(auth()->user()->id)) {
            if ($this->checkProductExistence($product)->isNotEmpty()) {
                abort(404, 'this product is already exist in the cart');
            } else {
                $cartSession = FacadesCart::instance('main');
                return $this->updateCartProducts($cartSession, $product, "addtostoredcart");
            }
        } else {
            $cartSession = FacadesCart::instance('main');
            return $this->updateCartProducts($cartSession, $product, "addtonewcart");
        }
    }

    public function remove($product)
    {
        if (FacadesCart::instance('main')->merge(auth()->user()->id)) {
            if ($this->checkProductExistence($product)->isEmpty()) {
                abort(404, 'this product is already not in the cart');
            } else {
                $cartSession = FacadesCart::instance('main');
                return $this->updateCartProducts($cartSession, $product, "remove");
            }
        } else {
            abort(404, 'no cart ');
        }
    }

    public function increase($product)
    {
        if (FacadesCart::instance('main')->merge(auth()->user()->id)) {
            if ($this->checkProductExistence($product)->isEmpty()) {
                abort(404, 'this product is not in the cart');
            } else {
                $cartSession = FacadesCart::instance('main');
                return $this->updateCartProducts($cartSession, $product, "inc");
            }
        } else {
            abort(404, 'no cart ');
        }
    }

    public function decrease($product)
    {
        if (FacadesCart::instance('main')->merge(auth()->user()->id)) {
            if ($this->checkProductExistence($product)->isEmpty()) {
                abort(404, 'this product is already not in the cart');
            } else {
                $cartSession = FacadesCart::instance('main');
                return $this->updateCartProducts($cartSession, $product, "dec");
            }
        } else {
            abort(404, 'no cart ');
        }
    }

    public function cartDetails($identifier)
    {
        $stored = Cart::where('identifier', $identifier)->first();
        $storedContent = unserialize(data_get($stored, 'content'));
        $multiplied = $storedContent->map(
            function ($item, $key) {
                   return [$item->id,$item->price,$item->qty];
            }
        );
        return $multiplied;
    }
    private function checkProductExistence($product)
    {
        $checkExist = FacadesCart::instance('main')->search(
            function ($cartItem, $rowId) use ($product) {

                return $cartItem->id === $product->id;
            }
        );
        return $checkExist;
    }
    private function updateCartProducts($cartSession, $product, $action)
    {
        if ($action == "addtostoredcart" || $action == "addtonewcart") {
            $cartSession->add($product->id, $product->name, 1, $product->price)->associate('\App\Models\Product');
        }
        if ($action == "remove" || $action == "inc" || $action == "dec") {
            foreach ($this->checkProductExistence($product) as $check) {
                $row = $check->rowId;
                $qty = $check->qty;
            }
            if ($action == "remove") {
                $cartSession->remove($row);
            } elseif ($action == "inc") {
                FacadesCart::instance('main')->update($row, ++$qty);
            } else {
                FacadesCart::instance('main')->update($row, --$qty);
            }
        }
        if ($action != "addtonewcart") {
            $cartSession->erase(auth()->user()->id);
        }
        $cartSession->store(auth()->user()->id);
        return $cartSession->content();
    }
}
