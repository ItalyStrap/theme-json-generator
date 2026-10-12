<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Factories;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Color;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorsGenerator;

final readonly class ColorPresetsFromGeneratorFactory
{
    public function __construct(
        private ColorsGenerator $generator,
        private string $slugPrefix,
        private string $namePrefix
    ) {
    }

    /**
     * Preserve source keys, order, and color instances, including equal values.
     *
     * @return array<array-key, Color>
     */
    public function create(): array
    {
        $presets = [];

        foreach ($this->generator->generate() as $identifier => $color) {
            $presets[$identifier] = new Color(
                $this->prefixedIdentifier($this->slugPrefix, $identifier, '-'),
                $this->prefixedIdentifier($this->namePrefix, $identifier, ' '),
                $color
            );
        }

        return $presets;
    }

    private function prefixedIdentifier(string $prefix, int|string $identifier, string $separator): string
    {
        if ($prefix === '') {
            return (string) $identifier;
        }

        return $prefix . $separator . $identifier;
    }
}
