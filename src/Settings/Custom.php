<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Settings\Custom\Custom as CustomPreset;

final readonly class Custom
{
    use ScopedSettingsWriterTrait;

    private const SECTION = 'custom';

    public function __construct(
        private Settings $settings,
    ) {
    }

    /**
     * @param list<string>|string $key
     */
    #[ThemeSchemaCoverage(['settings', 'custom'])]
    public function add(array|string $key, string $value): self
    {
        $this->settings->addPreset(new CustomPreset($key, $value));

        return $this;
    }

    /**
     * @param array<array-key, mixed> $customs
     */
    #[ThemeSchemaCoverage(['settings', 'custom'])]
    public function addMultiple(array $customs): self
    {
        $this->presetsToFlat($customs, [], true);
        return $this;
    }

    /**
     * @param array<array-key, mixed> $customs
     * @param list<string> $prefix
     */
    private function presetsToFlat(array $customs, array $prefix, bool $isFirstLevel): void
    {
        foreach ($customs as $key => $value) {
            $fullKey = [...$prefix, (string) $key];

            if ($value instanceof CustomPreset) {
                if (!$isFirstLevel) {
                    throw new \InvalidArgumentException(
                        \sprintf(
                            'CustomPreset "%s" at key "%s" is supported only at the first level.',
                            $value->slug(),
                            $this->formatKey($fullKey)
                        )
                    );
                }

                $this->settings->addPreset($value);
                continue;
            }

            if (\is_array($value)) {
                $this->presetsToFlat($value, $fullKey, false);
                continue;
            }

            $this->settings->addPreset(new CustomPreset($fullKey, $value));
        }
    }

    /**
     * @param list<string> $segments
     */
    private function formatKey(array $segments): string
    {
        return \implode('.', $segments);
    }
}
