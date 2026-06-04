<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Settings;

final readonly class Position
{
    public const STICKY = 'sticky';

    public function __construct(
        private Settings $settings,
    ) {
    }

    public function enableSticky(): self
    {
        $this->set(self::STICKY, true);
        return $this;
    }

    public function disableSticky(): self
    {
        $this->set(self::STICKY, false);
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

        return $this->settings->set(['position', ...$path], $value);
    }
}
