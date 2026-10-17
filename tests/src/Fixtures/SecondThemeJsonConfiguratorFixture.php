<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Fixtures;

use ItalyStrap\ThemeJsonGenerator\ConfiguratorInterface;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class SecondThemeJsonConfiguratorFixture implements ConfiguratorInterface
{
    public function __invoke(ThemeJson $themeJson): void
    {
        $themeJson->styles()->appendCss('c{color:green;}');
    }
}
