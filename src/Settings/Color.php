<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\GradientInterface;
use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Duotone;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Gradient;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Palette;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\ColorInterface;

final readonly class Color
{
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

    public function enableText(): self
    {
        $this->set(self::TEXT, true);
        return $this;
    }

    public function disableText(): self
    {
        $this->set(self::TEXT, false);
        return $this;
    }

    public function enableBackground(): self
    {
        $this->set(self::BACKGROUND, true);
        return $this;
    }

    public function disableBackground(): self
    {
        $this->set(self::BACKGROUND, false);
        return $this;
    }

    public function enableLink(): self
    {
        $this->set(self::LINK, true);
        return $this;
    }

    public function disableLink(): self
    {
        $this->set(self::LINK, false);
        return $this;
    }

    public function enableCustom(): self
    {
        $this->set(self::CUSTOM, true);
        return $this;
    }

    public function disableCustom(): self
    {
        $this->set(self::CUSTOM, false);
        return $this;
    }

    public function enableCustomDuotone(): self
    {
        $this->set(self::CUSTOM_DUOTONE, true);
        return $this;
    }

    public function disableCustomDuotone(): self
    {
        $this->set(self::CUSTOM_DUOTONE, false);
        return $this;
    }

    public function enableCustomGradient(): self
    {
        $this->set(self::CUSTOM_GRADIENT, true);
        return $this;
    }

    public function disableCustomGradient(): self
    {
        $this->set(self::CUSTOM_GRADIENT, false);
        return $this;
    }

    public function enableDefaultDuotone(): self
    {
        $this->set(self::DEFAULT_DUOTONE, true);
        return $this;
    }

    public function disableDefaultDuotone(): self
    {
        $this->set(self::DEFAULT_DUOTONE, false);
        return $this;
    }

    public function enableDefaultGradients(): self
    {
        $this->set(self::DEFAULT_GRADIENTS, true);
        return $this;
    }

    public function disableDefaultGradients(): self
    {
        $this->set(self::DEFAULT_GRADIENTS, false);
        return $this;
    }

    public function enableDefaultPalette(): self
    {
        $this->set(self::DEFAULT_PALETTE, true);
        return $this;
    }

    public function disableDefaultPalette(): self
    {
        $this->set(self::DEFAULT_PALETTE, false);
        return $this;
    }

    public function enableHeading(): self
    {
        $this->set(self::HEADING, true);
        return $this;
    }

    public function disableHeading(): self
    {
        $this->set(self::HEADING, false);
        return $this;
    }

    public function enableButton(): self
    {
        $this->set(self::BUTTON, true);
        return $this;
    }

    public function disableButton(): self
    {
        $this->set(self::BUTTON, false);
        return $this;
    }

    public function enableCaption(): self
    {
        $this->set(self::CAPTION, true);
        return $this;
    }

    public function disableCaption(): self
    {
        $this->set(self::CAPTION, false);
        return $this;
    }

    public function addColor(string $slug, string $name, string|ColorInterface $color): self
    {
        $this->settings->addPreset(['color', 'palette'], new Palette(
            $slug,
            $name,
            \is_string($color) ? new Color\Utilities\Color($color) : $color
        ));

        return $this;
    }

    /**
     * @param iterable<mixed> $colors
     */
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

            $this->settings->addPreset(['color', 'palette'], $color);
        }

        return $this;
    }

    public function addGradient(
        string $slug,
        string $name,
        GradientInterface $gradient
    ): self {

        $this->settings
            ->addPreset(['color', 'gradients'], new Gradient(
                $slug,
                $name,
                $gradient
            ));
        return $this;
    }

    public function addDuotone(string $slug, string $name, Palette ...$colors): self
    {
        $this->settings->addPreset(['color', 'duotone'], new Duotone($slug, $name, ...$colors));

        return $this;
    }

    /**
     * @param array<array-key, string|int>|string $path
     */
    public function set(array|string $path, mixed $value): bool
    {
        if (\is_string($path)) {
            $path = \explode('.', $path);
        }

        return $this->settings->set(['color', ...$path], $value);
    }
}
