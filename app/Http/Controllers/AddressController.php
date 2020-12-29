<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\Response;

class AddressController extends Controller
{
    public function store(AddressRequest $request)
    {
        $user_addresses = auth()->user()->addresses->count();
        if ($user_addresses < 7) {
            $data = $request->all();
            $data['user_id'] = auth()->user()->id;
            Address::create($data);
            return response()->json(null, Response::HTTP_CREATED);
        } else {
            return response()->json(__('messages.addresserror'), Response::HTTP_BAD_REQUEST);
        }
    }

    public function index()
    {
        $user_addresses = auth()->user()->addresses->sortBy([['created_at','desc']]);
        return response()->json(AddressResource::collection($user_addresses), Response::HTTP_OK);
    }
}
