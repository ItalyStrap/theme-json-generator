<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;

final class Outline implements ArrayableInterface, \JsonSerializable
{
    use CommonTrait;

    /**
     * @var string
     */
    public const COLOR = 'color';

    /**
     * @var string
     */
    public const OFFSET = 'offset';

    /**
     * @var string
     */
    public const STYLE = 'style';

    /**
     * @var string
     */
    public const WIDTH = 'width';

    #[ThemeSchemaCoverage(['styles', 'outline', 'color'])]
    public function color(string $value): Outline
    {
        return $this->setProperty(self::COLOR, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'outline', 'offset'])]
    public function offset(string $value): Outline
    {
        return $this->setProperty(self::OFFSET, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'outline', 'style'])]
    public function style(string $value): Outline
    {
        return $this->setProperty(self::STYLE, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'outline', 'width'])]
    public function width(string $value): Outline
    {
        return $this->setProperty(self::WIDTH, $value);
    }
}
