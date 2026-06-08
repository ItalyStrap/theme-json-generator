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
        $size = $this->presets->get($keyOrValue);
        if ($size instanceof PresetInterface) {
            $size = $size->var();
        }

        if (\is_null($size)) {
            $size = $keyOrValue;
        }

        $this->set(self::CONTENT_SIZE, $size);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'layout', 'wideSize'])]
    public function wideSize(string $keyOrValue): self
    {
        $size = $this->presets->get($keyOrValue);
        if ($size instanceof PresetInterface) {
            $size = $size->var();
        }

        if (\is_null($size)) {
            $size = $keyOrValue;
        }

        $this->set(self::WIDE_SIZE, $size);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'layout', 'allowEditing'])]
    public function enableEditing(): self
    {
        $this->set(self::ALLOW_EDITING, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'layout', 'allowEditing'])]
    public function disableEditing(): self
    {
        $this->set(self::ALLOW_EDITING, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'layout', 'allowCustomContentAndWideSize'])]
    public function enableCustomContentAndWideSize(): self
    {
        $this->set(self::ALLOW_CUSTOM_CONTENT_AND_WIDE_SIZE, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'layout', 'allowCustomContentAndWideSize'])]
    public function disableCustomContentAndWideSize(): self
    {
        $this->set(self::ALLOW_CUSTOM_CONTENT_AND_WIDE_SIZE, false);
        return $this;
    }
}
