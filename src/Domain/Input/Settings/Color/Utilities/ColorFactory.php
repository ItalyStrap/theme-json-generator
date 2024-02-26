<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities;

/**
 * @psalm-api
 */
final class ColorFactory implements ColorFactoryInterface
{
    /**
     * @throws \Exception
     */
    public function fromColorInfo(ColorInterface $colorValue): ColorInterface
    {
        return new Color((string) $colorValue);
    }

    /**
     * @throws \Exception
     */
    public function fromColorString(string $color): ColorInterface
    {
        return new Color($color);
    }
}
