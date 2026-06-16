<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Styles;

use ItalyStrap\Config\Config;
use ItalyStrap\ThemeJsonGenerator\Settings\NullPresets;
use ItalyStrap\ThemeJsonGenerator\Styles;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

trait CommonTests
{
    protected function makeStyles(): Styles
    {
        return (new ThemeJson(new Config(), new NullPresets()))->styles();
    }

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
            '{"property":"value"}',
            'Calling the second time should return the same result'
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
            '{"property":"another-value"}',
            'Calling the second time should return the same result'
        );
    }

    public function testItShouldReturnTheSameArrayWhenCalledMultipleTimes(): void
    {
        $sut = $this->makeInstance();
        $sut->property('property', 'value');

        $this->assertSame(['property' => 'value'], $sut->toArray());
        $this->assertSame(['property' => 'value'], $sut->toArray());
    }

    public function testItShouldBeImmutable(): void
    {
        $sut = $this->makeInstance();

        $data = [
            Styles::SECTION => [
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

        $config = new Config($data);
        new ThemeJson(
            $config,
            $this->makePresets(),
        );

        $this->assertStringMatchesFormat(
            // phpcs:disable
            '{"styles":{"blocks":{"core/site-title":{"color":{"property":"core/site-title"},"typography":{"property":"core/site-title"}},"core/post-title":{"color":{"property":"core/post-title"},"typography":{"property":"core/post-title"}}}}}',
            // phpcs:enable
            \json_encode($config, \JSON_UNESCAPED_SLASHES),
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

        $this->assertSame(['style' => '#000000'], $sut->toArray(), '');
        $this->assertSame([], $sut_cloned->toArray(), '');

        $sut_cloned->property('style', '#ffffff');

        $this->assertSame(['style' => '#000000'], $sut->toArray(), '');
        $this->assertSame(['style' => '#ffffff'], $sut_cloned->toArray(), '');
    }

    public function testTheNameOfVariableInConstructorMustBePresets(): void
    {
        $reflection = new \ReflectionClass($this->makeInstance());
        $constructor = $reflection->getConstructor();
        $parameters = $constructor->getParameters();

        $this->assertSame(
            'presets',
            $parameters[0]->getName(),
            // phpcs:disable
            'The name of the variable in the constructor of \ItalyStrap\ThemeJsonGenerator\Styles\CommonTrait must be presets'
            // phpcs:enable
        );
        $this->assertSame(
            'context',
            $parameters[2]->getName(),
            // phpcs:disable
            'The name of the context variable in the constructor of \ItalyStrap\ThemeJsonGenerator\Styles\CommonTrait must be context'
            // phpcs:enable
        );
        $this->assertFalse($parameters[2]->allowsNull(), 'The context parameter must not allow null');
        $this->assertFalse($parameters[2]->isDefaultValueAvailable(), 'The context parameter must be required');
    }
}
