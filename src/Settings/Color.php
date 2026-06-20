<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Duotone;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Gradient;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Palette;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\ColorInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\GradientInterface;

final readonly class Color
{
    use ScopedSettingsWriterTrait;

    private const SECTION = 'color';

    public const BACKGROUND = 'background';

    public const BUTTON = 'button';

    public const CAPTION = 'caption';

    public const CUSTOM = 'custom';

    public const CUSTOM_DUOTONE = 'customDuotone';

    public const CUSTOM_GRADIENT = 'customGradient';

    public const DEFAULT_DUOTONE = 'defaultDuotone';

    public const DEFAULT_GRADIENTS = 'defaultGradients';

    public const DEFAULT_PALETTE = 'defaultPalette';

    public const HEADING = 'heading';

    public const LINK = 'link';

    public const TEXT = 'text';

    public function __construct(
        private Settings $settings,
    ) {
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'text'])]
    public function enableText(): self
    {
        return $this->setBoolean(self::TEXT, true);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'text'])]
    public function disableText(): self
    {
        return $this->setBoolean(self::TEXT, false);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'background'])]
    public function enableBackground(): self
    {
        return $this->setBoolean(self::BACKGROUND, true);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'background'])]
    public function disableBackground(): self
    {
        return $this->setBoolean(self::BACKGROUND, false);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'link'])]
    public function enableLink(): self
    {
        return $this->setBoolean(self::LINK, true);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'link'])]
    public function disableLink(): self
    {
        return $this->setBoolean(self::LINK, false);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'custom'])]
    public function enableCustom(): self
    {
        return $this->setBoolean(self::CUSTOM, true);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'custom'])]
    public function disableCustom(): self
    {
        return $this->setBoolean(self::CUSTOM, false);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'customDuotone'])]
    public function enableCustomDuotone(): self
    {
        return $this->setBoolean(self::CUSTOM_DUOTONE, true);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'customDuotone'])]
    public function disableCustomDuotone(): self
    {
        return $this->setBoolean(self::CUSTOM_DUOTONE, false);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'customGradient'])]
    public function enableCustomGradient(): self
    {
        return $this->setBoolean(self::CUSTOM_GRADIENT, true);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'customGradient'])]
    public function disableCustomGradient(): self
    {
        return $this->setBoolean(self::CUSTOM_GRADIENT, false);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'defaultDuotone'])]
    public function enableDefaultDuotone(): self
    {
        return $this->setBoolean(self::DEFAULT_DUOTONE, true);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'defaultDuotone'])]
    public function disableDefaultDuotone(): self
    {
        return $this->setBoolean(self::DEFAULT_DUOTONE, false);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'defaultGradients'])]
    public function enableDefaultGradients(): self
    {
        return $this->setBoolean(self::DEFAULT_GRADIENTS, true);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'defaultGradients'])]
    public function disableDefaultGradients(): self
    {
        return $this->setBoolean(self::DEFAULT_GRADIENTS, false);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'defaultPalette'])]
    public function enableDefaultPalette(): self
    {
        return $this->setBoolean(self::DEFAULT_PALETTE, true);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'defaultPalette'])]
    public function disableDefaultPalette(): self
    {
        return $this->setBoolean(self::DEFAULT_PALETTE, false);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'heading'])]
    public function enableHeading(): self
    {
        return $this->setBoolean(self::HEADING, true);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'heading'])]
    public function disableHeading(): self
    {
        return $this->setBoolean(self::HEADING, false);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'button'])]
    public function enableButton(): self
    {
        return $this->setBoolean(self::BUTTON, true);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'button'])]
    public function disableButton(): self
    {
        return $this->setBoolean(self::BUTTON, false);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'caption'])]
    public function enableCaption(): self
    {
        return $this->setBoolean(self::CAPTION, true);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'caption'])]
    public function disableCaption(): self
    {
        return $this->setBoolean(self::CAPTION, false);
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'palette'])]
    public function addColor(string $slug, string $name, string|ColorInterface $color): self
    {
        $this->settings->addPreset(new Palette(
            $slug,
            $name,
            \is_string($color) ? new Color\Utilities\Color($color) : $color
        ));

        return $this;
    }

    /**
     * @param iterable<mixed> $colors
     */
    #[ThemeSchemaCoverage(['settings', 'color', 'palette'])]
    public function addColors(iterable $colors): self
    {
        foreach ($colors as $color) {
            if (!$color instanceof Palette) {
                throw new \InvalidArgumentException(\sprintf(
                    'Expected an instance of %s, got %s.',
                    Palette::class,
                    \get_debug_type($color),
                ));
            }

            $this->settings->addPreset($color);
        }

        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'gradients'])]
    public function addGradient(
        string $slug,
        string $name,
        GradientInterface $gradient
    ): self {

        $this->settings->addPreset(new Gradient($slug, $name, $gradient));
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'duotone'])]
    public function addDuotone(string $slug, string $name, Palette ...$colors): self
    {
        $this->settings->addPreset(new Duotone($slug, $name, ...$colors));

        return $this;
    }
}
