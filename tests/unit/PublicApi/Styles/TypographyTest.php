<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Styles;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Styles\Typography;

final class TypographyTest extends UnitTestCase
{
    use CommonTests;

    protected function makeInstance(): Typography
    {
        return new Typography();
    }

    public function testItShouldCreateCorrectArray(): void
    {
        $sut = $this->makeInstance();
        $result = $sut
            ->textDecoration('none')
            ->lineHeight('1')
            ->textIndent('1rem')
            ->textAlign('center')
            ->textColumns('2')
            ->writingMode('vertical-rl')
            ->fontSize('25px')
            ->fontWeight('800')
            ->textTransform('uppercase')
            ->fontStyle('value')
            ->letterSpacing('1rem')
            ->fontFamily('serif')
            ->toArray();

        $this->assertIsArray($result, '');
        $this->assertArrayHasKey('textDecoration', $result, '');
        $this->assertArrayHasKey('lineHeight', $result, '');
        $this->assertArrayHasKey('textIndent', $result, '');
        $this->assertArrayHasKey('textAlign', $result, '');
        $this->assertArrayHasKey('textColumns', $result, '');
        $this->assertArrayHasKey('writingMode', $result, '');
        $this->assertArrayHasKey('fontSize', $result, '');
        $this->assertArrayHasKey('fontWeight', $result, '');
        $this->assertArrayHasKey('textTransform', $result, '');
        $this->assertArrayHasKey('fontStyle', $result, '');
        $this->assertArrayHasKey('letterSpacing', $result, '');
        $this->assertArrayHasKey('fontFamily', $result, '');

        $this->assertStringMatchesFormat('none', $result['textDecoration'], '');
        $this->assertStringMatchesFormat('1', $result['lineHeight'], '');
        $this->assertStringMatchesFormat('1rem', $result['textIndent'], '');
        $this->assertStringMatchesFormat('center', $result['textAlign'], '');
        $this->assertStringMatchesFormat('2', $result['textColumns'], '');
        $this->assertStringMatchesFormat('vertical-rl', $result['writingMode'], '');
        $this->assertStringMatchesFormat('25px', $result['fontSize'], '');
        $this->assertStringMatchesFormat('800', $result['fontWeight'], '');
        $this->assertStringMatchesFormat('uppercase', $result['textTransform'], '');
        $this->assertStringMatchesFormat('value', $result['fontStyle'], '');
        $this->assertStringMatchesFormat('1rem', $result['letterSpacing'], '');
        $this->assertStringMatchesFormat('serif', $result['fontFamily'], '');
    }

    public function testItShouldCreateCorrectJson(): void
    {
        $sut = $this->makeInstance();
        $result = $sut
            ->textDecoration('none')
            ->lineHeight('1')
            ->textIndent('1rem')
            ->textAlign('center')
            ->textColumns('2')
            ->writingMode('vertical-rl')
            ->fontSize('25px')
            ->fontWeight('800')
            ->textTransform('uppercase')
            ->fontStyle('value')
            ->letterSpacing('1rem')
            ->fontFamily('serif');

        $this->assertJsonStringEqualsJsonString(
            // phpcs:disable
            '{"textDecoration":"none","lineHeight":"1","textIndent":"1rem","textAlign":"center","textColumns":"2","writingMode":"vertical-rl","fontSize":"25px","fontWeight":"800","textTransform":"uppercase","fontStyle":"value","letterSpacing":"1rem","fontFamily":"serif"}',
            // phpcs:enable
            \json_encode($result),
            ''
        );
    }
}
