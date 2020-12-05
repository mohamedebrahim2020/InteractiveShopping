<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class ApiAuthController extends Controller
{
    
    /**
     * Login functionality via API using Laravel
     * Sanctum
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'email|required',
            'password' => 'required',
            'device_name' => 'required'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status_code' => 403,
                'message' => 'Unauthorized access'
            ]);
        }

        $user = User::where('email', $request->email)->first();


        if (!$user) {
            return response()->json([
                'status_code' => 403,
                'Message' => 'User not found. Please check your email',
            ], 403);
        }

        if (!Hash::check($request->password, $user->password, [])) {
            return response()->json([
                'status_code' => 403,
                'Message' => 'Please check your email or password',
            ], 403);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'status_code' => 200,
            'message' => 'Auth Successful',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'email|required|unique:users|string',
            'name' => 'string|required|min:3|max:20',
            'password' => 'required|string',
            'device_name' => 'required|string'
        ]);

        $user = User::create([
            'email' => $request->get('email'),
            'name' => $request->get('name'),
            'password' => Hash::make($request->get('password'))
        ]);

      //  $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'status_code' => 200,
            'message' => 'ٌregistered Successfully',
            'user' => $user,
            
        ], 200);

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status_code' => 200,
            'message' => 'Logout successful',
        ]);
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
   // $user = User::query()->firstOrNew(['email' => $providerUser->getEmail()]);

 
    // if (!$user->exists) {
    //     $user->name = $providerUser->getName();
    //     $user->save(); 
    // }


 return response()->json(['user'=>$providerUser]);
}

}
