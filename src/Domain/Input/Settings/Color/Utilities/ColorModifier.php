<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities;

use Exception;

/**
 * @psalm-api
 */
final class ColorModifier implements ColorModifierInterface
{
    private ColorInterface $color;

    private ColorFactoryInterface $color_factory;

    private string $initialType;

    /**
     * @throws Exception
     */
    public function __construct(ColorInterface $color, ColorFactoryInterface $factory = null)
    {
        $this->color = $color;
        $this->color_factory = $factory ?? new ColorFactory();
        $this->initialType = $this->color->type();
    }

    public function color(): ColorInterface
    {
        return $this->color;
    }

    public function tint(float $weight = 0): ColorInterface
    {
        return $this->mixWith('rgb(255,255,255)', $weight);
    }

    public function shade(float $weight = 0): ColorInterface
    {
        return $this->mixWith('rgb(0,0,0)', $weight);
    }

    public function tone(float $weight = 0): ColorInterface
    {
        return $this->mixWith('rgb(128,128,128)', $weight);
    }

    public function opacity(float $alpha = 1): ColorInterface
    {
        return $this->createNewColorWithChangedLightnessOrOpacity(0, $alpha);
    }

    public function darken(int $amount = 0): ColorInterface
    {
        return $this->createNewColorWithChangedLightnessOrOpacity(-$amount);
    }

    public function lighten(int $amount = 0): ColorInterface
    {
        return $this->createNewColorWithChangedLightnessOrOpacity($amount);
    }

    public function saturate(int $amount = 0): ColorInterface
    {
        return $this->createNewColorWithChangedSaturation($amount);
    }

    public function contrast(int $amount = 0): ColorInterface
    {
        return $this->createNewColorWithChangedContrast($amount);
    }

    public function complementary(): ColorInterface
    {
        if ($this->color->hue() === 0 && $this->color->saturation() === 0) {
            return $this->color;
        }

        return $this->hueRotate($this->color->hue() + 180);
    }

    public function invert(): ColorInterface
    {
        return $this->createNewColorFrom(
            (string) $this->color->hue(),
            (string) $this->color->saturation(),
            (string) $this->sanitizeFromFloatToInteger(100 - $this->color->lightness()),
            (string) $this->color->alpha()
        );
    }

    public function hueRotate(int $amount = 0): ColorInterface
    {
        $sumHue = $this->color->hue() + $amount;

        if ($sumHue < 0) {
            $sumHue = 360 + $sumHue;
        }

        return $this->createNewColorFrom(
            (string) $sumHue,
            (string) $this->color->saturation(),
            (string) $this->color->lightness(),
            (string) $this->color->alpha()
        );
    }

    private function createNewColorWithChangedLightnessOrOpacity(int $amount, float $alpha = 1): ColorInterface
    {
        return $this->createNewColorFrom(
            (string) $this->color->hue(),
            (string) $this->color->saturation(),
            (string) $this->sanitizeFromFloatToInteger($this->color->lightness() + $amount),
            (string) $alpha
        );
    }

    private function createNewColorWithChangedSaturation(int $amount): ColorInterface
    {
        return $this->createNewColorFrom(
            (string) $this->color->hue(),
            (string) $this->sanitizeFromFloatToInteger($this->color->saturation() + $amount),
            (string) $this->color->lightness(),
            (string) $this->color->alpha()
        );
    }

    private function createNewColorWithChangedContrast(int $amount): ColorInterface
    {
        return $this->createNewColorFrom(
            (string) $this->color->hue(),
            (string) $this->sanitizeFromFloatToInteger($this->color->saturation() + $amount),
            (string) $this->sanitizeFromFloatToInteger($this->color->lightness() + $amount),
            (string) $this->color->alpha()
        );
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedReturnStatement
     */
    private function createNewColorFrom(
        string $hue,
        string $saturation,
        string $lightness,
        string $alpha
    ): ColorInterface {
        $newColor = $this->color_factory->fromColorString(\sprintf(
            'hsla(%s, %s%%, %s%%, %d)',
            $hue,
            $saturation,
            $lightness,
            \ctype_digit($alpha) ? $alpha : \hexdec($alpha) / 255
        ));

        return $this->callMethodOnColorObject($newColor);
    }

    /**
     * @todo Is it a good idea to make it public?
     *       Evaluate possible side effects.
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedReturnStatement
     */
    private function mixWith(string $color_string, float $weight = 0): ColorInterface
    {
        /**
         * I need to cast to RGB or RGBA because the mixRgb method
         * uses calculation over `ColorInfo::red()` and `ColorInfo::green()` and `ColorInfo::blue()`
         * as a number (0 to 255) and not as string (ff, or 00 or whatever)
         * So the cast here is necessary
         */
        $result = $this->mixRgb(
            $this->color_factory->fromColorString($color_string)->toRgba(),
            $this->color->toRgba(),
            $weight > 1 ? $weight / 100 : $weight
        );

        $newColor = $this->color_factory->fromColorString(\sprintf(
            'rgb(%s)',
            \implode(',', $result)
        ));

        return $this->callMethodOnColorObject($newColor);
    }

    /**
     * @return array<array-key, int|float>
     */
    private function mixRgb(ColorInterface $color_1, ColorInterface $color_2, float $weight = 0.5): array
    {
        $f = static fn (int $x): float => $weight * $x;
        $g = static fn (int $x): float => (1 - $weight) * $x;
        $h = static fn (float $x, float $y): float => \round($x + $y);

        return \array_map(
            $h,
            \array_map($f, [ (int)$color_1->red(), (int)$color_1->green(), (int)$color_1->blue() ]),
            \array_map($g, [ (int)$color_2->red(), (int)$color_2->green(), (int)$color_2->blue() ])
        );
    }

    private function sanitizeFromFloatToInteger(float $value): int
    {
        return $value > 100
            ? 100
            : ($value < 0 ? 0 : (int) $value);
    }

    /**
     * @param ColorInterface $newColor
     * @return mixed
     * @throws Exception
     */
    private function callMethodOnColorObject(ColorInterface $newColor): ColorInterface
    {
        if (\method_exists($newColor, 'to' . $this->initialType)) {
            $methodName = 'to' . $this->initialType;
            /**
             * Cast to original type passed to the constructor
             * to make consistence between the original color and the new one
             */
            return $newColor->$methodName();
        }

        throw new Exception('Method not found');
    }
}
