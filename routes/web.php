<?php

use App\Controller\HomeController;

return [
   ['GET', '/', [HomeController::class, 'index']],
];
