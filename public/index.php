<?php

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH.'/src/App.php';
use Achraf\framework\Http\Kernel;
use Achraf\framework\Http\Request;
use Achraf\framework\Http\Response;

$request = Request::createFromGlobals();
logToFile('error', $request->getPathInfo());
$kernel = new Kernel(app());
try {
    $response = $kernel->handle($request);
} catch (Exception $exception) {
    $response = new Response('Internal Server Error'.$exception->getMessage(), 500);
}

$response->send();
