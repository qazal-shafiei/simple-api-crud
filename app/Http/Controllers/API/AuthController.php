<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use PhpParser\Node\Stmt\TryCatch;
use Tymon\JWTAuth\Exceptions\JWTException;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);
        if($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error'], 400);
        }
        $user = User::create($request->all());
            return response(['message' => 'user registered successfully', 'user' => $user], 201);
    }
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        try {
            $accessToken = JWTAuth::attempt($loginData);
            return response(['token' => $accessToken, 'message' => 'login successful'], 200);
        } catch (JWTException $e) {
            return response(['error' => $e], 400);
        }

        return response(['user' => auth()->user(), 'access_token' => $accessToken]);
    }
}
