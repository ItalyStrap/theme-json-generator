<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use ItalyStrap\Config\Config;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Duotone;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Gradient;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Palette;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Shadow;
use ItalyStrap\ThemeJsonGenerator\Settings\Custom\Custom;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\FontFamily;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\FontSize;

/**
 * @extends Config<array-key, mixed>
 */
final class ThemeJson extends Config
{
    public function setGlobalCss(string $css): bool
    {
        return $this->set([SectionNames::STYLES, 'css'], $css);
    }

    public function appendGlobalCss(string $css): bool
    {
        $currentCss = $this->get([SectionNames::STYLES, 'css']);
        $currentCss = \is_string($currentCss) ? $currentCss : '';

        return $this->set([SectionNames::STYLES, 'css'], $currentCss . $css);
    }

    /**
     * @param array<string, mixed> $config
     */
    public function setElementStyle(string $elementName, array $config): bool
    {
        return $this->set([SectionNames::STYLES, 'elements', $elementName], $config);
    }

    /**
     * @param array<string, mixed> $config
     */
    public function setBlockSettings(string $blockName, array $config): bool
    {
        return $this->set([SectionNames::SETTINGS, 'blocks', $blockName], $config);
    }

    /**
     * @param array<string, mixed> $config
     */
    public function setBlockStyle(string $blockName, array $config): bool
    {
        return $this->set([SectionNames::STYLES, 'blocks', $blockName], $config);
    }

    public function setPerBLockCss(string $blockName, string $css): bool
    {
        return $this->set([SectionNames::STYLES, 'blocks', $blockName, 'css'], $css);
    }

    /**
     * @internal
     */
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
                $this->set($key, $presets->toArrayByCategory($value));
            } catch (\Exception) {
                continue;
            }
        }

        return true;
    }
}
