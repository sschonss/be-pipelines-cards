<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = new User();
        $validateEmail = $user->emailExists($request->email);
        if ($validateEmail) {
            return response()->json(['error' => 'Email already exists'], 400);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(['status' => 'success'], 201);
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        JWTAuth::invalidate();
        return response()->json(['status' => 'success', 'message' => 'User logged out successfully'], 200);
    }

    public function user(): \Illuminate\Http\JsonResponse
    {
        return response()->json(JWTAuth::user());
    }

    public function redirectToGoogle(): \Illuminate\Http\JsonResponse | \Illuminate\Http\RedirectResponse
    {
        $redirect = Socialite::driver('google')->redirect();

        if (!$redirect) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $redirect;

    }

    public function handleGoogleCallback(): \Illuminate\Http\JsonResponse
    {
        $googleUser = Socialite::driver('google')->user();
        $user = User::where('email', $googleUser->email)->first();
        if(!$user)
        {
            $user = User::create(['name' => $googleUser->name, 'email' => $googleUser->email, 'password' => Hash::make(rand(100000,999999))]);
        }

        $token = JWTAuth::fromUser($user);

        return $this->respondWithToken($token);
    }


}

