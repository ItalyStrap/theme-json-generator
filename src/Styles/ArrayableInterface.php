<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

/**
 * @psalm-api
 */
interface ArrayableInterface
{
    /**
     * @return array<string|int, string>
     */
    public function toArray(): array;
}
