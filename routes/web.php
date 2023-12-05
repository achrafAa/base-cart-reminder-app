<?php

use App\Controllers\CartController;
use App\Controllers\CheckoutController;
use App\Controllers\HomeController;

return [
    ['GET', '/', [HomeController::class, 'index']],
    ['GET', '/cart/{id:\d+}', [CartController::class, 'show']],
    ['GET', '/checkout/{id:\d+}', [CheckoutController::class, 'checkout']],
];
