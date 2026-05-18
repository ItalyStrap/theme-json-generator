<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Container;

use ItalyStrap\Empress\AurynConfig;
use ItalyStrap\Empress\ModuleInterface;
use ItalyStrap\ThemeJsonGenerator\Api\ThemeJson;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetsInterface;

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
