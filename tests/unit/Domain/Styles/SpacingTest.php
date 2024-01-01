<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Styles;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Styles\Spacing;

class SpacingTest extends UnitTestCase
{
    use CommonTests;

    private function makeInstance(): Spacing
    {
        return new Spacing();
    }

    public static function valueProvider(): \Generator
    {

        yield '1 value' => [
            ['0px'],
            [
                'top'       => '0px',
                'right'     => '0px',
                'bottom'    => '0px',
                'left'      => '0px',
            ]
        ];

        yield '1 value with int 0 without unit' => [
            [0],
            [
                'top'       => '0',
                'right'     => '0',
                'bottom'    => '0',
                'left'      => '0',
            ]
        ];

        yield '1 value with string "0" without unit' => [
            ['0'],
            [
                'top'       => '0',
                'right'     => '0',
                'bottom'    => '0',
                'left'      => '0',
            ]
        ];

        yield '2 values'    => [
            ['0px', 'auto'],
            [
                'top'       => '0px',
                'right'     => 'auto',
                'bottom'    => '0px',
                'left'      => 'auto',
            ]
        ];

        yield '3 values'    => [
            ['0px', 'auto', '10px'],
            [
                'top'       => '0px',
                'right'     => 'auto',
                'bottom'    => '10px',
                'left'      => 'auto',
            ]
        ];

        yield '4 values'    => [
            ['1px', '2px', '3px', '4px'],
            [
                'top'       => '1px',
                'right'     => '2px',
                'bottom'    => '3px',
                'left'      => '4px',
            ]
        ];

        yield '4 values with some empty'    => [
            ['1px', '', '3px', ''],
            [
                'top'       => '1px',
                'bottom'    => '3px',
            ]
        ];
    }

    /**
     * @dataProvider valueProvider
     */
    public function testItShouldReturnArrayWithSpacingPropsFor(array $value, array $expected): void
    {
        $sut = $this->makeInstance();
        $sut->shorthand($value);
        $this->assertEquals($expected, $sut->toArray(), '');
    }

    public static function methodsProvider(): \Generator
    {
        yield 'Top' => [
            'top',
            '25px'
        ];

        yield 'Right' => [
            'right',
            '25px'
        ];

        yield 'Bottom' => [
            'bottom',
            '25px'
        ];

        yield 'Left' => [
            'left',
            '25px'
        ];
    }

    /**
     * @dataProvider methodsProvider
     */
    public function testItShouldReturnAnArray(string $method, string $value): void
    {

        $sut = $this->makeInstance();

        \call_user_func([ $sut, $method ], $value);

        $this->assertIsArray($sut->toArray(), '');
    }

    public function testItShouldReturnTheCorrectValue(): void
    {

        $sut = $this->makeInstance();
        $sut->top('25px')
            ->right('25px')
            ->bottom('50px')
            ->left('5rem');

        $this->assertEquals(
            [
                'top'       => '25px',
                'right'     => '25px',
                'bottom'    => '50px',
                'left'      => '5rem',
            ],
            $sut->toArray()
        );
    }

    public function testItShouldBeImmutable(): void
    {

        $sut = $this->makeInstance();
        $sut->top('25px')
            ->left('25px');

        $this->expectException(\RuntimeException::class);
        $sut->top('22');
    }

    public function testItShouldBeImmutableAlsoIfICloneIt(): void
    {

        $sut = $this->makeInstance();
        $sut->top('25px')
            ->left('25px');

        $sut_cloned = clone $sut;

        $this->assertNotEmpty($sut->toArray(), '');
        $this->assertEmpty($sut_cloned->toArray(), '');

        $sut_cloned->left('20px');
    }

    public function testItShouldBeStringable(): void
    {

        $sut = $this->makeInstance();

        $sut->top('25px');
        $this->assertStringMatchesFormat('25px 0 0 0', (string) $sut, '');

        $sut->bottom('20px');
        $this->assertStringMatchesFormat('25px 0 20px 0', (string) $sut, '');

        $sut->left('0');
        $this->assertStringMatchesFormat('25px 0 20px 0', (string) $sut, '');

        $sut->right('50px');
        $this->assertStringMatchesFormat('25px 50px 20px 0', (string) $sut, '');
    }

    public static function sameValueProvider(): \Generator
    {

        yield 'Value of 0'  => [
            ['0','0','0','0',],
            '0'
        ];

        yield 'Value of 0px'    => [
            ['0px','0px','0px','0px',],
            '0px'
        ];

        yield 'Value of 0 if we have 0px and 0' => [
            ['0px','0','0px','0px',],
            '0px'
        ];

        yield 'Value of 42px'   => [
            ['42px','42px','42px','42px',],
            '42px'
        ];

        yield 'Value of 42px even if we have 42 with no unit'   => [
            ['42px','42','42px','42px',],
            '42px'
        ];
    }

    /**
     * @dataProvider sameValueProvider
     */
    public function testItShouldBeStringableAndReturnOnly(array $value, string $expected): void
    {

        $sut = $this->makeInstance();

        $sut->top($value[0]);
        $sut->bottom($value[1]);
        $sut->left($value[2]);
        $sut->right($value[3]);

        $this->assertStringMatchesFormat($expected, (string) $sut, '');
    }

    public function testItShouldCreateCorrectJson(): void
    {
        $sut = $this->makeInstance();
        $result = $sut
            ->top('25px')
            ->right('25px')
            ->bottom('50px')
            ->left('5rem');

        $this->assertJsonStringEqualsJsonString(
            '{"top":"25px","right":"25px","bottom":"50px","left":"5rem"}',
            \json_encode($result),
            ''
        );
    }
}
