<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;

final class Background implements ArrayableInterface, \JsonSerializable
{
    use CommonTrait;

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

    #[ThemeSchemaCoverage(['styles', 'background', 'backgroundImage'])]
    public function backgroundImage(string $value): self
    {
        return $this->setProperty(self::BACKGROUND_IMAGE, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'background', 'backgroundPosition'])]
    public function backgroundPosition(string $value): self
    {
        return $this->setProperty(self::BACKGROUND_POSITION, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'background', 'backgroundRepeat'])]
    public function backgroundRepeat(string $value): self
    {
        return $this->setProperty(self::BACKGROUND_REPEAT, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'background', 'backgroundSize'])]
    public function backgroundSize(string $value): self
    {
        return $this->setProperty(self::BACKGROUND_SIZE, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'background', 'backgroundAttachment'])]
    public function backgroundAttachment(string $value): self
    {
        return $this->setProperty(self::BACKGROUND_ATTACHMENT, $value);
    }
}
