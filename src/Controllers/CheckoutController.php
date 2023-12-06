<?php

namespace App\Controllers;

use Achraf\framework\Http\Response;
use App\Models\Cart;

class CheckoutController
{
    public function checkout(int $cartId): Response
    {
        // check auth skipped
        $cart = Cart::find($cartId);
        if (! $cart) {
            return response(json_encode(['error' => 'Cart not found']), 404);
        }
        // process payment and delivery data skipped
        $cart->delete();

        return response(json_encode(['success' => true]));
    }
}
