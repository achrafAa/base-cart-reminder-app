<?php

use App\Controllers\CartController;
use App\Controllers\CheckoutController;
use App\Controllers\HomeController;

return [
    ['GET', '/', [HomeController::class, 'index']],
    ['GET', '/cart/{cartId:\d+}', [CartController::class, 'show']],
    ['POST', '/checkout/{cartId:\d+}', [CheckoutController::class, 'checkout']],
];
