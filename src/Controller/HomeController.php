<?php

namespace App\Controller;

use Achraf\framework\Http\Response;

class HomeController
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        //  $cart = Cart::all();
        return response(view('home'));
    }
}
