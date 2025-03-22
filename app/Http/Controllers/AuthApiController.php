<?php

namespace App\Http\Controllers;

use App\Api\v1\Controllers\ApiController;
use App\Http\Requests\LoginApiRequest;
use App\Utils\ResponseUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthApiController
{
    public function login(LoginApiRequest $request){
        // Validation des données
        $request->validated();
        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials)){
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return ResponseUtil::responseStandard(
                'success',
                [
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                ]
            );

        }
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}

