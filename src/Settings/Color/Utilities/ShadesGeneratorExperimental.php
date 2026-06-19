<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Palette;
use Traversable;

final readonly class ShadesGeneratorExperimental implements \IteratorAggregate
{
    /**
     * @var int
     */
    public const MIN = 100;

    /**
     * @var int
     */
    public const MAX = 1000;

    /**
     * @var int
     */
    public const INCREMENT_BY = 100;

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
        ColorInterface $color,
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
        private ColorInterface $color,
        private string $slug,
        private int $min = self::MIN,
        private int $max = self::MAX,
        private int $increment_by = self::INCREMENT_BY
    ) {
        if ($this->increment_by <= 0) {
            throw new \InvalidArgumentException('Shade increment must be greater than zero.');
        }

        if ($this->min < 0) {
            throw new \InvalidArgumentException('Minimum shade must be zero or greater.');
        }

        if ($this->max < $this->min) {
            throw new \InvalidArgumentException(
                'Maximum shade must be greater than or equal to minimum shade.'
            );
        }
    }

    public function toColors(): array
    {
        $colors = [];
        foreach ($this->shadeIndexes() as $index) {
            $colors[$index] = $this->shadeAt($index);
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
     * This functionality creates an array of shades of a given color
     * The created shades are from 10% to 100% of the given color
     * If the color is dark, the shades will be lightened
     * If the color is light, the shades will be darkened
     *
     * This method creates an array of Palette of shades of a color
     * If the color generated is #000000 or #ffffff it will be skipped,
     * and you will get only the shades of the color without duplicates values like many #000000 or #ffffff
     *
     * @throws \Exception
     */
    public function toArray(): array
    {
        $colors = [];
        foreach ($this->shadeIndexes() as $index) {
            $colors[$index] = new Palette(
                \sprintf('%s-%d', $this->slug, $index),
                \sprintf("Shade of %s by %s%%", \ucfirst($this->slug), $index / 10),
                $this->shadeAt($index)
            );

            $colorToCheck = (string)$colors[$index]->color()->toHex();
            if (
                $colorToCheck === '#000000'
                || $colorToCheck === '#ffffff'
            ) {
                unset($colors[$index]);
                break;
            }
        }

        return $colors;
    }

    /**
     * @return \Generator<int>
     */
    private function shadeIndexes(): \Generator
    {
        for ($index = $this->min; $index <= $this->max; $index += $this->increment_by) {
            yield $index;
        }
    }

    private function shadeAt(int $index): ColorInterface
    {
        $modifier = new ColorModifier($this->color);
        $amount = $index / 10;

        return $this->color->isDark()
            ? $modifier->lighten($amount)
            : $modifier->darken($amount);
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->toArray());
    }
}
