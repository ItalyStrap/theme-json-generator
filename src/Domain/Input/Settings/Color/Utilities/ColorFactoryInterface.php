<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities;

interface ColorFactoryInterface
{
    /**
     * @throws \Exception
     */
    public function fromColorInfo(ColorInterface $colorValue): ColorInterface;

    /**
     * @throws \Exception
     */
    public function fromColorString(string $color): ColorInterface;
}
