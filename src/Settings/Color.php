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
        $this->set(self::TEXT, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'text'])]
    public function disableText(): self
    {
        $this->set(self::TEXT, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'background'])]
    public function enableBackground(): self
    {
        $this->set(self::BACKGROUND, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'background'])]
    public function disableBackground(): self
    {
        $this->set(self::BACKGROUND, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'link'])]
    public function enableLink(): self
    {
        $this->set(self::LINK, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'link'])]
    public function disableLink(): self
    {
        $this->set(self::LINK, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'custom'])]
    public function enableCustom(): self
    {
        $this->set(self::CUSTOM, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'custom'])]
    public function disableCustom(): self
    {
        $this->set(self::CUSTOM, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'customDuotone'])]
    public function enableCustomDuotone(): self
    {
        $this->set(self::CUSTOM_DUOTONE, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'customDuotone'])]
    public function disableCustomDuotone(): self
    {
        $this->set(self::CUSTOM_DUOTONE, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'customGradient'])]
    public function enableCustomGradient(): self
    {
        $this->set(self::CUSTOM_GRADIENT, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'customGradient'])]
    public function disableCustomGradient(): self
    {
        $this->set(self::CUSTOM_GRADIENT, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'defaultDuotone'])]
    public function enableDefaultDuotone(): self
    {
        $this->set(self::DEFAULT_DUOTONE, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'defaultDuotone'])]
    public function disableDefaultDuotone(): self
    {
        $this->set(self::DEFAULT_DUOTONE, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'defaultGradients'])]
    public function enableDefaultGradients(): self
    {
        $this->set(self::DEFAULT_GRADIENTS, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'defaultGradients'])]
    public function disableDefaultGradients(): self
    {
        $this->set(self::DEFAULT_GRADIENTS, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'defaultPalette'])]
    public function enableDefaultPalette(): self
    {
        $this->set(self::DEFAULT_PALETTE, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'defaultPalette'])]
    public function disableDefaultPalette(): self
    {
        $this->set(self::DEFAULT_PALETTE, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'heading'])]
    public function enableHeading(): self
    {
        $this->set(self::HEADING, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'heading'])]
    public function disableHeading(): self
    {
        $this->set(self::HEADING, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'button'])]
    public function enableButton(): self
    {
        $this->set(self::BUTTON, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'button'])]
    public function disableButton(): self
    {
        $this->set(self::BUTTON, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'caption'])]
    public function enableCaption(): self
    {
        $this->set(self::CAPTION, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color', 'caption'])]
    public function disableCaption(): self
    {
        $this->set(self::CAPTION, false);
        return $this;
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
