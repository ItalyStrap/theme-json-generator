<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Styles;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Styles\Background;
use ItalyStrap\ThemeJsonGenerator\Styles\Border;
use ItalyStrap\ThemeJsonGenerator\Styles\Color;
use ItalyStrap\ThemeJsonGenerator\Styles\Css;
use ItalyStrap\ThemeJsonGenerator\Styles\Dimensions;
use ItalyStrap\ThemeJsonGenerator\Styles\Filter;
use ItalyStrap\ThemeJsonGenerator\Styles\Outline;
use ItalyStrap\ThemeJsonGenerator\Styles\Scss;
use ItalyStrap\ThemeJsonGenerator\Styles\Spacing;
use ItalyStrap\ThemeJsonGenerator\Styles\Typography;

final class OnlyCtorPresetsParamTest extends UnitTestCase
{
    public static function classNameDataProvider(): iterable
    {
        yield Background::class => [Background::class];
        yield Border::class => [Border::class];
        yield Color::class => [Color::class];
        yield Css::class => [Css::class];
        yield Dimensions::class => [Dimensions::class];
        yield Filter::class => [Filter::class];
        yield Outline::class => [Outline::class];
        yield Scss::class => [Scss::class];
        yield Spacing::class => [Spacing::class];
        yield Typography::class => [Typography::class];
    }

    /**
     * @dataProvider classNameDataProvider
     */
    public function testClassesThatNeedPresetsAsParameter(string $class): void
    {
        $reflection = new \ReflectionClass($class);
        $constructor = $reflection->getConstructor();
        $parameters = $constructor->getParameters();

        $this->assertNotEmpty($parameters, 'The constructor of ' . $class . ' is empty');

        $found = false;
        foreach ($parameters as $parameter) {
            if ($parameter->getName() === 'presets') {
                $found = true;
                break;
            }
        }

        $this->assertTrue(
            $found,
            \sprintf(
                "The constructor of %s does not have a parameter named \$preset, found: %s",
                $class,
                \implode(', ', \array_map(fn(\ReflectionParameter $p): string => '$' . $p->getName(), $parameters))
            )
        );
    }
}
