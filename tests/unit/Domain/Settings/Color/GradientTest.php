<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Settings\Color;

use ItalyStrap\Tests\Unit\Domain\Settings\CommonTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Gradient;

class GradientTest extends UnitTestCase
{
    use CommonTrait;

    private string $slug = 'base';

    private string $name = 'Base';

    private function makeInstance(): Gradient
    {
        return new Gradient(
            $this->slug,
            $this->name,
            $this->makeGradient()
        );
    }
}
