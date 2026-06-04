<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Typography;

use ItalyStrap\Tests\Unit\PublicApi\Settings\PresetCommonTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\FontSize;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\Utilities\Fluid;

final class FontSizeTest extends UnitTestCase
{
    use PresetCommonTrait;

    private string $slug = 'base';

    private string $name = 'Font Size';

    private string $size = '16px';

    private function makeInstance(): FontSize
    {
        return new FontSize(
            $this->slug,
            $this->name,
            $this->size,
            null
        );
    }

    public function testItShouldAllowClampWithoutFluid(): void
    {
        $sut = new FontSize(
            'fluid',
            'Fluid',
            'clamp(1rem, 2vw, 1.5rem)'
        );

        $this->assertSame('clamp(1rem, 2vw, 1.5rem)', $sut->toArray()['size']);
    }

    public function testItShouldRejectClampWithFluidConfig(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Fluid typography cannot be applied to a font size that already uses clamp().');

        new FontSize(
            'fluid',
            'Fluid',
            'clamp(1rem, 2vw, 1.5rem)',
            new Fluid('1rem', '1.5rem')
        );
    }
}
