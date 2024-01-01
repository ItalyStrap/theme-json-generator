<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Config;

class Blueprint extends \ItalyStrap\Config\Config implements \JsonSerializable
{
    /**
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->getIterator();
    }
}
