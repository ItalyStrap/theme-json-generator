<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Fixtures;

use ItalyStrap\ThemeJsonGenerator\ConfiguratorInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Color;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Shadow\Utilities\BoxShadow;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final readonly class ThemeJsonContainerFactoryPaletteConfiguratorFixture implements ConfiguratorInterface
{
    public function __construct(private PresetsInterface $presets)
    {
    }

    public function __invoke(ThemeJson $themeJson): void
    {
        $this->presets->add(new Color('base', 'Base', new CssColor('#ffffff')));
        $themeJson->settings()->border()->addRadiusSize('small', 'Small', '4px');
        $themeJson->settings()->dimensions()->addAspectRatio('square', 'Square', '1/1');
        $themeJson->settings()->spacing()->addSpacingSize('50', 'Medium', '1rem');
        $blockSettings = $themeJson->settings()->blocks('core/group');
        $blockSettings->color()->addColors([
            new Color('base', 'Block Base', new CssColor('#000000')),
        ]);
        $blockSettings->border()->addRadiusSize('large', 'Large', '12px');
        $blockSettings->dimensions()->addDimensionSize('content', 'Content', '42rem');
        $blockSettings->shadow()->addShadow(
            'soft',
            'Soft',
            (new BoxShadow())->offsetX('0')->offsetY('2px')->blur('4px')->color('#000000')
        );
        $blockSettings->spacing()->addSpacingSize('40', 'Small', '0.75rem');
        $blockSettings->typography()->addFontSize('body', 'Body', '1rem');
        $blockSettings->typography()->addFontFamily('sans', 'Sans', 'Arial, sans-serif');
        $blockSettings->custom()->addMultiple([
            'spacing' => [
                'base' => '2rem',
            ],
        ]);
        $themeJson->styles()->css('body{color: red;}');
    }
}
