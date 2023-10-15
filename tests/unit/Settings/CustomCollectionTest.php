<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Settings;

use ItalyStrap\ThemeJsonGenerator\Settings\CollectionInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\CustomCollection;

class CustomCollectionTest extends BaseCollectionTest
{
	// phpcs:ignore
	protected function _before() {

        $this->collection = [
            'contentSize'   => '60vw',
            'wideSize'  => '80vw',
            'baseFontSize' => "1rem",
            'spacer' => [
                'base'  => '1rem',
                'v'     => 'calc(var(--wp--custom--spacer--base)*4)',
                'h'     => 'calc(var(--wp--custom--spacer--base)*4)',
            ],
            'lineHeight' => [
                'small' => 1.2,
                'medium' => 1.4,
                'large' => 1.8
            ],
            'very' => [
                'indented' => [
                    'multiDimensional'  => [
                        'inner' => 'element',
                    ],
                ],
            ],
        ];
    }

    protected function makeInstance(): CustomCollection
    {
        $sut = new CustomCollection($this->collection);
        $this->assertInstanceOf(CollectionInterface::class, $sut, '');
        return $sut;
    }

    /**
     * @test
     */
    public function itShouldHaveCategory()
    {
        $expected = 'custom';
        $sut = $this->makeInstance();

        $this->assertStringMatchesFormat(
            $expected,
            $sut->category(),
            ''
        );
    }

    /**
     * @test
     */
    public function itShouldWorksWithCustom()
    {

        $this->category = 'custom';

        $sut = $this->makeInstance();
        $sut->value('contentSize');
        $sut->toArray();
    }

    public function valueProvider()
    {
        yield 'Custom content size' => [
            '60vw', // Expected
            'contentSize', // Slug
            'custom', // Category
        ];

        yield 'Custom spacer base' => [
            '1rem', // Expected
            'spacer.base', // Slug
            'custom', // Category
        ];
    }

    /**
     * @dataProvider valueProvider
     * @test
     */
    public function itShouldReturnValidValue(
        string $expected,
        string $slug,
        string $category
    ) {

        $this->collection = [
            'contentSize'   => '60vw',
            'wideSize'  => '80vw',
            'baseFontSize' => "1rem",
            'spacer' => [
                'base'  => '1rem',
                'v'     => 'calc(var(--wp--custom--spacer--base)*4)',
                'h'     => 'calc(var(--wp--custom--spacer--base)*4)',
            ],
            'lineHeight' => [
                'small' => 1.2,
                'medium' => 1.4,
                'large' => 1.8
            ],
        ];

        $this->category = $category;

        $sut = $this->makeInstance();

        $this->assertStringMatchesFormat(
            $expected,
            $sut->value($slug),
            ''
        );
    }

    public function propertiesProvider()
    {
        yield 'Base font size' => [
            '--wp--custom--base-font-size',
            'baseFontSize'
        ];
        yield 'Base spacer' => [
            '--wp--custom--spacer--base',
            'spacer.base'
        ];
        yield 'Inner element' => [
            '--wp--custom--very--indented--multi-dimensional--inner',
            'very.indented.multiDimensional.inner'
        ];
    }

    /**
     * @dataProvider propertiesProvider
     * @test
     */
    public function itShouldReturnCssPropertyFor(string $expected, string $slug)
    {

        $sut = $this->makeInstance();

        $this->assertStringMatchesFormat(
            $expected,
            $sut->propOf($slug),
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

        yield 'Only spacer' => [
            'calc(var(--wp--custom--base)*4)',
            'spacer',
            'fontSize',
            'size',
            [
                'base'  => '1rem',
                'spacer' => 'calc({{base}}*4)',
            ],
        ];

        yield 'Spacer * Base' => [
            'calc(var(--wp--custom--base)*var(--wp--custom--double))',
            'spacer',
            'fontSize',
            'size',
            [
                'base'  => '1rem',
                'double'    => '2rem',
                'spacer' => 'calc({{base}}*{{double}})',
            ],
        ];

        yield 'Spacer base' => [
            'calc(var(--wp--custom--spacer--base)*4)',
            'spacer.v',
            'fontSize',
            'size',
            [
                'spacer' => [
                    'base'  => '1rem',
                    'v'     => 'calc({{spacer.base}}*4)',
                ],
            ],
        ];

        yield 'Sub Key Spacer base' => [
            'calc(var(--wp--custom--spacer--base)*4)',
            'spacer.sub.v',
            'fontSize',
            'size',
            [
                'spacer' => [
                    'base'  => '1rem',
                    'sub'       => [
                        'v' => 'calc({{spacer.base}}*4)',
                    ],
                ],
            ],
        ];

        yield 'Sub Value Spacer base' => [
            'calc(var(--wp--custom--spacer--base--value)*4)',
            'spacer.sub.v',
            'fontSize',
            'size',
            [
                'spacer' => [
                    'base'  => [
                        'value' => '1rem'
                    ],
                    'sub'       => [
                        'v' => 'calc({{spacer.base.value}}*4)',
                    ],
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
}
