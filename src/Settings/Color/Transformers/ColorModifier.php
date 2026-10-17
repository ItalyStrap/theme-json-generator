<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Transformers;

use Exception;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Factories\ColorFactory;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Factories\ColorFactoryInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final readonly class ColorModifier implements ColorModifierInterface
{
    private ColorFactoryInterface $color_factory;

    private string $initialType;

    /**
     * @throws Exception
     */
    public function __construct(private CssColorInterface $color, ?ColorFactoryInterface $factory = null)
    {
        $this->color_factory = $factory ?? new ColorFactory();
        $this->initialType = $this->color->type();
    }

    public function color(): CssColorInterface
    {
        return $this->color;
    }

    public function tint(float $weight = 0): CssColorInterface
    {
        return $this->mixWith('rgb(255,255,255)', $weight);
    }

    public function shade(float $weight = 0): CssColorInterface
    {
        return $this->mixWith('rgb(0,0,0)', $weight);
    }

    public function tone(float $weight = 0): CssColorInterface
    {
        return $this->mixWith('rgb(128,128,128)', $weight);
    }

    public function opacity(?float $alpha = null): CssColorInterface
    {
        if ($alpha === null) {
            return $this->color;
        }

        return $this->callMethodOnColorObjectWithAlpha($alpha);
    }

    public function darken(float $amount = 0): CssColorInterface
    {
        return $this->createNewColorWithChangedLightnessOrOpacity(-$amount);
    }

    public function lighten(float $amount = 0): CssColorInterface
    {
        return $this->createNewColorWithChangedLightnessOrOpacity($amount);
    }

    public function saturate(float $amount = 0): CssColorInterface
    {
        return $this->createNewColorWithChangedSaturation($amount);
    }

    public function contrast(float $amount = 0): CssColorInterface
    {
        return $this->createNewColorWithChangedContrast($amount);
    }

    public function complementary(): CssColorInterface
    {
        if ($this->color->hue() === 0 && $this->color->saturation() === 0) {
            return $this->color;
        }

        return $this->hueRotate(180);
    }

    public function invert(): CssColorInterface
    {
        return $this->createNewColorFrom(
            (string) $this->color->hue(),
            (string) $this->color->saturation(),
            (string) $this->sanitizeFromFloatToInteger(100 - $this->color->lightness()),
            (string) $this->color->alpha()
        );
    }

    public function hueRotate(int $amount = 0): CssColorInterface
    {
        return $this->createNewColorFrom(
            (string) $this->normalizeHue($this->color->hue() + $amount),
            (string) $this->color->saturation(),
            (string) $this->color->lightness(),
            (string) $this->color->alpha()
        );
    }

    private function createNewColorWithChangedLightnessOrOpacity(float $amount, ?float $alpha = null): CssColorInterface
    {
        return $this->createNewColorFrom(
            (string) $this->color->hue(),
            (string) $this->color->saturation(),
            (string) $this->sanitizeFromFloatToInteger($this->color->lightness() + $amount),
            (string) ($alpha ?? $this->color->alpha())
        );
    }

    private function createNewColorWithChangedSaturation(float $amount): CssColorInterface
    {
        return $this->createNewColorFrom(
            (string) $this->color->hue(),
            (string) $this->sanitizeFromFloatToInteger($this->color->saturation() + $amount),
            (string) $this->color->lightness(),
            (string) $this->color->alpha()
        );
    }

    private function createNewColorWithChangedContrast(float $amount): CssColorInterface
    {
        $lightness = $this->color->lightness();
        $direction = $lightness < 50 ? -1 : 1;

        return $this->createNewColorFrom(
            (string) $this->color->hue(),
            (string) $this->color->saturation(),
            (string) $this->sanitizeFromFloatToInteger($lightness + ($amount * $direction)),
            (string) $this->color->alpha()
        );
    }

    private function createNewColorFrom(
        string $hue,
        string $saturation,
        string $lightness,
        string $alpha
    ): CssColorInterface {
        $newColor = $this->color_factory->fromColorString(\sprintf(
            'hsla(%s, %s%%, %s%%, %s)',
            $hue,
            $saturation,
            $lightness,
            $this->normalizeAlpha($alpha)
        ));

        return $this->callMethodOnColorObject($newColor);
    }

    /**
     * @todo Is it a good idea to make it public?
     *       Evaluate possible side effects.
     */
    private function mixWith(string $colorString, float $weight = 0): CssColorInterface
    {
        /**
         * I need to cast to RGB or RGBA because the mixRgb method
         * uses calculation over `ColorInfo::red()` and `ColorInfo::green()` and `ColorInfo::blue()`
         * as a number (0 to 255) and not as string (ff, or 00 or whatever),
         * So the cast here is necessary
         */
        $result = $this->mixRgb(
            $this->color_factory->fromColorString($colorString)->toRgba(),
            $this->color->toRgba(),
            $this->normalizePercentage($weight)
        );

        $newColor = $this->color_factory->fromColorString(\sprintf(
            'rgba(%s, %s)',
            \implode(',', $result),
            $this->normalizeAlpha($this->color->alpha())
        ));

        return $this->callMethodOnColorObject($newColor);
    }

    /**
     * @return array<array-key, int|float>
     */
    private function mixRgb(CssColorInterface $color1, CssColorInterface $color2, float $weight = 0.5): array
    {
        $f = static fn (int $x): float => $weight * $x;
        $g = static fn (int $x): float => (1 - $weight) * $x;
        $h = static fn (float $x, float $y): float => \round($x + $y);

        return \array_map(
            $h,
            \array_map($f, [ (int)$color1->red(), (int)$color1->green(), (int)$color1->blue() ]),
            \array_map($g, [ (int)$color2->red(), (int)$color2->green(), (int)$color2->blue() ])
        );
    }

    private function sanitizeFromFloatToInteger(float $value): int
    {
        return $value > 100
            ? 100
            : ($value < 0 ? 0 : (int) \round($value));
    }

    private function normalizePercentage(float $percentage): float
    {
        return \max(0, \min(100, $percentage)) / 100;
    }

    private function normalizeAlpha(string|float $alpha): float
    {
        if (\is_float($alpha)) {
            return \round($alpha, 2);
        }

        if (\preg_match('/^[\da-f]{2}$/i', $alpha) === 1) {
            return \round(\hexdec($alpha) / 255, 2);
        }

        return \round((float) $alpha, 2);
    }

    private function normalizeHue(int $hue): int
    {
        return (($hue % 360) + 360) % 360;
    }

    private function callMethodOnColorObjectWithAlpha(float $alpha): CssColorInterface
    {
        if (\in_array($this->initialType, ['Hex', 'Rgb', 'Rgba'], true)) {
            return $this->color->toRgba($alpha);
        }

        if (\in_array($this->initialType, ['Hsl', 'Hsla'], true)) {
            return $this->color->toHsla($alpha);
        }

        return $this->createNewColorWithChangedLightnessOrOpacity(0, $alpha);
    }

    /**
     * @param CssColorInterface $newColor
     * @return CssColorInterface
     * @throws Exception
     */
    private function callMethodOnColorObject(CssColorInterface $newColor): CssColorInterface
    {
        if (\method_exists($newColor, 'to' . $this->initialType)) {
            $methodName = 'to' . $this->initialType;
            /**
             * Cast to the original type passed to the constructor
             * to make consistence between the original color and the new one
             */
            return $newColor->$methodName();
        }

        throw new Exception('Method not found');
    }
}
