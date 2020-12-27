<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    /**
     * Login functionality via API using Laravel
     * Sanctum
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json(
                [
                'status_code' => 403,
                'message' => 'Unauthorized access'
                ]
            );
        }

        $user = User::where('email', $request->email)->first();


        if (!$user) {
            return response()->json(
                [
                'status_code' => 403,
                'Message' => 'User not found. Please check your email',
                ],
                403
            );
        }

        if (!Hash::check($request->password, $user->password, [])) {
            return response()->json(
                [
                'status_code' => 403,
                'Message' => 'Please check your email or password',
                ],
                403
            );
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json(
            [
            'status_code' => 200,
            'message' => 'Auth Successful',
            'user' => $user,
            'token' => $token
            ]
        );
    }

    public function register(RegisterRequest $request)
    {
        User::create(
            [
            'email' => $request->get('email'),
            'name' => $request->get('name'),
            'password' => Hash::make($request->get('password'))
            ]
        );
        return response()->json(null, Response::HTTP_CREATED);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(null, Response::HTTP_OK);
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return JsonResponse
     */
    public function handleProviderCallback()
    {
        $providerUser = Socialite::driver('google')->stateless()->user();
        $user = User::firstOrCreate(
            [
                'email' => $providerUser->getEmail()
            ],
            [
                'name' => 'null',
                'password' => 'null',
            ]
        );

        $token = $user->createToken('socialite')->plainTextToken;

        return response()->json(null, Response::HTTP_OK);
    }
}
