<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AddressController extends Controller
{
    public function addAddress(AddressRequest $request)
    {
        $user_addresses = auth()->user()->addresses->count();
        if ($user_addresses < 7) {
            $data = $request->all();
            $data['user_id'] = auth()->user()->id;
            $address = Address::create($data);
            return response()->json($address);
        } else {
            return response()->json(["error" => "can not have more than 5 addresses"], 400);
        }
    }

    public function getAddresses()
    {
        $user_addresses = auth()->user()->addresses->sortBy([['created_at','desc']]);
        //UserResource::collection(User::all())
        return response()->json($UserResource::collection($user_addresses));
    }
}
