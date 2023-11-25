<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings;

class NullCollection implements CollectionInterface
{
    public function get(string $key, $default = null)
    {
        return $default;
    }
}
