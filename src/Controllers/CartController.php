<?php

namespace App\Controllers;

use Achraf\framework\Http\Response;
use App\Models\Cart;

class CartController
{
    public function show(int $cartId): Response
    {

        $cart = Cart::query()->find($cartId)->with(['items', 'items.product'])->first();

        return response(view('cart/show', ['cart' => $cart]));
    }
}
