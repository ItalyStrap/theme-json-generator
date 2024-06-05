<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Config;

use ItalyStrap\Config\Config;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\SectionNames;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Shadow;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Duotone;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Gradient;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Palette;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Custom\Custom;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography\FontFamily;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography\FontSize;

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

    public function appendGlobalCss(string $css): bool
    {
        $currentCss = (string)$this->get(SectionNames::STYLES . '.css');
        return $this->set(SectionNames::STYLES . '.css', $currentCss . $css);
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

    public function setPresets(PresetsInterface $presets): bool
    {
        $keys = [
            'settings.color.palette' => Palette::TYPE,
            'settings.color.gradients' => Gradient::TYPE,
            'settings.color.duotone' => Duotone::TYPE,
            'settings.shadow.presets' => Shadow::TYPE,
            'settings.typography.fontSizes' => FontSize::TYPE,
            'settings.typography.fontFamilies' => FontFamily::TYPE,
            'settings.custom' => Custom::TYPE,
        ];

        foreach ($keys as $key => $value) {
            try {
                /**  @psalm-suppress UndefinedInterfaceMethod */
                $this->set($key, $presets->toArrayByCategory($value));
            } catch (\Exception $e) {
                continue;
            }
        }

        return true;
    }

    public function jsonSerialize(): array
    {
        return $this->getArrayCopy();
    }
}
