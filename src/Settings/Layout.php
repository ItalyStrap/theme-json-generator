<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;

final readonly class Layout
{
    use ScopedSettingsWriterTrait;

    private const SECTION = 'layout';

    public const ALLOW_CUSTOM_CONTENT_AND_WIDE_SIZE = 'allowCustomContentAndWideSize';

    public const ALLOW_EDITING = 'allowEditing';

    public const CONTENT_SIZE = 'contentSize';

    public const WIDE_SIZE = 'wideSize';

    public function __construct(
        private Settings $settings,
        private PresetsInterface $presets,
    ) {
    }

    #[ThemeSchemaCoverage(['settings', 'layout', 'contentSize'])]
    public function contentSize(string $keyOrValue): self
    {
        $this->set(self::CONTENT_SIZE, $this->resolveSize($keyOrValue));
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'layout', 'wideSize'])]
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

    #[ThemeSchemaCoverage(['settings', 'layout', 'allowEditing'])]
    public function enableEditing(): self
    {
        return $this->setBoolean(self::ALLOW_EDITING, true);
    }

    #[ThemeSchemaCoverage(['settings', 'layout', 'allowEditing'])]
    public function disableEditing(): self
    {
        return $this->setBoolean(self::ALLOW_EDITING, false);
    }

    #[ThemeSchemaCoverage(['settings', 'layout', 'allowCustomContentAndWideSize'])]
    public function enableCustomContentAndWideSize(): self
    {
        return $this->setBoolean(self::ALLOW_CUSTOM_CONTENT_AND_WIDE_SIZE, true);
    }

    #[ThemeSchemaCoverage(['settings', 'layout', 'allowCustomContentAndWideSize'])]
    public function disableCustomContentAndWideSize(): self
    {
        return $this->setBoolean(self::ALLOW_CUSTOM_CONTENT_AND_WIDE_SIZE, false);
    }
}
