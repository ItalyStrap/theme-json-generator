<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Typography\Utilities;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\Utilities\FontFace;

final class FontFaceTest extends UnitTestCase
{
    public function testItShouldSerializeTheSchemaSupportedFields(): void
    {
        $sut = new FontFace(
            'Inter',
            400,
            'normal',
            'normal',
            'file:./inter.woff2',
            'swap',
            '90%',
            '20%',
            'small-caps',
            '"liga"',
            '"wght" 400',
            'normal',
            '100%',
            'U+000-5FF'
        );

        $this->assertSame(
            [
                'fontFamily' => 'Inter',
                'fontWeight' => 400,
                'fontStyle' => 'normal',
                'fontDisplay' => 'swap',
                'src' => 'file:./inter.woff2',
                'fontStretch' => 'normal',
                'ascentOverride' => '90%',
                'descentOverride' => '20%',
                'fontVariant' => 'small-caps',
                'fontFeatureSettings' => '"liga"',
                'fontVariationSettings' => '"wght" 400',
                'lineGapOverride' => 'normal',
                'sizeAdjust' => '100%',
                'unicodeRange' => 'U+000-5FF',
            ],
            $sut->toArray()
        );
    }

    public function testItShouldRejectUnsupportedFontDisplay(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Expected font display "auto", "block", "fallback", "swap" or "optional", got "invalid".'
        );

        new FontFace('Inter', '400', 'normal', 'normal', ['file:./inter.woff2'], 'invalid');
    }

    public function testItShouldRejectEmptySources(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected at least one font source.');

        new FontFace('Inter', '400', 'normal', 'normal', []);
    }

    public function testItShouldRejectInvalidSources(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected font sources to be non-empty strings.');

        new FontFace('Inter', '400', 'normal', 'normal', ['']);
    }
}
