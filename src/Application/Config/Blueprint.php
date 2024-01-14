<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Config;

use ItalyStrap\Config\Config;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\SectionNames;

/**
 * @psalm-api
 */
final class Blueprint extends Config implements \JsonSerializable
{
    public function setBlockStyle(string $blockName, array $config): bool
    {
        return $this->set(SectionNames::STYLES . '.blocks.' . $blockName, $config);
    }

    public function setElementStyle(string $elementName, array $config): bool
    {
        return $this->set(SectionNames::STYLES . '.elements.' . $elementName, $config);
    }

    /**
     * @return mixed
     */
    public function jsonSerialize(): array
    {
        return $this->getArrayCopy();
    }
}
