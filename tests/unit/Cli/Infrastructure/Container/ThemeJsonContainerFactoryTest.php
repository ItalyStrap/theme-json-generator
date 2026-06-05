<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Cli\Infrastructure\Container;

use ItalyStrap\Tests\Fixtures\ThemeJsonContainerFactoryPaletteConfiguratorFixture;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Container\ThemeJsonContainerFactory;
use ItalyStrap\ThemeJsonGenerator\Pipeline;

final class ThemeJsonContainerFactoryTest extends UnitTestCase
{
    public function testItShouldSerializePresetsAfterEntrypointExecution(): void
    {
        $sut = new ThemeJsonContainerFactory();

        $themeJson = $sut->execute(static function (Pipeline $pipeline): void {
            $pipeline->process([
                ThemeJsonContainerFactoryPaletteConfiguratorFixture::class,
            ]);
        });

        $this->assertSame('body{color: red;}', $themeJson->get('styles.css'));
        $this->assertSame(
            [
                [
                    'slug' => 'base',
                    'name' => 'Base',
                    'color' => '#ffffff',
                ],
            ],
            $themeJson->get('settings.color.palette')
        );
        $this->assertSame(
            [
                [
                    'slug' => 'small',
                    'name' => 'Small',
                    'size' => '4px',
                ],
            ],
            $themeJson->get('settings.border.radiusSizes')
        );
        $this->assertSame(
            [
                [
                    'slug' => 'square',
                    'name' => 'Square',
                    'ratio' => '1/1',
                ],
            ],
            $themeJson->get('settings.dimensions.aspectRatios')
        );
        $this->assertSame(
            [
                [
                    'slug' => '50',
                    'name' => 'Medium',
                    'size' => '1rem',
                ],
            ],
            $themeJson->get('settings.spacing.spacingSizes')
        );
        $this->assertSame(
            [
                [
                    'slug' => 'base',
                    'name' => 'Block Base',
                    'color' => '#000000',
                ],
            ],
            $themeJson->get('settings.blocks.core/group.color.palette')
        );
        $this->assertSame(
            [
                [
                    'slug' => 'large',
                    'name' => 'Large',
                    'size' => '12px',
                ],
            ],
            $themeJson->get('settings.blocks.core/group.border.radiusSizes')
        );
        $this->assertSame(
            [
                [
                    'slug' => 'content',
                    'name' => 'Content',
                    'size' => '42rem',
                ],
            ],
            $themeJson->get('settings.blocks.core/group.dimensions.dimensionSizes')
        );
        $this->assertSame(
            [
                [
                    'slug' => 'soft',
                    'name' => 'Soft',
                    'shadow' => '0 2px 4px #000000',
                ],
            ],
            $themeJson->get('settings.blocks.core/group.shadow.presets')
        );
        $this->assertSame(
            [
                [
                    'slug' => '40',
                    'name' => 'Small',
                    'size' => '0.75rem',
                ],
            ],
            $themeJson->get('settings.blocks.core/group.spacing.spacingSizes')
        );
        $this->assertSame(
            [
                [
                    'slug' => 'body',
                    'name' => 'Body',
                    'size' => '1rem',
                ],
            ],
            $themeJson->get('settings.blocks.core/group.typography.fontSizes')
        );
        $this->assertSame(
            [
                [
                    'slug' => 'sans',
                    'name' => 'Sans',
                    'fontFamily' => 'Arial, sans-serif',
                ],
            ],
            $themeJson->get('settings.blocks.core/group.typography.fontFamilies')
        );
        $this->assertSame('2rem', $themeJson->get('settings.blocks.core/group.custom.spacing.base'));
    }
}
