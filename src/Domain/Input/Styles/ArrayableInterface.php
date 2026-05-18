<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles;

interface ArrayableInterface
{
    /**
     * @return array<array-key, string>
     */
    public function toArray(): array;
}
