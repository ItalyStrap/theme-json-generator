<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Settings\Color;

use ItalyStrap\Tests\Unit\Domain\Input\Settings\PresetCommonTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Shadow;

class ShadowTest extends UnitTestCase
{
    use PresetCommonTrait;

    private string $slug = 'base';

    private string $name = 'Base';

    private function makeInstance(): Shadow
    {
        return new Shadow(
            $this->slug,
            $this->name,
            $this->makeBoxShadow()
        );
    }
}
