<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Infrastructure\Filesystem;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Application\Config\Blueprint;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Collection;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Palette;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\ColorInfo;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\ColorModifier;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Color;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Typography;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\JsonFileWriter;

class JsonFileWriterIntegrationTest extends UnitTestCase
{
    public const COLOR_HEADING_TEXT = Palette::CATEGORY . '.headingColor';

    public const FONT_SIZE_H1 = 'font-size-h1';

    public const COLOR_GRAY_400 = 'color-gray-400';

    public const FONT_SIZE_H5 = 'font-size-h5';

    private string $theme_json_path;

    private Blueprint $blueprint;

    private $colorIntegration;

    private $typographyIntegration;

    protected function makeInstance(): JsonFileWriter
    {
        $this->theme_json_path = \codecept_output_dir(random_int(0, mt_getrandmax()) . '/theme.json');
        $this->blueprint = new Blueprint();
        $collection = new Collection();

        $bodyText = (new ColorInfo('#000000'))->toHsla();
        $headingText = (new ColorModifier($bodyText))->lighten(20);
        $headingClrPalette = new Palette('headingColor', 'Color for headings', $headingText);

        $collection->add($headingClrPalette);

        $this->colorIntegration = new Color($collection);
        $this->typographyIntegration = new Typography($collection);
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

        $this->blueprint->setBlockStyle('core/site-title', [
            'color' => $this->colorIntegration
                ->text(self::COLOR_HEADING_TEXT),
            'typography' => $this->typographyIntegration
                ->fontSize(self::FONT_SIZE_H1)
                ->fontWeight('600'),
        ]);

        $this->blueprint->setBlockStyle('core/post-title', [ // .wp-block-post-title
            'color' => $this->colorIntegration
                ->text(self::COLOR_HEADING_TEXT),
            'typography' => $this->typographyIntegration
                ->fontSize(self::FONT_SIZE_H1),
            'elements' => [
                'link' => [ // .wp-block-post-title a
                    'color' => $this->colorIntegration
                        ->text('inherit')
                        ->background('transparent'),
                ],
            ],
        ]);

        $this->blueprint->setBlockStyle('core/query-title', [
            'color' => $this->colorIntegration
                ->text(self::COLOR_GRAY_400),
            'typography' => $this->typographyIntegration
                ->fontSize(self::FONT_SIZE_H5)
                ->fontWeight('700'),
        ]);

        $sut->write($this->blueprint);

        $this->assertJsonStringEqualsJsonFile($this->theme_json_path, $expected, '');

        \unlink($this->theme_json_path);
        \rmdir(\dirname($this->theme_json_path));
    }
}
