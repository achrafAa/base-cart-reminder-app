<?php

use Achraf\framework\Application;
use Achraf\framework\Config\Config;
use Achraf\framework\Http\Response;
use Achraf\framework\Logger\Logger;
use Achraf\framework\View\View;

if (! function_exists('app')) {

    function app(): Application
    {
        return Application::getInstance();
    }
}

if (! function_exists('config')) {

    function config(string $key): string
    {
        return Config::getValue($key);
    }
}

if (! function_exists('view')) {

    function view(string $view, array $data = []): string
    {
        try {
            return app()->get(View::class)->render($view, $data);
        } catch (Exception $exception) {
            logToFile('error', sprintf('Error loading template %s', $view));
            logToFile('error', sprintf(
                'File: %s Line: %s   Error: %s ',
                $exception->getFile(),
                $exception->getLine(),
                $exception->getMessage()
            ));

            return '<h1>Something went wrong</h1>'.$exception->getMessage();
        }
    }
}

if (! function_exists('response')) {

    function response(string $content, int $status = 200, array $headers = []): Response
    {
        return new Response($content, $status, $headers);
    }
}

if (! function_exists('logToFile')) {

    function logToFile(string $level = 'info', string $message = ''): void
    {
        if (! in_array($level, ['info', 'error', 'warning', 'debug'])) {
            throw new InvalidArgumentException('Invalid log level');
        }
        app()->get(Logger::class)->{$level}($message);
    }
}

if (! function_exists('errorHandler')) {

    function errorHandler(int $errno, string $errstr, string $errfile, int $errline): void
    {
        logToFile('error', sprintf('Error: %s File: %s Line: %s', $errstr, $errfile, $errline));
        if (php_sapi_name() !== 'cli') {
            return;
        }
        if (config('APP_ENV') === 'development') {
            $response = new Response(
                sprintf('Error: %s File: %s Line: %s', $errstr, $errfile, $errline),
                500
            );
            $response->send();
        } else {
            $response = new Response('Internal Server Error', 500);
            $response->send();
        }
    }
}
