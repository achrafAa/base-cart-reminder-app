<?php

namespace App\Controllers;

use Achraf\framework\Http\Response;

class HomeController
{
    public function index(): Response
    {
        return response(view('home'));
    }
}
