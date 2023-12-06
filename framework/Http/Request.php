<?php

namespace Achraf\framework\Http;

readonly class Request
{
    public function __construct(
        public array $getParams = [],
        public array $postParams = [],
        public array $server = [],
        public array $cookies = [],
        public array $files = [],
    ) {
    }

    public static function createFromGlobals(): static
    {
        return new static(
            $_GET,
            $_POST,
            $_SERVER,
            $_COOKIE,
            $_FILES,
        );
    }

    public function getPathInfo(): string
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }

    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }
}
