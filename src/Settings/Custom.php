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

    #[ThemeSchemaCoverage(['settings', 'custom'])]
    public function add(string $key, string $value): self
    {
        $this->settings->addPreset(new CustomPreset($key, $value));

        return $this;
    }

    /**
     * @param array<string, mixed> $customs
     */
    #[ThemeSchemaCoverage(['settings', 'custom'])]
    public function addMultiple(array $customs): self
    {
        $this->presetsToFlat($customs);
        return $this;
    }

    /**
     * @param array<string, mixed> $customs
     */
    private function presetsToFlat(array $customs, string $prefix = ''): void
    {
        /**
         * @var string|array<string, mixed>|\Stringable $value
         */
        foreach ($customs as $key => $value) {
            if ($value instanceof CustomPreset) {
                $this->settings->addPreset($value);
                continue;
            }

            $fullKey = (string)($prefix === '' ? $key : $prefix . '.' . $key);
            if (\is_array($value)) {
                $this->presetsToFlat($value, $fullKey);
                continue;
            }

            $this->add($fullKey, (string)$value);
        }
    }
}
