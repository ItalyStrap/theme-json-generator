<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Cli\Infrastructure\Filesystem;

use ItalyStrap\Config\Config;
use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Filesystem\JsonFileWriter;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Color;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Transformers\ColorModifier;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class JsonFileWriterIntegrationTest extends UnitTestCase
{
    public const COLOR_HEADING_TEXT = Color::TYPE . '.headingColor';

    public const FONT_SIZE_H1 = 'font-size-h1';

    public const COLOR_GRAY_400 = 'color-gray-400';

    public const FONT_SIZE_H5 = 'font-size-h5';

    private string $theme_json_path;

    private ThemeJson $themeJson;

    private ConfigInterface $originalConfig;

    protected function makeInstance(): JsonFileWriter
    {
        $this->theme_json_path = \codecept_output_dir(random_int(0, mt_getrandmax()) . '/theme.json');
        \mkdir(\dirname($this->theme_json_path), 0777, true);

        $collection = new Presets();
        $this->originalConfig = new Config();
        $this->themeJson = new ThemeJson(
            $this->originalConfig,
            $collection,
        );

        $bodyText = (new CssColor('#000000'))->toHsla();
        $headingText = (new ColorModifier($bodyText))->lighten(20);
        $headingClrPalette = new Color('headingColor', 'Color for headings', $headingText);

        $collection->add($headingClrPalette);

        return new JsonFileWriter($this->theme_json_path);
    }

    public function testItShouldReturnValidJson(): void
    {
        $sut = $this->makeInstance();
        $expected = '{
      "styles": {
          "blocks": {
              "core/site-title": {
                  "color": {
                      "text": "var(--wp--preset--color--heading-color)"
                  },
                  "typography": {
                      "fontSize": "font-size-h1",
                      "fontWeight": "600"
                  }
              },
              "core/post-title": {
                  "color": {
                      "text": "var(--wp--preset--color--heading-color)"
                  },
                  "typography": {
                      "fontSize": "font-size-h1"
                  },
                  "elements": {
                      "link": {
                          "color": {
                              "text": "inherit",
                              "background": "transparent"
                          }
                      }
                  }
              },
              "core/query-title": {
                  "color": {
                      "text": "color-gray-400"
                  },
                  "typography": {
                      "fontSize": "font-size-h5",
                      "fontWeight": "700"
                  }
              }
          }
      }
  }';

        $this->themeJson->styles()->blocks('core/site-title')
            ->color()
            ->text(self::COLOR_HEADING_TEXT);

        $this->themeJson->styles()->blocks('core/site-title')
            ->typography()
            ->fontSize(self::FONT_SIZE_H1)
            ->fontWeight('600');

        $this->themeJson->styles()->blocks('core/post-title')
            ->color()
            ->text(self::COLOR_HEADING_TEXT);

        $this->themeJson->styles()->blocks('core/post-title')
            ->typography()
            ->fontSize(self::FONT_SIZE_H1);

        $this->themeJson->styles()->blocks('core/post-title')
            ->elements('link')
            ->color()
            ->text('inherit')
            ->background('transparent');

        $this->themeJson->styles()->blocks('core/query-title')
            ->color()
            ->text(self::COLOR_GRAY_400);

        $this->themeJson->styles()->blocks('core/query-title')
            ->typography()
            ->fontSize(self::FONT_SIZE_H5)
            ->fontWeight('700');

        $sut->write($this->originalConfig);

        $this->assertJsonStringEqualsJsonFile($this->theme_json_path, $expected, '');

        \unlink($this->theme_json_path);
        \rmdir(\dirname($this->theme_json_path));
    }
}
