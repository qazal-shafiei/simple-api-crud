<?php

namespace App\Http\Controllers\API\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderItem\CreateRequest;
use App\Http\Requests\OrderItem\DeleteRequest;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;

class OrderItemController extends Controller
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(OrderItem $orderItem)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $userID = $this->getAuthUser();
        $order = User::find($userID)->orders()->where('status', 'pending')->first();
        if (! $order) {
            $order = Order::create([
                'user_id' => $userID,
                'amount' => 0,
                'status' => 'pending'
            ]);
        }
        $product = Product::where('id', $request->product_id)->first();
            if ($product) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'amount' => $product->price * (int)$request->quantity,
                ]);
                $order->increment('amount', $product->price * (int)$request->quantity);
                return response()->json(['order_items' => new OrderItemResource($orderItem), 'order' => new OrderResource($order), 'message' => "selected item added to your order"], 201);
            } else {
                return response()->json(['message' => 'product not found'], 404);
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(OrderItem $orderItem)
    {
        return response()->json([
            'items in order' => $orderItem,
            'message' => 'items retervied successfully'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrderItem $orderItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OrderItem $orderItem, DeleteRequest $request)
    {
        try {
            $userID = $this->getAuthUser();
            $productPrice = Product::where('id', $orderItem->product_id)->first();
            if ($orderItem->order->amount > 0 && $orderItem->quantity >= $request->quantity) {
                DB::table('orders')->where('id', $orderItem->order_id)
                    ->decrement('amount', (int)$productPrice->price * (int)$request->quantity );
                $orderItem->decrement('quantity', (int)$request->quantity);
                $orderItem->decrement('amount', (int)$productPrice->price * (int)$request->quantity);
            } else {
                return response()->json([
                    'error' => 'quantity value is unvalid'
                ], 400);
            }
            return response()->json([
                'message' => 'item deleted successfully.'
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
