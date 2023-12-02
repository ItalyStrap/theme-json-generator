<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities;

use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Palette;

class ShadesGeneratorExperimental
{
    public const MIN = 100;
    public const MAX = 1000;
    public const INCREMENT_BY = 100;

    private ColorInfoInterface $color;
    private string $slug;
    private int $min;
    private int $max;
    private int $increment_by;

    public static function fromPalette(
        Palette $palette,
        int $min = self::MIN,
        int $max = self::MAX,
        int $increment_by = self::INCREMENT_BY
    ): ShadesGeneratorExperimental {
        return new self(
            $palette->color(),
            $palette->slug(),
            $min,
            $max,
            $increment_by
        );
    }

    public static function fromColorInfo(
        ColorInfoInterface $color,
        string $slug,
        int $min = self::MIN,
        int $max = self::MAX,
        int $increment_by = self::INCREMENT_BY
    ): ShadesGeneratorExperimental {
        return new self(
            $color,
            $slug,
            $min,
            $max,
            $increment_by
        );
    }

    public function __construct(
        ColorInfoInterface $color,
        string $slug,
        int $min = self::MIN,
        int $max = self::MAX,
        int $increment_by = self::INCREMENT_BY
    ) {
        $this->color = $color;
        $this->slug = $slug;
        $this->min = $min;
        $this->max = $max;
        $this->increment_by = $increment_by;
    }

    public function toColors(): array
    {
        $colors = [];
        for ($i = $this->min; $i < $this->max; $i += $this->increment_by) {
            $colors[$i] = $this->color->isDark()
                ? (new ColorModifier($this->color))->lighten($i / 10)
                : (new ColorModifier($this->color))->darken($i / 10);
        }

        return $colors;
    }

    public function toPalettes(): array
    {
        $palettes = [];
        foreach ($this->toColors() as $key => $color) {
            $palettes[$key] = new Palette(
                \sprintf('%s-%d', $this->slug, $key),
                \sprintf("Shade of %s by %s%%", \ucfirst($this->slug), $key / 10),
                $color
            );
        }

        return $palettes;
    }

    /**
     * This functionality create an array of shades of a given color
     * The created shades are from 10% to 100% of the given color
     * If the color is dark, the shades will be lightened
     * If the color is light, the shades will be darkened
     *
     * This method create an array of Palette of shades of a color
     * If the color generated is #000000 or #ffffff it will be skipped,
     * and you will get only the shades of the color without duplicates values likes many #000000 or #ffffff
     *
     * @throws \Exception
     */
    public function toArray(): array
    {

        $colors = [];
        for ($i = $this->min; $i < $this->max; $i += $this->increment_by) {
            $colors[$i] = new Palette(
                \sprintf('%s-%d', $this->slug, $i),
                \sprintf("Shade of %s by %s%%", \ucfirst($this->slug), $i / 10),
                $this->color->isDark()
                    ? (new ColorModifier($this->color))->lighten($i / 10)
                    : (new ColorModifier($this->color))->darken($i / 10)
            );

            $r = $colors[$i]->color()->red();
            $g = $colors[$i]->color()->green();
            $b = $colors[$i]->color()->blue();

//          var_dump($r, $g, $b);

//          if ( $r === 0 && $g === 0 && $b === 0 ) {
//              // This removes the current element from the array
//              unset($colors[$i]);
//              // This ensures that the loop will stop
//              $i = $this->max;
//          }

            $colorToCheck = (string)$colors[$i]->color()->toHex();
            if (
                $colorToCheck === '#000000'
                || $colorToCheck === '#ffffff'
            ) {
                // This removes the current element from the array
                unset($colors[$i]);
                // This ensures that the loop will stop
                $i = $this->max;
            }
        }

        return $colors;
    }
}
