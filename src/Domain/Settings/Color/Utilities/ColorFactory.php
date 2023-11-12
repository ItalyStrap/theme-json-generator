<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities;

/**
 * @psalm-api
 */
final class ColorFactory implements ColorFactoryInterface
{
    /**
     * @throws \Exception
     */
    public function fromColorInfo(ColorInfoInterface $colorValue): ColorInfoInterface
    {
        return new ColorInfo((string) $colorValue);
    }

    /**
     * @throws \Exception
     */
    public function fromColorString(string $color): ColorInfoInterface
    {
        return new ColorInfo($color);
    }
}
