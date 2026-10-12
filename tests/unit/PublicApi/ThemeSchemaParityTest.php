<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Container\PresetsToThemeJson;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;
use JsonSchema\Validator;

final class ThemeSchemaParityTest extends UnitTestCase
{
    public function testItShouldSerializeTheMissingApiFeaturesAsSchemaValidThemeJson(): void
    {
        $config = new Config();
        $presets = new Presets();
        $themeJson = new ThemeJson($config, $presets);
        $themeJson->version(3);
        $themeJson->settings()->blockVisibility()->disableEditing();
        $themeJson->settings()->viewport()->mobile('480px')->tablet('48.875rem');
        $themeJson->settings()->background()->backgroundClip('text', 'border-box');
        $themeJson->settings()->typography()->enableTextShadow()->disableDefaultTextShadowPresets()
            ->addTextShadow('soft', 'Soft', '0 2px 4px #000000');
        $themeJson->settings()->blocks('core/cover')->background()->disableBackgroundClip();
        $themeJson->settings()->blocks('core/heading')->typography()
            ->disableTextShadow()->enableDefaultTextShadowPresets()
            ->addTextShadow('heading', 'Heading', '1px 1px #333333');
        $themeJson->styles()->background()->backgroundClip('text');
        $themeJson->styles()->typography()->textShadow('textShadow.soft');
        $themeJson->styles()->blocks('core/heading')->background()->backgroundClip('padding-box');
        $themeJson->styles()->blocks('core/heading')->typography()->textShadow('1px 1px #333333');
        (new PresetsToThemeJson())($config, $presets);

        $this->assertSame('padding-box', $themeJson->get('styles.blocks.core/heading.background.backgroundClip'));
        $this->assertSame('1px 1px #333333', $themeJson->get('styles.blocks.core/heading.typography.textShadow'));
        $data = \json_decode(\json_encode($config, \JSON_THROW_ON_ERROR), false, 512, \JSON_THROW_ON_ERROR);
        $validator = new Validator();
        $validator->validate($data, (object) ['$ref' => 'file://' . \codecept_root_dir('theme.schema.json')]);

        $this->assertTrue($validator->isValid(), \json_encode($validator->getErrors(), \JSON_THROW_ON_ERROR));
    }
}
