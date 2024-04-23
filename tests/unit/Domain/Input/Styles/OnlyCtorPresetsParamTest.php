<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Styles;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Border;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Color;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Css;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Outline;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Scss;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Spacing;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Typography;

class OnlyCtorPresetsParamTest extends UnitTestCase
{
    public static function classNameDataProvider(): iterable
    {
        yield Border::class => [Border::class];
        yield Color::class => [Color::class];
        yield Css::class => [Css::class];
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
                \implode(', ', \array_map(fn(\ReflectionParameter $p) => '$' . $p->getName(), $parameters))
            )
        );
    }
}
