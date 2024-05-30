<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

use ItalyStrap\ThemeJsonGenerator\Application\Config\Blueprint;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\SectionNames;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Duotone;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Gradient;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Palette;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Shadow;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\BoxShadow;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\Color;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\ColorModifier;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\LinearGradient;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Custom\CustomToPresets;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography\FontFamily;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography\FontSize;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles;
use Psr\Container\ContainerInterface;

return static function (Blueprint $blueprint, Presets $presets, ContainerInterface $container): void {
    $baseClr = (new Color('#3986E0'))->toHsla();
    $bodyText = (new Color('#000000'))->toHsla();
    $headingText = (new ColorModifier($bodyText))->lighten(20);

    $baseClrPalette = new Palette('base', 'Brand base color', $baseClr);
    $bodyClrPalette = new Palette('bodyColor', 'Color for text', $bodyText);
    $headingClrPalette = new Palette('headingColor', 'Color for headings', $headingText);

    $presets
        ->add($baseClrPalette)
        ->add($bodyClrPalette)
        ->add($headingClrPalette);

    // Gradient
    $presets
        ->add(new Gradient(
            'base-to-white',
            'Base to white',
            (new LinearGradient())
                ->direction('135deg')
                ->colorStop($presets->get('color.base'))
                ->colorStop($presets->get('color.bodyColor'))
        ));

    // Duotone
    $presets
        ->add(new Duotone(
            'base-to-bodyColor',
            'Base to Body Color',
            $presets->get('color.base'),
            $presets->get('color.bodyColor')
        ));

    $presets
        ->add(new Shadow(
            'base',
            'Base shadow',
            (new BoxShadow())
                ->offsetX('0')
                ->offsetY('4px')
                ->blur('8px')
                ->spread('0')
                ->color($presets->get('color.bodyColor')),
            (new BoxShadow())
                ->inset()
                ->offsetX('0')
                ->offsetY('4px')
                ->blur('8px')
                ->spread('0')
                ->color('#fff')
        ));

    // Font size
    $presets
        ->add(new FontSize('base', 'Base font size 16px', 'clamp(1rem, 2vw, 1.5rem)'))
        ->add(new FontSize('h1', 'Used in H1 titles', 'calc( {{fontSize.base}} * 2.8125)'));
    // ... more font sizes

    // Font Family
    $presets
        ->add(new FontFamily(
            'base',
            'Default font family',
            // phpcs:disable
            'system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"'
            // phpcs:enable
        ));

    $collectionAdapter = new CustomToPresets([
        'contentSize'   => 'clamp(16rem, 60vw, 60rem)',
        'wideSize'      => 'clamp(16rem, 85vw, 70rem)',
        'baseFontSize'  => "1rem",
        'spacer'        => [
            'base'  => '1rem',
            // ... more spacers
        ],
    ]);

    $presets
        ->addMultiple(
            $collectionAdapter->toArray()
        );

    $blueprint->merge([
        SectionNames::SCHEMA => 'https://schemas.wp.org/trunk/theme.json',
        SectionNames::VERSION => 2,
        SectionNames::TITLE => 'Experimental Theme',
        SectionNames::DESCRIPTION => 'Experimental Theme',
        SectionNames::SETTINGS => [
            'color' => [
                'custom'    => true,
                'link'      => true,
            ],
            'typography' => [
                'customFontSize'    => true,
            ],
            'spacing' => [
                'blockGap'  => true,
                'units' => [ '%', 'px', 'em', 'rem', 'vh', 'vw' ]
            ],
            'layout' => [
                'contentSize' => $presets->get('custom.contentSize')->var(),
                'wideSize' => $presets->get('custom.wideSize')->var(),
            ],
        ],
        SectionNames::STYLES => [
            'color' => (new Styles\Color())
                ->background('var(--wp--preset--color--body-bg)')
                ->text('var(--wp--preset--color--body-color)'),
            'typography' => (new Styles\Typography($presets))
                ->fontSize(FontSize::TYPE . '.base')
                ->fontFamily(FontFamily::TYPE . '.base'),
            'elements' => [
                'link' => [ // .wp-block-file a
                    'color' => $container->get(Styles\Color::class)
                        ->text(Palette::TYPE . '.base')
                        ->background('transparent'),
                ],
            ],
            'blocks' => [
                'core/paragraph' => [
                    'color' => (new Styles\Color())
                        ->text('var(--wp--preset--color--body-color)'),
                    'typography' => (new Styles\Typography())
                        ->fontSize('var(--wp--preset--font-size--base)')
                        ->fontFamily('var(--wp--preset--font-family--base)'),
                ],
            ],
        ],
    ]);
};
