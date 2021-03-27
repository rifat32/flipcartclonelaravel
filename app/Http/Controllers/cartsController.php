<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class cartsController extends Controller
{
    public function create(Request $request)
    {
        $user_id = Auth::user()->id;
        $product_id = $request->product_id;
        $quantity = $request->quantity;
        $cartTable = DB::table('carts');
        $cartQuery = $cartTable->where([
            'user_id' => $user_id,
            'product_id' => $product_id
        ]);
        $cart = $cartQuery->get();
        if (count($cart)) {
            $cartItem = $cart[0];
            $cartQuery->update([
                'quantity' => $cartItem->quantity + $quantity,
                "updated_at" => \Carbon\Carbon::now()
            ]);
            return response()->json([
                'status' => 204,
                'data' => $cartQuery->get()[0]
            ]);
        } else {
            $cartQuery->insert([
                'user_id' => $user_id,
                'product_id' => $product_id,
                'quantity' => $quantity,
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" => \Carbon\Carbon::now()
            ]);
            return response()->json([
                'status' => 201,
                'data' => $cartQuery->get()[0]
            ]);
        }
    }
}
