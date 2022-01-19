<?php

namespace App\Http\Controllers\API\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use function auth;
use function response;

class CartController extends Controller
{
    public function getAuthUser()
    {
        $userID = "";
        if (Auth::guard('api')->check()){
            $userID = auth('api')->user()->getKey();
            return $userID;
        }
        return false;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $userID = $this->getAuthUser();
        if (! Cart::where('user_id', $userID)->count() >= 1) {
            try {
                Cart::create([
                    'user_id' => $userID,
                ]);
                return response(['user_id' => $userID, 'message' => 'new cart created successfully.'], 201);
            } catch (JWTException $e) {
                return response(['error' => $e->getMessage()], 400);
            }
        }
        return response(['message' => 'you already have an Cart'], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
