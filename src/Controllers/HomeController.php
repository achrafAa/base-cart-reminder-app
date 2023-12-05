<?php

namespace App\Controllers;

use Achraf\framework\Http\Response;

class HomeController
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return response(view('home'));
    }
}
