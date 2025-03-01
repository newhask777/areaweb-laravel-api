<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Sanctum\NewAccessToken;

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

        /**
         * @var NewAccessToken $token
         */
        $token = $user->createToken('login');
//        dd($token);

        $user->update(['api_token' => $token]);

        return ['token' => $token->plainTextToken];

//        dd(Auth::user());

    }
}
