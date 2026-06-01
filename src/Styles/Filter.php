<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

final class Filter implements ArrayableInterface, \JsonSerializable
{
    use CommonTrait;

    /**
     * @var string
     */
    public const DUOTONE = 'duotone';

    public function duotone(string $value): self
    {
        return $this->setProperty(self::DUOTONE, $value);
    }
}
