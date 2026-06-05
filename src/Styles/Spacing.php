<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;

final class Spacing implements ArrayableInterface, \JsonSerializable
{
    use CommonTrait;

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

    #[ThemeSchemaCoverage(['styles', 'spacing', 'blockGap'])]
    public function blockGap(string $value): self
    {
        return $this->setProperty(self::BLOCK_GAP, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'spacing', 'margin'])]
    public function margin(): BoxSpacing
    {
        return new BoxSpacing($this->at(self::MARGIN));
    }

    #[ThemeSchemaCoverage(['styles', 'spacing', 'padding'])]
    public function padding(): BoxSpacing
    {
        return new BoxSpacing($this->at(self::PADDING));
    }
}
