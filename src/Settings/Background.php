<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;

final readonly class Background
{
    use ScopedSettingsWriterTrait;

    public const SECTION = 'background';

    public const BACKGROUND_IMAGE = 'backgroundImage';

    public const BACKGROUND_SIZE = 'backgroundSize';

    public const BACKGROUND_CLIP = 'backgroundClip';

    private const BACKGROUND_CLIP_VALUES = ['text', 'padding-box', 'border-box', 'content-box'];

    public const GRADIENT = 'gradient';

    public function __construct(
        private Settings $settings,
    ) {
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::BACKGROUND_IMAGE])]
    public function enableBackgroundImage(): self
    {
        return $this->setBoolean(self::BACKGROUND_IMAGE, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::BACKGROUND_IMAGE])]
    public function disableBackgroundImage(): self
    {
        return $this->setBoolean(self::BACKGROUND_IMAGE, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::BACKGROUND_SIZE])]
    public function enableBackgroundSize(): self
    {
        return $this->setBoolean(self::BACKGROUND_SIZE, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::BACKGROUND_SIZE])]
    public function disableBackgroundSize(): self
    {
        return $this->setBoolean(self::BACKGROUND_SIZE, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::BACKGROUND_CLIP])]
    public function enableBackgroundClip(): self
    {
        return $this->setBoolean(self::BACKGROUND_CLIP, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::BACKGROUND_CLIP])]
    public function disableBackgroundClip(): self
    {
        return $this->setBoolean(self::BACKGROUND_CLIP, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::BACKGROUND_CLIP])]
    public function backgroundClip(string ...$values): self
    {
        foreach ($values as $value) {
            if (!\in_array($value, self::BACKGROUND_CLIP_VALUES, true)) {
                throw new \InvalidArgumentException(\sprintf('Invalid background clip value "%s".', $value));
            }
        }

        if (\count(\array_unique($values)) !== \count($values)) {
            throw new \InvalidArgumentException('Background clip values must be unique.');
        }

        $this->set(self::BACKGROUND_CLIP, \array_values($values));
        return $this;
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::GRADIENT])]
    public function enableGradient(): self
    {
        return $this->setBoolean(self::GRADIENT, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::GRADIENT])]
    public function disableGradient(): self
    {
        return $this->setBoolean(self::GRADIENT, false);
    }
}
