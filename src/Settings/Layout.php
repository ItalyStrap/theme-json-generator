<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;

final readonly class Layout
{
    use ScopedSettingsWriterTrait;

    public const SECTION = 'layout';

    public const ALLOW_CUSTOM_CONTENT_AND_WIDE_SIZE = 'allowCustomContentAndWideSize';

    public const ALLOW_EDITING = 'allowEditing';

    public const CONTENT_SIZE = 'contentSize';

    public const WIDE_SIZE = 'wideSize';

    public function __construct(
        private Settings $settings,
        private PresetsInterface $presets,
    ) {
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::CONTENT_SIZE])]
    public function contentSize(string $keyOrValue): self
    {
        $this->set(self::CONTENT_SIZE, $this->resolveSize($keyOrValue));
        return $this;
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::WIDE_SIZE])]
    public function wideSize(string $keyOrValue): self
    {
        $this->set(self::WIDE_SIZE, $this->resolveSize($keyOrValue));
        return $this;
    }

    private function resolveSize(string $keyOrValue): mixed
    {
        $size = $this->presets->get($keyOrValue);
        if ($size instanceof PresetInterface) {
            return $size->var();
        }

        if ($size === null) {
            return $keyOrValue;
        }

        return $size;
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::ALLOW_EDITING])]
    public function enableEditing(): self
    {
        return $this->setBoolean(self::ALLOW_EDITING, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::ALLOW_EDITING])]
    public function disableEditing(): self
    {
        return $this->setBoolean(self::ALLOW_EDITING, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::ALLOW_CUSTOM_CONTENT_AND_WIDE_SIZE])]
    public function enableCustomContentAndWideSize(): self
    {
        return $this->setBoolean(self::ALLOW_CUSTOM_CONTENT_AND_WIDE_SIZE, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::ALLOW_CUSTOM_CONTENT_AND_WIDE_SIZE])]
    public function disableCustomContentAndWideSize(): self
    {
        return $this->setBoolean(self::ALLOW_CUSTOM_CONTENT_AND_WIDE_SIZE, false);
    }
}
