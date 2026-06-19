<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Custom\Custom as CustomPreset;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class CustomSectionTest extends UnitTestCase
{
    public function testItShouldAcceptAnArrayKeyPath(): void
    {
        $presets = new Presets();
        $themeJson = new ThemeJson(new Config(), $presets);

        $themeJson->settings()->custom()->add(['spacing', 'base'], '1rem');

        $this->assertSame('1rem', (string) $presets->get('custom.spacing.base'));
    }

    /**
     * @dataProvider invalidArrayKeyProvider
     * @param array<array-key, mixed> $key
     */
    public function testItShouldRejectInvalidArrayKeyPaths(array $key, string $expectedKey): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Custom key "%s" contains an empty segment.',
            $expectedKey
        ));

        $this->makeThemeJson()->settings()->custom()->add($key, '1rem');
    }

    public static function invalidArrayKeyProvider(): iterable
    {
        yield 'empty path' => [[], '<empty>'];
        yield 'empty first segment' => [['', 'base'], '.base'];
        yield 'empty nested segment' => [['spacing', ''], 'spacing.'];
    }

    public function testItShouldRejectEmptyCustomValues(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Custom value for key "spacing.base" must be a non-empty string.');

        $this->makeThemeJson()->settings()->custom()->add('spacing.base', '');
    }

    /**
     * @dataProvider invalidCustomValueProvider
     */
    public function testItShouldRejectNonStringCustomValues(mixed $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Custom value for key "spacing.base" must be a non-empty string.');

        $this->makeThemeJson()->settings()->custom()->addMultiple(['spacing' => ['base' => $value]]);
    }

    public static function invalidCustomValueProvider(): iterable
    {
        yield 'null' => [null];
        yield 'false' => [false];
        yield 'true' => [true];
        yield 'integer' => [1];
        yield 'float' => [1.5];
        yield 'empty string' => [''];
    }

    public function testItShouldAcceptCustomPresetAtFirstLevel(): void
    {
        $presets = new Presets();
        $themeJson = new ThemeJson(new Config(), $presets);

        $themeJson->settings()->custom()->addMultiple([
            new CustomPreset('spacing.base', '1rem'),
        ]);

        $this->assertSame('1rem', (string) $presets->get('custom.spacing.base'));
    }

    public function testItShouldRejectNestedCustomPreset(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'CustomPreset "base" at key "spacing.0" is supported only at the first level.'
        );

        $this->makeThemeJson()->settings()->custom()->addMultiple([
            'spacing' => [
                new CustomPreset('base', '1rem'),
            ],
        ]);
    }

    private function makeThemeJson(): ThemeJson
    {
        return new ThemeJson(new Config(), new Presets());
    }
}
