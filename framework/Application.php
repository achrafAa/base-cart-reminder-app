<?php

namespace Achraf\framework;

use Achraf\framework\Container\Container;

class Application extends Container
{
    public static ?self $instance = null;

    public static function getInstance(): self|static
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
