<?php

namespace App\Repository;

use Gloudemans\Shoppingcart\Facades\Cart as FacadesCart;

class CartRepository
{
    public function add($product)
    {

        if (FacadesCart::instance('main')->merge(auth()->user()->id) == true) {
            $checkExist = FacadesCart::instance('main')->search(
                function ($cartItem, $rowId) use ($product) {

                    return $cartItem->id === $product->id;
                }
            );
            if ($checkExist->isNotEmpty()) {
                abort(404, 'this product is already exist in the cart');
            } else {
                $cartSession = FacadesCart::instance('main');
                $cartSession->add($product->id, $product->name, 1, $product->price)->associate('\App\Models\Product');
                $cartSession->erase(auth()->user()->id);
                $cartSession->store(auth()->user()->id);
                return $cartSession->content();
            }
        } else {
            $cartSession = FacadesCart::instance('main');
            $cartSession->add($product->id, $product->name, 1, $product->price)->associate('\App\Models\Product');
            $cartSession->store(auth()->user()->id);
            return $cartSession->content();
        }
    }

    public function remove($product)
    {
        if (FacadesCart::instance('main')->merge(auth()->user()->id) == true) {
            $checkExist = FacadesCart::instance('main')->search(
                function ($cartItem, $rowId) use ($product) {
                    return $cartItem->id === $product->id;
                }
            );
            if ($checkExist->isEmpty()) {
                abort(404, 'this product is already not in the cart');
            } else {
                $cartSession = FacadesCart::instance('main');
                foreach ($checkExist as $check) {
                    $row = $check->rowId;
                }
                $remove = FacadesCart::instance('main')->remove($row);
                $cartSession->erase(auth()->user()->id);
                $cartSession->store(auth()->user()->id);
                return $cartSession->content();
            }
        } else {
            abort(404, 'no cart ');
        }
    }

    public function increase($product)
    {
        if (FacadesCart::instance('main')->merge(auth()->user()->id) == true) {
            $checkExist = FacadesCart::instance('main')->search(
                function ($cartItem, $rowId) use ($product) {
                    return $cartItem->id === $product->id;
                }
            );

            if ($checkExist->isEmpty()) {
                abort(404, 'this product is not in the cart');
            } else {
                $cartSession = FacadesCart::instance('main');
                foreach ($checkExist as $check) {
                    $row = $check->rowId;
                    $qty = $check->qty;
                }
                FacadesCart::instance('main')->update($row, ++$qty);
                $cartSession->erase(auth()->user()->id);
                $cartSession->store(auth()->user()->id);
                return $cartSession->content();
            }
        } else {
            abort(404, 'no cart ');
        }
    }

    public function decrease($product)
    {
        if (FacadesCart::instance('main')->merge(auth()->user()->id) == true) {
            $checkExist = FacadesCart::instance('main')->search(
                function ($cartItem, $rowId) use ($product) {
                    return $cartItem->id === $product->id;
                }
            );

            if ($checkExist->isEmpty()) {
                abort(404, 'this product is already not in the cart');
            } else {
                $cartSession = FacadesCart::instance('main');
                foreach ($checkExist as $check) {
                    $row = $check->rowId;
                    $qty = $check->qty;
                }
                FacadesCart::instance('main')->update($row, --$qty);
                ;
                $cartSession->erase(auth()->user()->id);
                $cartSession->store(auth()->user()->id);
                return $cartSession->content();
            }
        } else {
            abort(404, 'no cart ');
        }
    }
}
