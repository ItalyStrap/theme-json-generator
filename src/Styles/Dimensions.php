<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Styles;

final class Dimensions implements ArrayableInterface, \JsonSerializable
{
    use CommonTrait;

    public const SECTION = 'dimensions';

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

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::ASPECT_RATIO])]
    public function aspectRatio(string $value): self
    {
        return $this->setProperty(self::ASPECT_RATIO, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::HEIGHT])]
    public function height(string $value): self
    {
        return $this->setProperty(self::HEIGHT, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::MIN_HEIGHT])]
    public function minHeight(string $value): self
    {
        return $this->setProperty(self::MIN_HEIGHT, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::MIN_WIDTH])]
    public function minWidth(string $value): self
    {
        return $this->setProperty(self::MIN_WIDTH, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::WIDTH])]
    public function width(string $value): self
    {
        return $this->setProperty(self::WIDTH, $value);
    }
}
