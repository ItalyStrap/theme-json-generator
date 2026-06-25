<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Application;

use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;

final readonly class ThemeJsonBuildResult
{
    /**
     * @param ConfigInterface<array-key, mixed> $config
     */
    public function __construct(
        public ConfigInterface $config,
        public PresetsInterface $presets
    ) {
    }
}
