<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Collection;

/**
 * @deprecated
 */
interface CollectionInterface
{
    /**
     * @return string
     */
    public function category(): string;

    /**
     * @param string $slug
     * @return string
     */
    public function propOf(string $slug): string;

    /**
     * @param string $slug
     * @return string
     */
    public function varOf(string $slug): string;

    /**
     * @param string $slug
     * @return string
     */
    public function value(string $slug): string;

    /**
     * @return array<int|string, mixed>
     */
    public function toArray(): array;

    /**
     * @param \ItalyStrap\ThemeJsonGenerator\Settings\CollectionInterface ...$collections
     * @return void
     */
    public function withCollection(\ItalyStrap\ThemeJsonGenerator\Settings\CollectionInterface ...$collections): void;
}
