<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities;

/**
 * @psalm-api
 */
interface ColorFactoryInterface
{
    /**
     * @throws \Exception
     */
    public function fromColorInfo(ColorInfoInterface $colorValue): ColorInfoInterface;

    /**
     * @throws \Exception
     */
    public function fromColorString(string $color): ColorInfoInterface;
}
