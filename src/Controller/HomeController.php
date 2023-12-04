<?php

namespace App\Controller;

use Achraf\framework\Http\Response;
use App\Models\Cart;

class HomeController
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        $cart = Cart::all();

        return response(view('home'));
    }
}
