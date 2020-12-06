<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;

class ProductController extends Controller
{


    public function store(Product $product, StoreProductRequest $request)
    {

        $createdProduct =  $product->storeProduct($request->name, $request->price, $request->file('image'));
        return response()->json($createdProduct->load('images'));
    }
}
