<?php

namespace Achraf\framework\Config;

final class Config
{
    /**
     * @var array|mixed
     */
    private static array $values;

    /**
     * @param  array  $values
     * @return void
     */
    public function __construct(array $values = [])
    {
        self::$values = $values;
    }

    /**
     * @param  string  $key
     * @return string
     */
    public static function getValue(string $key): string
    {
        return self::$values[$key] ?? '';
    }

    /**
     * @return void
     */
    private function __clone()
    {
    }
}
