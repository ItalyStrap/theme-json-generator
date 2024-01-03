<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Styles;

/**
 * @psalm-api
 */
final class Color implements ArrayableInterface, \JsonSerializable
{
    use CommonTrait;

    /**
     * @var string
     */
    public const BACKGROUND = 'background';

    /**
     * @var string
     */
    public const GRADIENT = 'gradient';

    /**
     * @var string
     */
    public const TEXT = 'text';

    /**
     * @var string
     */
    public const LINK = 'link';

    public function background(string $value): Color
    {
        return $this->setProperty(self::BACKGROUND, $value);
    }

    public function gradient(string $value): Color
    {
        return $this->setProperty(self::GRADIENT, $value);
    }

    public function text(string $value): Color
    {
        return $this->setProperty(self::TEXT, $value);
    }

    public function link(string $value): Color
    {
        return $this->setProperty(self::LINK, $value);
    }
}
