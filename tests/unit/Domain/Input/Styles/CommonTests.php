<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Styles;

use ItalyStrap\ThemeJsonGenerator\Application\Config\Blueprint;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\SectionNames;

trait CommonTests
{
    public function testItShouldBeAnInstanceOfJsonSerializable(): void
    {
        $sut = $this->makeInstance();
        $this->assertInstanceOf(\JsonSerializable::class, $sut);
    }

    public function testItShouldReturnASerializedResult(): void
    {
        $sut = $this->makeInstance();
        $sut->property('property', 'value');

        $this->assertStringMatchesFormat(
            '{"property":"value"}',
            \json_encode($sut),
            ''
        );

        $this->assertSame(
            \json_encode($sut),
            '[]',
            'Calling the second time should return an empty array'
        );

        // Now we repopulate the array, same property but different value
        $sut->property('property', 'another-value');

        $this->assertStringMatchesFormat(
            '{"property":"another-value"}',
            \json_encode($sut),
            ''
        );

        $this->assertSame(
            \json_encode($sut),
            '[]',
            'Calling the second time should return an empty array'
        );
    }

    public function testItShouldBeImmutable(): void
    {
        $sut = $this->makeInstance();

        $data = [
            SectionNames::STYLES => [
                'blocks' => [
                    'core/site-title' => [
                        'color' => $object1 = $sut->property('property', 'core/site-title'),
                        'typography' => $object2 = $sut->property('property', 'core/site-title'),
                    ],
                    'core/post-title' => [
                        'color' => $object3 = $sut->property('property', 'core/post-title'),
                        'typography' => $object4 = $sut->property('property', 'core/post-title'),
                    ],
                ],
            ],
        ];

        $data = new Blueprint($data);

        $this->assertStringMatchesFormat(
            '{"styles":{"blocks":{"core/site-title":{"color":{"property":"core/site-title"},"typography":{"property":"core/site-title"}},"core/post-title":{"color":{"property":"core/post-title"},"typography":{"property":"core/post-title"}}}}}',
            \json_encode($data, \JSON_UNESCAPED_SLASHES),
            ''
        );

        $this->assertNotSame(
            $object1,
            $object2,
            ''
        );

        $this->assertNotSame(
            $object3,
            $object4,
            ''
        );
    }

    public function testItShouldCreateUserDefinedProperty(): void
    {
        $sut = $this->makeInstance();
        $result = $sut->property('style', '#000000')->toArray();

        $this->assertStringMatchesFormat('#000000', $result['style'], '');
    }

    public function testItShouldBeImmutableAlsoIfICloneIt(): void
    {
        $sut = $this->makeInstance();
        $sut->property('style', '#000000');

        $sut_cloned = clone $sut;

        $this->assertNotEmpty($sut->toArray(), '');
        $this->assertEmpty($sut_cloned->toArray(), '');

        $sut_cloned->property('style', '#000000');

        $this->assertNotSame($sut->toArray(), $sut_cloned->toArray(), '');
    }
}
