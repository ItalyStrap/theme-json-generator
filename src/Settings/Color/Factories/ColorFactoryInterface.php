<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Factories;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

interface ColorFactoryInterface
{
    /**
     * @throws \Exception
     */
    public function fromColorInfo(CssColorInterface $colorValue): CssColorInterface;

    /**
     * @throws \Exception
     */
    public function fromColorString(string $color): CssColorInterface;
}
