<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Config;

use ItalyStrap\ThemeJsonGenerator\Domain\SectionNames;

final class Blueprint extends \ItalyStrap\Config\Config implements \JsonSerializable
{
    public function setBlockStyle(string $blockName, array $config): bool
    {
        return $this->set(SectionNames::STYLES . '.blocks.' . $blockName, $config);
    }

    /**
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return $this->getArrayCopy();
    }
}
