<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Styles;

final class Background implements ArrayableInterface, \JsonSerializable
{
    use CommonTrait;

    public const SECTION = 'background';

    /**
     * @var string
     */
    public const BACKGROUND_IMAGE = 'backgroundImage';

    /**
     * @var string
     */
    public const BACKGROUND_POSITION = 'backgroundPosition';

    /**
     * @var string
     */
    public const BACKGROUND_REPEAT = 'backgroundRepeat';

    /**
     * @var string
     */
    public const BACKGROUND_SIZE = 'backgroundSize';

    /**
     * @var string
     */
    public const BACKGROUND_ATTACHMENT = 'backgroundAttachment';

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::BACKGROUND_IMAGE])]
    public function backgroundImage(string $value): self
    {
        return $this->setProperty(self::BACKGROUND_IMAGE, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::BACKGROUND_POSITION])]
    public function backgroundPosition(string $value): self
    {
        return $this->setProperty(self::BACKGROUND_POSITION, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::BACKGROUND_REPEAT])]
    public function backgroundRepeat(string $value): self
    {
        return $this->setProperty(self::BACKGROUND_REPEAT, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::BACKGROUND_SIZE])]
    public function backgroundSize(string $value): self
    {
        return $this->setProperty(self::BACKGROUND_SIZE, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::BACKGROUND_ATTACHMENT])]
    public function backgroundAttachment(string $value): self
    {
        return $this->setProperty(self::BACKGROUND_ATTACHMENT, $value);
    }
}
