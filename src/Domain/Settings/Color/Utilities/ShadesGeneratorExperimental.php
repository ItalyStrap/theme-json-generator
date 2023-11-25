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

    /**
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
                    ? (new ColorModifier($this->color))->lighten($i)
                    : (new ColorModifier($this->color))->darken($i)
            );
        }

        return $colors;
    }
}
