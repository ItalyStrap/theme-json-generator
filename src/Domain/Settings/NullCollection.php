<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings;

/**
 * @psalm-api
 */
class NullCollection implements CollectionInterface
{
    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $default;
    }

    public function value(string $key, $default = null): string
    {
        return $default;
    }
}
