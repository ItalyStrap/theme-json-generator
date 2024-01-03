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
        $result = $sut->shorthand($value);
        $this->assertEquals($expected, $result->toArray(), '');
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

        \call_user_func([$sut, $method], $value);

        $this->assertIsArray($sut->toArray(), '');
    }

    public function testItShouldReturnTheCorrectValue(): void
    {

        $sut = $this->makeInstance();
        $result = $sut->top('25px')
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
            $result->toArray()
        );
    }

    public function testItShouldBeImmutableAlsoIfICloneIt(): void
    {

        $sut = $this->makeInstance();
        $result = $sut->top('25px')
            ->left('25px');

        $resultCloned = clone $result;

        $this->assertNotEmpty($result->toArray(), '');
        $this->assertEmpty($resultCloned->toArray(), '');

        $resultCloned->left('20px');
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
