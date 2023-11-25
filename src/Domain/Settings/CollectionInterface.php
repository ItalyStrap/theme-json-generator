<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings;

interface CollectionInterface
{
    /**
     * @return mixed
     */
    public function get(string $key, $default = null);
}
