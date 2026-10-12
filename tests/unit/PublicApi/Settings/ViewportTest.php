<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\Settings\Viewport;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class ViewportTest extends UnitTestCase
{
    public function testItShouldWriteIndependentBreakpointsWithoutImplicitDefaults(): void
    {
        $themeJson = new ThemeJson(new Config(), new Presets());
        $viewport = $themeJson->settings()->viewport();

        $this->assertInstanceOf(Viewport::class, $viewport);
        $this->assertNull($themeJson->get('settings.viewport'));
        $this->assertSame($viewport, $viewport->mobile('480px'));
        $this->assertSame(['mobile' => '480px'], $themeJson->get('settings.viewport'));
        $this->assertSame($viewport, $viewport->tablet('48.875rem'));
        $this->assertSame(['mobile' => '480px', 'tablet' => '48.875rem'], $themeJson->get('settings.viewport'));
    }

    /**
     * @dataProvider validWidthProvider
     */
    public function testItShouldAcceptSchemaLengthsInEitherBreakpoint(string $width): void
    {
        $themeJson = new ThemeJson(new Config(), new Presets());
        $themeJson->settings()->viewport()->mobile($width)->tablet($width);

        $this->assertSame(['mobile' => $width, 'tablet' => $width], $themeJson->get('settings.viewport'));
    }

    public static function validWidthProvider(): iterable
    {
        yield 'pixels' => ['480px'];
        yield 'em' => ['30em'];
        yield 'rem' => ['48.875rem'];
        yield 'fractional pixels' => ['480.5px'];
        yield 'leading decimal' => ['.5em'];
        yield 'zero' => ['0px'];
    }

    /**
     * @dataProvider invalidWidthProvider
     */
    public function testItShouldRejectInvalidWidthsBeforeWriting(string $method, string $width): void
    {
        $themeJson = new ThemeJson(new Config(), new Presets());
        $viewport = $themeJson->settings()->viewport();
        $viewport->{$method}('480px');

        try {
            $viewport->{$method}($width);
            $this->fail('Expected an invalid viewport width to be rejected.');
        } catch (\InvalidArgumentException $exception) {
            $this->assertSame(
                'Viewport width must be a non-negative length in px, em, or rem.',
                $exception->getMessage()
            );
        }

        $this->assertSame('480px', $themeJson->get('settings.viewport.' . $method));
    }

    public static function invalidWidthProvider(): iterable
    {
        $invalidWidths = [
            '',
            '480',
            '-1px',
            '40vw',
            '100%',
            '1e3px',
            '480PX',
            ' 480px',
            "480px\n",
            '1.px',
        ];

        foreach (['mobile', 'tablet'] as $method) {
            foreach ($invalidWidths as $index => $width) {
                yield $method . ' invalid ' . $index => [$method, $width];
            }
        }
    }

    public function testItShouldRejectBlockScopedSettings(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The viewport section is only supported at the root settings level.');

        (new ThemeJson(new Config(), new Presets()))->settings()->blocks('core/group')->viewport();
    }
}
