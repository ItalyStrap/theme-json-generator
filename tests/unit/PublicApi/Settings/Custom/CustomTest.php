<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Custom;

use ItalyStrap\Tests\Unit\PublicApi\Settings\PresetCommonTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Custom\Custom;

final class CustomTest extends UnitTestCase
{
    use PresetCommonTrait;

    private string $slug = 'base';

    private string $name = 'Custom';

    private function makeInstance(): Custom
    {
        return new Custom(
            $this->slug,
            $this->name
        );
    }

    /**
     * @dataProvider invalidKeyProvider
     * @param list<string>|string $key
     */
    public function testItShouldRejectInvalidKeys(array|string $key, string $expectedKey): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Custom key "%s" contains an empty segment.',
            $expectedKey
        ));

        new Custom($key, '1rem');
    }

    public static function invalidKeyProvider(): iterable
    {
        yield 'empty string' => ['', ''];
        yield 'empty path' => [[], '<empty>'];
        yield 'empty first segment' => [['', 'base'], '.base'];
        yield 'empty nested segment' => [['spacing', ''], 'spacing.'];
    }

    /**
     * @dataProvider invalidValueProvider
     */
    public function testItShouldRejectInvalidValues(mixed $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Custom value for key "spacing.base" must be a non-empty string.'
        );

        new Custom('spacing.base', $value);
    }

    public static function invalidValueProvider(): iterable
    {
        yield 'null' => [null];
        yield 'false' => [false];
        yield 'true' => [true];
        yield 'integer' => [1];
        yield 'float' => [1.5];
        yield 'empty string' => [''];
    }
}
