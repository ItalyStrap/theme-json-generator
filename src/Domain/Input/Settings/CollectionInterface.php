<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings;

/**
 * @psalm-api
 */
interface CollectionInterface
{
    public function add(ItemInterface $item): self;

    /**
     * @param ItemInterface[] $items
     */
    public function addMultiple(array $items): self;

    /**
     * @param mixed $default
     * @return array<string, mixed>|ItemInterface|mixed|null
     */
    public function get(string $key, $default = null);

    public function parse(string $content): string;
}
