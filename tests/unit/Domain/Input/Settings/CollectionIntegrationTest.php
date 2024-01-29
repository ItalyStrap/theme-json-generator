<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Settings;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Collection;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Palette;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\Color;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\ShadesGeneratorExperimental;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Custom\CollectionAdapter;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography\FontSize;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Typography;

class CollectionIntegrationTest extends UnitTestCase
{
    private function makeInstance(): Collection
    {
        return new Collection();
    }

    private function buildInstance(): Collection
    {
        $sut = $this->makeInstance();
        $sut->add(new FontSize('base', 'Base font size 16px', 'clamp(1.125rem, 2vw, 1.5rem)'))
            ->add(new FontSize('h1', 'Used in H1 titles', 'calc( {{fontSize.base}} * 2.8125)'))
            ->add(new FontSize('h2', 'Used in H2 titles', 'calc( {{fontSize.base}} * 2.1875)'));

        $body_text = (new Color('#000000'))->toHsla();
        $bodyClrPalette = new Palette('bodyColor', 'Color for text', $body_text);

        $sut->add($bodyClrPalette);

//        $sut->addMultiple(ShadesGeneratorExperimental::fromColorInfo($body_text, 'bodyColor')->toArray());
        $sut->addMultiple(ShadesGeneratorExperimental::fromPalette($bodyClrPalette)->toArray());

        $sut->addMultiple((new CollectionAdapter([
            'contentSize' => 'clamp(16rem, 60vw, 60rem)',
            'wideSize' => 'clamp(16rem, 85vw, 70rem)',
            'baseFontSize' => "{{fontSize.base}}",
        ]))->toArray());

        return $sut;
    }

