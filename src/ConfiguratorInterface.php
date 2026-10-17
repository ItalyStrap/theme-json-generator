<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

interface ConfiguratorInterface
{
    public function __invoke(ThemeJson $themeJson): void;
}
