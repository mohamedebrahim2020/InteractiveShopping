<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function AddAddress(AddressRequest $request){
        $user_addresses = auth()->user()->addresses->count();
        if ($user_addresses < 5) {
            $address = Address::create(
                [
                'title' => $request->title,
                'address_address' => $request->address_address,
                'address_type' => $request->address_type,
                'address_latitude' => $request->address_latitude,
                'address_longitude' => $request->address_longitude,
                'address_description' => $request->address_description,
                'icon' => $request->icon,
                'user_id' => auth()->user()->id
    
                ]
            );
    
            return response()->json($address);
        } else {
            return response()->json("can not have more than 5 addresses");
        }


    }

    public function getAddresses (){
        $user_addresses = auth()->user()->addresses->sortBy([['created_at','desc']]);
        return response()->json($user_addresses);
    }
}
