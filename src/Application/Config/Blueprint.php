<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Config;

use ItalyStrap\Config\Config;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\SectionNames;

/**
 * @psalm-api
 * @template TKey as array-key
 * @template TValue
 * @template-extends Config<TKey,TValue>
 * @psalm-suppress DeprecatedInterface
 */
final class Blueprint extends Config implements \JsonSerializable
{
    public function setGlobalCss(string $css): bool
    {
        return $this->set(SectionNames::STYLES . '.css', $css);
    }

    public function setElementStyle(string $elementName, array $config): bool
    {
        return $this->set(SectionNames::STYLES . '.elements.' . $elementName, $config);
    }

    public function setBlockSettings(string $blockName, array $config): bool
    {
        return $this->set(SectionNames::SETTINGS . '.blocks.' . $blockName, $config);
    }

    public function setBlockStyle(string $blockName, array $config): bool
    {
        return $this->set(SectionNames::STYLES . '.blocks.' . $blockName, $config);
    }

    public function setPerBLockCss(string $blockName, string $css): bool
    {
        return $this->set(SectionNames::STYLES . '.blocks.' . $blockName . '.css', $css);
    }

    public function jsonSerialize(): array
    {
        return $this->getArrayCopy();
    }
}
