<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

final class Dimensions implements ArrayableInterface, \JsonSerializable
{
    use CommonTrait;

    /**
     * @var string
     */
    public const ASPECT_RATIO = 'aspectRatio';

    /**
     * @var string
     */
    public const HEIGHT = 'height';

    /**
     * @var string
     */
    public const MIN_HEIGHT = 'minHeight';

    /**
     * @var string
     */
    public const MIN_WIDTH = 'minWidth';

    /**
     * @var string
     */
    public const WIDTH = 'width';

    public function aspectRatio(string $value): self
    {
        return $this->setProperty(self::ASPECT_RATIO, $value);
    }

    public function height(string $value): self
    {
        return $this->setProperty(self::HEIGHT, $value);
    }

    public function minHeight(string $value): self
    {
        return $this->setProperty(self::MIN_HEIGHT, $value);
    }

    public function minWidth(string $value): self
    {
        return $this->setProperty(self::MIN_WIDTH, $value);
    }

    public function width(string $value): self
    {
        return $this->setProperty(self::WIDTH, $value);
    }
}
