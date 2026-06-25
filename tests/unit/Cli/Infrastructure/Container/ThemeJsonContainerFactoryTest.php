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

        $result = $sut->execute(static function (Pipeline $pipeline): void {
            $pipeline->process([
                ThemeJsonContainerFactoryPaletteConfiguratorFixture::class,
            ]);
        });
        $config = $result->config;

        $this->assertSame('body{color: red;}', $config->get('styles.css'));
        $this->assertCount(12, \iterator_to_array($result->presets->presets()));
        $this->assertSame(
            [
                [
                    'slug' => 'base',
                    'name' => 'Base',
                    'color' => '#ffffff',
                ],
            ],
            $config->get('settings.color.palette')
        );
        $this->assertSame(
            [
                [
                    'slug' => 'small',
                    'name' => 'Small',
                    'size' => '4px',
                ],
            ],
            $config->get('settings.border.radiusSizes')
        );
        $this->assertSame(
            [
                [
                    'slug' => 'square',
                    'name' => 'Square',
                    'ratio' => '1/1',
                ],
            ],
            $config->get('settings.dimensions.aspectRatios')
        );
        $this->assertSame(
            [
                [
                    'slug' => '50',
                    'name' => 'Medium',
                    'size' => '1rem',
                ],
            ],
            $config->get('settings.spacing.spacingSizes')
        );
        $this->assertSame(
            [
                [
                    'slug' => 'base',
                    'name' => 'Block Base',
                    'color' => '#000000',
                ],
            ],
            $config->get('settings.blocks.core/group.color.palette')
        );
        $this->assertSame(
            [
                [
                    'slug' => 'large',
                    'name' => 'Large',
                    'size' => '12px',
                ],
            ],
            $config->get('settings.blocks.core/group.border.radiusSizes')
        );
        $this->assertSame(
            [
                [
                    'slug' => 'content',
                    'name' => 'Content',
                    'size' => '42rem',
                ],
            ],
            $config->get('settings.blocks.core/group.dimensions.dimensionSizes')
        );
        $this->assertSame(
            [
                [
                    'slug' => 'soft',
                    'name' => 'Soft',
                    'shadow' => '0 2px 4px #000000',
                ],
            ],
            $config->get('settings.blocks.core/group.shadow.presets')
        );
        $this->assertSame(
            [
                [
                    'slug' => '40',
                    'name' => 'Small',
                    'size' => '0.75rem',
                ],
            ],
            $config->get('settings.blocks.core/group.spacing.spacingSizes')
        );
        $this->assertSame(
            [
                [
                    'slug' => 'body',
                    'name' => 'Body',
                    'size' => '1rem',
                ],
            ],
            $config->get('settings.blocks.core/group.typography.fontSizes')
        );
        $this->assertSame(
            [
                [
                    'slug' => 'sans',
                    'name' => 'Sans',
                    'fontFamily' => 'Arial, sans-serif',
                ],
            ],
            $config->get('settings.blocks.core/group.typography.fontFamilies')
        );
        $this->assertSame('2rem', $config->get('settings.blocks.core/group.custom.spacing.base'));
    }
}
