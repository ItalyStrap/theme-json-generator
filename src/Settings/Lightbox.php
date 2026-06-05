<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;

final readonly class Lightbox
{
    public const ALLOW_EDITING = 'allowEditing';

    public const ENABLED = 'enabled';

    public function __construct(
        private Settings $settings,
    ) {
    }

    #[ThemeSchemaCoverage(['settings', 'lightbox', 'enabled'])]
    public function enable(): self
    {
        $this->set(self::ENABLED, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'lightbox', 'enabled'])]
    public function disable(): self
    {
        $this->set(self::ENABLED, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'lightbox', 'allowEditing'])]
    public function enableEditing(): self
    {
        $this->set(self::ALLOW_EDITING, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'lightbox', 'allowEditing'])]
    public function disableEditing(): self
    {
        $this->set(self::ALLOW_EDITING, false);
        return $this;
    }

    /**
     * @param array<array-key, string|int>|string $path
     */
    public function set(array|string $path, mixed $value): bool
    {
        if (\is_string($path)) {
            $path = \explode('.', $path);
        }

        return $this->settings->set(['lightbox', ...$path], $value);
    }
}
