<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings;

/**
 * @psalm-api
 */
interface CollectionInterface
{
    public function add(ItemInterface $item): self;

    /**
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * @param mixed $default
     */
    public function value(string $key, $default = null): string;
}
