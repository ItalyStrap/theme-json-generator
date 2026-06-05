<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\Fixtures\FirstThemeJsonConfiguratorFixture;
use ItalyStrap\Tests\Fixtures\SecondThemeJsonConfiguratorFixture;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Pipeline;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;
use Psr\Container\ContainerInterface;

final class PipelineTest extends UnitTestCase
{
    public function testItShouldProcessConfiguratorsInOrder(): void
    {
        $themeJson = new ThemeJson(
            new Config(),
            new Presets(),
        );
        $container = $this->makeContainer([
            FirstThemeJsonConfiguratorFixture::class => new FirstThemeJsonConfiguratorFixture()
        ]);

        $sut = new Pipeline($container, $themeJson);

        $result = $sut->process([
            FirstThemeJsonConfiguratorFixture::class,
            static fn (ThemeJson $themeJson): bool => $themeJson->styles()->appendCss('b{color:blue;}'),
            new SecondThemeJsonConfiguratorFixture(),
        ]);

        $this->assertSame($themeJson, $result);
        $this->assertSame('a{color:red;}b{color:blue;}c{color:green;}', $themeJson->get('styles.css'));
    }

    public function testItShouldThrowExceptionForInvalidConfigurator(): void
    {
        $sut = new Pipeline($this->makeContainer(), new ThemeJson(
            new Config(),
            $this->makePresets(),
        ));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected configurator to be callable');

        $sut->process([new \stdClass()]);
    }

    /**
     * @param array<string, mixed> $services
     */
    private function makeContainer(array $services = []): ContainerInterface
    {
        return new class ($services) implements ContainerInterface {
            /**
             * @param array<string, mixed> $services
             */
            public function __construct(private array $services)
            {
            }

            public function get(string $id)
            {
                return $this->services[$id] ?? throw new \RuntimeException(\sprintf('Service %s not found.', $id));
            }

            public function has(string $id): bool
            {
                return \array_key_exists($id, $this->services);
            }
        };
    }
}
