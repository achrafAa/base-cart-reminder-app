<?php

namespace Achraf\framework\Http;

readonly class Request
{
    /**
     * @param  array  $getParams
     * @param  array  $postParams
     * @param  array  $server
     * @param  array  $cookies
     * @param  array  $files
     */
    public function __construct(
        public array $getParams = [],
        public array $postParams = [],
        public array $server = [],
        public array $cookies = [],
        public array $files = [],
    ) {
    }

    /**
     * @return static
     */
    public static function createFromGlobals():static
    {
        return new static(
            $_GET,
            $_POST,
            $_SERVER,
            $_COOKIE,
            $_FILES,
        );
    }

    /**
     * @return string
     */
    public function getPathInfo():string
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }

    /**
     * @return string
     */
    public function getMethod():string
    {
        return $this->server['REQUEST_METHOD'];
    }
}
