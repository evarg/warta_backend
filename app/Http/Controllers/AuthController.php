<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;


use App\Http\Requests\AuthLoginRequest;
use JsonException;

class AuthController extends Controller
{
    function store(AuthLoginRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
        } else {
            return new JsonResponse(['message' => 'Zły login lub hasło'], 401);
        }

        // Delete old tokens
        //Auth::user()->tokens->delete();

        //$token = $request->user()->createToken($request->bearerToken());
        return new JsonResponse(Auth::user(), 201);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