    public static function placeholdersProvider(): iterable
    {
        yield 'simple' => [
            'string' => '{{fontSize.base}}',
            'expected' => 'var(--wp--preset--font-size--base)',
        ];

        yield 'simple with calc' => [
            'string' => 'calc( {{fontSize.base}} * 2 )',
            'expected' => 'calc( var(--wp--preset--font-size--base) * 2 )',
        ];

        yield 'simple from custom' => [
            'string' => '{{custom.contentSize}}',
            'expected' => 'var(--wp--custom--content-size)',
        ];

        yield 'from css' => [
            'string' => <<<'EOF'
.has-text-color {
    font-size: {{fontSize.base}};
})
.wp-block-paragraph.has-text-color {
    font-size: {{fontSize.h1}};
}
EOF
            ,
            'expected' => <<<'EOF'
.has-text-color {
    font-size: var(--wp--preset--font-size--base);
})
.wp-block-paragraph.has-text-color {
    font-size: var(--wp--preset--font-size--h-1);
}
EOF
            ,
        ];
    }

    /**
     * @dataProvider placeholdersProvider
     */
    public function testChangeMultiplePlaceHolder(
        string $string,
        string $expected
    ): void {
        $sut = $this->buildInstance();

        $value = $sut->parse($string);

        $this->assertSame($expected, $value);
    }

    public function testItShouldReplaceStringPlaceholder(): void
    {
        $sut = $this->makeInstance();
        $sut->add(new FontSize('base', 'Base font size 16px', 'clamp(1.125rem, 2vw, 1.5rem)'))
            ->add(new FontSize('h1', 'Used in H1 titles', 'calc( {{fontSize.base}} * 2.8125)'))
            ->add(new FontSize('h2', 'Used in H2 titles', 'calc( {{fontSize.base}} * 2.1875)'));

//        $this->assertSame('base', $sut->get('fontSize.base')->slug());
//        $this->assertSame('h1', $sut->get('fontSize.h1')->slug());
//        $this->assertSame('h2', $sut->get('fontSize.h2')->slug());
//
//        $this->assertSame('var(--wp--preset--font-size--base)', $sut->get('fontSize.base')->var());
//        $this->assertSame('var(--wp--preset--font-size--h-1)', $sut->get('fontSize.h1')->var());
//        $this->assertSame('var(--wp--preset--font-size--h-2)', $sut->get('fontSize.h2')->var());

        $fontSizesCollection = $sut->toArrayByCategory(FontSize::CATEGORY);
        $this->assertSame(
            [
                [
                    'slug' => 'base',
                    'name' => 'Base font size 16px',
                    'size' => 'clamp(1.125rem, 2vw, 1.5rem)',
                ],
                [
                    'slug' => 'h1',
                    'name' => 'Used in H1 titles',
                    'size' => 'calc( var(--wp--preset--font-size--base) * 2.8125)',
                ],
                [
                    'slug' => 'h2',
                    'name' => 'Used in H2 titles',
                    'size' => 'calc( var(--wp--preset--font-size--base) * 2.1875)',
                ],
            ],
            $fontSizesCollection
        );

        $sut->addMultiple((new CollectionAdapter([
            'contentSize' => 'clamp(16rem, 60vw, 60rem)',
            'wideSize' => 'clamp(16rem, 85vw, 70rem)',
            'baseFontSize' => "{{fontSize.base}}",
            'spacer' => [
                'base' => '1rem',
                'v' => 'calc( {{spacer.base}} * 4 )',
                'h' => 'calc( {{spacer.base}} * 4 )',
                's' => 'calc( {{spacer.base}} / 1.5 )',
                'm' => 'calc( {{spacer.base}} * 2 )',
                'l' => 'calc( {{spacer.base}} * 3 )',
                'xl' => 'calc( {{spacer.base}} * 4 )',
            ],
            'grandParentField' => [
                'parentField' => [
                    'childField' => 'calc( {{spacer.base}} * 4 )',
                ],
            ],
        ]))->toArray());

        $customCollection = $sut->toArrayByCategory('custom');
        $this->assertSame(
            [
                'contentSize' => 'clamp(16rem, 60vw, 60rem)',
                'wideSize' => 'clamp(16rem, 85vw, 70rem)',
                'baseFontSize' => 'var(--wp--preset--font-size--base)',
                'spacer' => [
                    'base' => '1rem',
                    'v' => 'calc( var(--wp--custom--spacer--base) * 4 )',
                    'h' => 'calc( var(--wp--custom--spacer--base) * 4 )',
                    's' => 'calc( var(--wp--custom--spacer--base) / 1.5 )',
                    'm' => 'calc( var(--wp--custom--spacer--base) * 2 )',
                    'l' => 'calc( var(--wp--custom--spacer--base) * 3 )',
                    'xl' => 'calc( var(--wp--custom--spacer--base) * 4 )',
                ],
                'grandParentField' => [
                    'parentField' => [
                        'childField' => 'calc( var(--wp--custom--spacer--base) * 4 )',
                        ],
                    ],
            ],
            $customCollection
        );

//        codecept_debug($sut->get('custom.grandParentField.parentField.childField')->var());

        $typo = (new Typography($sut))
            ->fontSize('fontSize.h1')
            ->toArray();

        $this->assertSame(
            [
                'fontSize' => 'var(--wp--preset--font-size--h-1)',
            ],
            $typo
        );
    }

    public function testItHasShadesValueFromGenerator(): void
    {
        $sut = $this->makeInstance();

        $body_text = (new Color('#000000'))->toHsla();
        $bodyClrPalette = new Palette('bodyColor', 'Color for text', $body_text);

        $sut->add($bodyClrPalette);

//        $sut->addMultiple(ShadesGeneratorExperimental::fromColorInfo($body_text, 'bodyColor')->toArray());
        $sut->addMultiple(ShadesGeneratorExperimental::fromPalette($bodyClrPalette)->toArray());

        $this->assertSame(
            'var(--wp--preset--color--body-color)',
            $sut->get('color.bodyColor')->var()
        );

        $this->assertSame(
            'hsla(0,0%,0%,1)',
            (string)$sut->get('color.bodyColor')->color()->toHsla()
        );

        $this->assertSame(
            'var(--wp--preset--color--body-color-100)',
            $sut->get('color.bodyColor-100')->var()
        );

        $this->assertSame(
            'hsla(0,0%,10%,1)',
            (string)$sut->get('color.bodyColor-100')->color()->toHsla()
        );

        $this->assertSame(
            'var(--wp--preset--color--body-color-200)',
            $sut->get('color.bodyColor-200')->var()
        );

        $this->assertSame(
            'hsla(0,0%,20%,1)',
            (string)$sut->get('color.bodyColor-200')->color()->toHsla()
        );

        $this->assertSame(
            'var(--wp--preset--color--body-color-300)',
            $sut->get('color.bodyColor-300')->var()
        );

        $this->assertSame(
            'hsla(0,0%,30%,1)',
            (string)$sut->get('color.bodyColor-300')->color()->toHsla()
        );
    }

    public function testFonts(): void
    {
//        $robotoPath = \codecept_data_dir('fixtures/fonts/Roboto/Roboto-Regular.ttf');
//        codecept_debug($robotoPath);

//        $font = \FontLib\Font::load($robotoPath);
//        $font->parse();  // for getFontWeight() to work this call must be done first!
//        codecept_debug($font->getFontName());
//        codecept_debug($font->getFontSubfamily());
//        codecept_debug($font->getFontSubfamilyID());
//        codecept_debug($font->getFontFullName());
//        codecept_debug($font->getFontVersion());
//        codecept_debug($font->getFontWeight());
//        codecept_debug($font->getFontPostscriptName());
//        $font->close();

        $relativePath = 'fixtures/fonts/';
        $fontFace = [];
        $files = \glob(\codecept_data_dir($relativePath . '**/*'), GLOB_BRACE);
        foreach ($files as $file) {
            break;
            $font = \FontLib\Font::load($file);
            if (!$font instanceof \FontLib\TrueType\File) {
                continue;
            }

            $font->parse();  // for getFontWeight() to work this call must be done first!
//          codecept_debug($font->getFontName());
//          codecept_debug($font->getFontSubfamily());
//          codecept_debug($font->getFontSubfamilyID());
//          codecept_debug($font->getFontFullName());
//          codecept_debug($font->getFontVersion());
//          codecept_debug($font->getFontWeight());
            $fontFace[] = [
                'fontFamily' => $font->getFontName(),
                'fontStyle' => \mb_strtolower($font->getFontSubfamily()),
                'fontWeight' => $font->getFontWeight(),
                'src' => [
                    \sprintf(
                        'file:./%s',
                        $relativePath . \basename($file)
                    ),
                ],
            ];
            codecept_debug($font->getFontPostscriptName());
            $font->close();
//          break;
        }

//        codecept_debug($fontFace);
    }
}
