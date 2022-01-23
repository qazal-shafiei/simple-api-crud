<?php

namespace App\Http\Controllers\API\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CreateRequest;
use App\Http\Resources\OrderItemResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class OrderController extends Controller
{
    /**
     * @return string
     */
    public function getAuthUser()
    {
        $userID = "";
        if(Auth::guard('api')->check()) {
            $userID = auth('api')->user()->getKey();
        }
        return $userID;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateRequest $request)
    {
        try {
            $userID = $this->getAuthUser();
            $orders = Order::where('user_id', $userID)->get();
            if ($orders->isEmpty()) {
                $order = Order::create([
                    'user_id' => $userID,
                    'amount' => 0,
                    'status' => 'pending'
                ]);
                return response()->json(['order' => new OrderResource($order),'message' => 'order created successfully.'], 201);
            } else {
                return response()->json(['message' => 'you have an open order'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Order $order)
    {
        try {
                return response()->json([
                    'items in order' => $order->orderItem()->get(),
                    'order' => $order,
                    'message' => 'items in order retrieved'
                ], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Order $order)
    {
        try {
            $order->delete();
            return response()->json([
                'message' => 'order deleted successfully.'
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
