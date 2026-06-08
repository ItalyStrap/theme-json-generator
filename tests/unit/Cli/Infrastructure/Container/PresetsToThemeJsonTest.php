<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Cli\Infrastructure\Container;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Container\PresetsToThemeJson;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Palette;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\Color;
use ItalyStrap\ThemeJsonGenerator\Settings\Custom\Custom;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\FontSize;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class PresetsToThemeJsonTest extends UnitTestCase
{
    public function testItShouldWritePresetsToThemeJson(): void
    {
        $presets = new Presets();
        $presets
            ->add(new Palette('base', 'Base', new Color('#ffffff')))
            ->add(new FontSize('base', 'Base', '1rem'))
            ->add(new FontSize('large', 'Large', 'calc( {{fontSize.base}} * 2 )'))
            ->add(new Custom('spacing.base', '{{fontSize.base}}'))
            ->addToBlock('core/group', new Palette('base', 'Block Base', new Color('#000000')));

        $themeJson = new ThemeJson(new Config(), $presets);

        (new PresetsToThemeJson())($themeJson, $presets);

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
                    'slug' => 'base',
                    'name' => 'Base',
                    'size' => '1rem',
                ],
                [
                    'slug' => 'large',
                    'name' => 'Large',
                    'size' => 'calc( var(--wp--preset--font-size--base) * 2 )',
                ],
            ],
            $themeJson->get('settings.typography.fontSizes')
        );
        $this->assertSame(
            'var(--wp--preset--font-size--base)',
            $themeJson->get('settings.custom.spacing.base')
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
    }
}
