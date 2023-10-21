<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Handler;

interface ColorFactoryInterface
{
    /**
     * @throws \Exception
     */
    public function fromColorValue(ColorValue $colorValue): ColorValue;

    /**
     * @throws \Exception
     */
    public function fromColorString(string $color): ColorValue;
}
