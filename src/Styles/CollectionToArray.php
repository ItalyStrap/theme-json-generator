<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

trait CollectionToArray
{
    /**
     * @return array<array-key, string>
     */
    public function toArray(): array
    {
        return \array_filter($this->collection);
    }
}
