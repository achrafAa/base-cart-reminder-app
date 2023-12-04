<?php

namespace Achraf\framework\Http;

readonly class Response
{
    /**
     * @param  string|null  $content
     * @param  int  $status
     * @param  array  $headers
     */
    public function __construct(
        private ?string $content = null,
        private int $status = 200,
        private array $headers = []
    ) {
    }

    /**
     * @return void
     */
    public function send(): void
    {
        echo $this->content;
    }
}
