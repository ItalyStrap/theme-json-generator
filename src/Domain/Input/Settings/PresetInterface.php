<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings;

/**
 * @psalm-api
 */
// phpcs:ignore PHPCompatibility.Interfaces.NewInterfaces.stringableFound
interface PresetInterface extends \Stringable
{
    public function type(): string;

    public function slug(): string;

    public function ref(): string;

    public function prop(): string;

    public function var(string $fallback = ''): string;

    public function __toString(): string;

    /**
     * @return array<string, string|array|object>
     */
    public function toArray(): array;
}
