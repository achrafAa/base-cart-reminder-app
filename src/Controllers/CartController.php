<?php

namespace App\Controllers;

use Achraf\framework\Http\Response;
use App\Models\Cart;

class CartController
{
    /**
     * @param  int  $cartId
     * @return Response
     */
    public function show(int $cartId): Response
    {
        $cart = Cart::find($cartId)->with('items')->first();

        return response(view('cart/show', ['cart' => $cart]));
    }
}
