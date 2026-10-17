<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color;

use ItalyStrap\Tests\Unit\PublicApi\Settings\PresetCommonTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Duotone;

final class DuotoneTest extends UnitTestCase
{
    use PresetCommonTrait;

    private string $slug = 'base';

    private string $name = 'Base';

    private function makeInstance(): Duotone
    {
        $this->cssColor
            ->__toString()
            ->willReturn('rgba(255, 255, 255, 1)');

        $this->cssColor
            ->toRgba()
            ->willReturn($this->cssColor->reveal());

        $this->palette
            ->color()
            ->willReturn($this->cssColor->reveal());

        return new Duotone(
            $this->slug,
            $this->name,
            ...[
                $this->makePalette(),
                $this->makePalette(),
            ]
        );
    }
}
