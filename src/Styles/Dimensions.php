<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;

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

    #[ThemeSchemaCoverage(['styles', 'dimensions', 'aspectRatio'])]
    public function aspectRatio(string $value): self
    {
        return $this->setProperty(self::ASPECT_RATIO, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'dimensions', 'height'])]
    public function height(string $value): self
    {
        return $this->setProperty(self::HEIGHT, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'dimensions', 'minHeight'])]
    public function minHeight(string $value): self
    {
        return $this->setProperty(self::MIN_HEIGHT, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'dimensions', 'minWidth'])]
    public function minWidth(string $value): self
    {
        return $this->setProperty(self::MIN_WIDTH, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'dimensions', 'width'])]
    public function width(string $value): self
    {
        return $this->setProperty(self::WIDTH, $value);
    }
}
