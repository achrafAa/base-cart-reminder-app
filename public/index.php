<?php

define('BASE_PATH', dirname(__DIR__));

require_once dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/framework/Bootstrap.php';

use Achraf\framework\Http\Kernel;
use Achraf\framework\Http\Request;
use Achraf\framework\Http\Response;

error_reporting(E_ALL);
set_error_handler('errorHandler');

if (php_sapi_name() !== 'cli') {
    $request = Request::createFromGlobals();
    logToFile('error', $request->getPathInfo());
    $kernel = new Kernel(app());
    try {
        $response = $kernel->handle($request);
    } catch (Exception $exception) {
        $response = new Response('Internal Server Error' . $exception->getMessage(), 500);
    }
}
$response->send();
