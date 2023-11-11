<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Settings;

use ItalyStrap\ThemeJsonGenerator\Domain\Settings\PresetCollection;

class PresetCollectionTest extends BaseCollectionTest
{
	// phpcs:ignore
	protected function _before() {
        $this->collection = [
            [
                'slug'  => 'primary',
                'color' => '#ffffff',
            ],
        ];
        $this->category = 'color';
    }

    protected function makeInstance(): PresetCollection
    {
        return new PresetCollection($this->collection, $this->category, $this->key);
    }

    /**
     * @test
     */
    public function itShouldHaveCategory()
    {
        $expected = 'expected';
        $this->category = $expected;
        $sut = $this->makeInstance();

        $this->assertStringMatchesFormat(
            $expected,
            $sut->category(),
            ''
        );
    }

    /**
     * @see BaseCollectionTest
     * @return \Generator
     */
    public function valueProvider()
    {
        yield 'Primary color' => [
            '#ffffff', // Expected
            'primary', // Slug
            'color', // Category
            'color', // Key
        ];
    }

    /**
     * @dataProvider valueProvider
     * @test
     */
    public function itShouldReturnValidValue(
        string $expected,
        string $slug,
        string $category,
        string $key
    ) {

        $this->collection = [
            [
                'slug'  => $slug,
                $key    => $expected,
            ],
        ];

        $this->category = $category;

        $sut = $this->makeInstance();

        $this->assertIsString($sut->value($slug));

        $this->assertStringMatchesFormat(
            $expected,
            $sut->value($slug),
            ''
        );
    }

    public function propertiesProvider()
    {
        yield 'Primary color' => [
            '--wp--preset--color--primary',
            'primary',
            '#ffffff',
        ];

        yield 'Secondary color' => [
            '--wp--preset--color--secondary',
            'secondary',
            '#000000',
        ];

        yield 'Foo color' => [
            '--wp--preset--color--foo',
            'Foo',
            '#000000',
        ];

        yield 'FooBar color' => [
            '--wp--preset--color--foo-bar',
            'FooBar',
            '#000000',
        ];

        yield 'Foo123 color' => [
            '--wp--preset--color--foo-123',
            'Foo123',
            '#000000',
        ];

        yield 'Foo123Bar color' => [
            '--wp--preset--color--foo-123-bar',
            'Foo123Bar',
            '#000000',
        ];

        yield 'foo color' => [
            '--wp--preset--color--foo',
            'foo',
            '#000000',
        ];

        yield 'fooBar color' => [
            '--wp--preset--color--foo-bar',
            'fooBar',
            '#000000',
        ];

        yield 'foo123 color' => [
            '--wp--preset--color--foo-123',
            'foo123',
            '#000000',
        ];

        yield '123Foo color' => [
            '--wp--preset--color--123-foo',
            '123Foo',
            '#000000',
        ];

        yield 'fooBar123 color' => [
            '--wp--preset--color--foo-bar-123',
            'fooBar123',
            '#000000',
        ];

        yield 'foo123Bar color' => [
            '--wp--preset--color--foo-123-bar',
            'foo123Bar',
            '#000000',
        ];

        yield '123FooBar color' => [
            '--wp--preset--color--123-foo-bar',
            '123FooBar',
            '#000000',
        ];
    }

    /**
     * @dataProvider propertiesProvider
     * @test
     */
    public function itShouldReturnCssPropertyFor(string $expected, string $prop, string $value)
    {

        $this->collection = [
            [
                'slug'  => $prop,
                'color' => $value,
            ],
        ];

        $this->category = 'color';

        $sut = $this->makeInstance();

        $this->assertStringMatchesFormat(
            $expected,
            $sut->propOf($prop),
            ''
        );
    }

    /**
     * @test
     */
    public function itShouldReturnVarFunctionCssWithVariableCss()
    {
        $sut = $this->makeInstance();

        $this->assertStringMatchesFormat(
            'var(--wp--preset--color--primary)',
            $sut->varOf('primary'),
            ''
        );
    }

    public function placeholderProvider()
    {

        /**
         * [
         *  // expected
         *  // slug
         *  // value
         * $category,
        string $key,
        array $colection
         * ]
         */

        yield 'Base with CSS property' => [
            'calc(var(--wp--preset--font-size--base)*2)',
            'h1',
            'fontSize',
            'size',
            [
                [
                    'slug'  => 'base',
                    'size'  => '20px',
                ],
                [
                    'slug'  => 'h1',
                    'size'  => 'calc({{base}}*2)',
                ],
            ],
        ];

        yield 'With Default Value 20px' => [
            'calc(20px*2)',
            'h2',
            'fontSize',
            'size',
            [
                [
                    'slug'  => 'base',
                    'size'  => '20px',
                ],
                [
                    'slug'  => 'h2',
                    'size'  => 'calc({{propInexistent|20px}}*2)',
                ],
            ],
        ];

        yield 'Accept only the first two value separated from |' => [
            '20px|15',
            'h2',
            'fontSize',
            'size',
            [
                [
                    'slug'  => 'base',
                    'size'  => '20px',
                ],
                [
                    'slug'  => 'h2',
                    'size'  => '{{propInexistent|20px|15}}',
                ],
            ],
        ];
    }

    /**
     * @dataProvider placeholderProvider
     * @test
     */
    public function itShouldReplaceStringPlaceholder(
        string $expected,
        string $slug,
        string $category,
        string $key,
        array $collection
    ) {

        $this->collection = $collection;

        $this->category = $category;
        $this->key = $key;

        $sut = $this->makeInstance();

        $this->assertStringMatchesFormat(
            $expected,
            $sut->value($slug),
            ''
        );
    }

    /**
     * @test
     */
    public function itShouldGetValueFromOtherCollection()
    {
        $expected = 'linear-gradient(160deg,var(--wp--preset--color--text),var(--wp--preset--color--background))';

        $this->collection = [
            [
                'slug'  => 'background',
                'color' => '#ffffff',
            ],
            [
                'slug'  => 'text',
                'color' => '#000000',
            ],
        ];

        $this->category = 'color';
        $this->key = 'color';

        $palette = $this->makeInstance();

        $this->collection = [
            [
                'slug'  => 'black-to-white',
                'gradient'  => \sprintf(
                    'linear-gradient(160deg,%s,%s)',
                    '{{color.text}}',
                    '{{color.background}}'
                ),
            ],
        ];

        $this->category = 'gradient';
        $this->key = 'gradient';

        $gradient = $this->makeInstance();

        $gradient->withCollection($palette);
//      $gradient->withCollection( $palette, $palette, $palette );

        $this->assertStringMatchesFormat(
            $expected,
            $gradient->value('black-to-white'),
            ''
        );
    }
}
