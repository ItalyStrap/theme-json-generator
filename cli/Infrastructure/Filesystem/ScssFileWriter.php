<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Filesystem;

use Webimpress\SafeWriter\FileWriter;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;

final readonly class ScssFileWriter
{
    public function __construct(private string $path)
    {
    }

    public function write(PresetsInterface $presets): void
    {
        FileWriter::writeFile(
            $this->path,
            $this->generateContent($presets)
        );
    }

    private function generateContent(PresetsInterface $presets): string
    {
        $properties = [];

        foreach ($presets->presets() as $preset) {
            $properties[$preset->prop()] = true;
        }

        \ksort($properties);

        return \implode('', \array_map(
            $this->declaration(...),
            \array_keys($properties)
        ));
    }

    private function declaration(string $property): string
    {
        return \sprintf(
            '$%1$s: %2$s;%3$s',
            \ltrim($property, '-'),
            $property,
            \PHP_EOL
        );
    }
}
