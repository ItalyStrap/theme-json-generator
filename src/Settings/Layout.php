<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Settings;

final readonly class Layout
{
    public const ALLOW_CUSTOM_CONTENT_AND_WIDE_SIZE = 'allowCustomContentAndWideSize';

    public const ALLOW_EDITING = 'allowEditing';

    public const CONTENT_SIZE = 'contentSize';

    public const WIDE_SIZE = 'wideSize';

    public function __construct(
        private Settings $settings,
    ) {
    }

    public function contentSize(string $size): self
    {
        $this->set(self::CONTENT_SIZE, $size);
        return $this;
    }

    public function wideSize(string $size): self
    {
        $this->set(self::WIDE_SIZE, $size);
        return $this;
    }

    public function enableEditing(): self
    {
        $this->set(self::ALLOW_EDITING, true);
        return $this;
    }

    public function disableEditing(): self
    {
        $this->set(self::ALLOW_EDITING, false);
        return $this;
    }

    public function enableCustomContentAndWideSize(): self
    {
        $this->set(self::ALLOW_CUSTOM_CONTENT_AND_WIDE_SIZE, true);
        return $this;
    }

    public function disableCustomContentAndWideSize(): self
    {
        $this->set(self::ALLOW_CUSTOM_CONTENT_AND_WIDE_SIZE, false);
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

        return $this->settings->set(['layout', ...$path], $value);
    }
}
