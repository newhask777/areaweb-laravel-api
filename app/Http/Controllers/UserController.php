<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login(LoginRequest $request)
    {
        $email = $request->email;
        $password = $request->password;

        if ( !Auth::guard('web')->attempt(['email' => $email, 'password' => $password]) )
        {
            return response()->json([
                'message' => "Login failled"
            ], 401);
        }

        $user = Auth::guard('web')->user();
        $token = Str::random(60);

        $user->update(['api_token' => $token]);

        return ['token' => $token];

//        dd(Auth::user());

    }
}
