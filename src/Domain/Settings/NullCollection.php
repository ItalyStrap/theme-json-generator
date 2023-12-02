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

    /**
     * @param string $key
     * @param mixed $default
     * @return string
     */
    public function value(string $key, $default = null): string
    {
        return (string)$default;
    }

    public function add(ItemInterface $item): CollectionInterface
    {
        return $this;
    }
}
