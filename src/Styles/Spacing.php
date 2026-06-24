<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Styles;

final class Spacing implements ArrayableInterface, \JsonSerializable
{
    use CommonTrait;

    public const SECTION = 'spacing';

    /**
     * @var string
     */
    public const TOP = 'top';

    /**
     * @var string
     */
    public const RIGHT = 'right';

    /**
     * @var string
     */
    public const BOTTOM = 'bottom';

    /**
     * @var string
     */
    public const LEFT = 'left';

    /**
     * @var string
     */
    public const BLOCK_GAP = 'blockGap';

    /**
     * @var string
     */
    public const MARGIN = 'margin';

    /**
     * @var string
     */
    public const PADDING = 'padding';

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::BLOCK_GAP])]
    public function blockGap(string $value): self
    {
        return $this->setProperty(self::BLOCK_GAP, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::MARGIN])]
    public function margin(): BoxSpacing
    {
        return new BoxSpacing($this->at(self::MARGIN));
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::PADDING])]
    public function padding(): BoxSpacing
    {
        return new BoxSpacing($this->at(self::PADDING));
    }
}
