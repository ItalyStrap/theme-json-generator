<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Container;

use ItalyStrap\Empress\AurynConfig;
use ItalyStrap\Empress\ModuleInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class ThemeJsonModule implements ModuleInterface
{
    /**
     * @return array<string, mixed>
     */
    public function __invoke(): array
    {
        return [
            AurynConfig::ALIASES => [
                PresetsInterface::class => Presets::class,
            ],
            AurynConfig::SHARING => [
                Presets::class,
                ThemeJson::class,
            ],
        ];
    }
}
