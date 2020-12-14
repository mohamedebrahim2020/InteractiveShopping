<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductController extends Controller
{


    public function store(Product $product, StoreProductRequest $request)
    {

        $createdProduct =  $product->storeProduct($request);
        return response()->json($createdProduct->load('images'));
    }
}
