<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Container;

use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Border\RadiusSize;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Duotone;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Gradient;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Palette;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Shadow;
use ItalyStrap\ThemeJsonGenerator\Settings\Custom\Custom;
use ItalyStrap\ThemeJsonGenerator\Settings\Dimensions\AspectRatio;
use ItalyStrap\ThemeJsonGenerator\Settings\Dimensions\DimensionSize;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Spacing\SpacingSize;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\FontFamily;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\FontSize;

final class PresetsToThemeJson
{
    /**
     * @var array<string, string>
     */
    private const ROOT_PATH_BY_TYPE = [
        RadiusSize::TYPE => 'settings.border.radiusSizes',
        Palette::TYPE => 'settings.color.palette',
        Gradient::TYPE => 'settings.color.gradients',
        Duotone::TYPE => 'settings.color.duotone',
        AspectRatio::TYPE => 'settings.dimensions.aspectRatios',
        DimensionSize::TYPE => 'settings.dimensions.dimensionSizes',
        Shadow::TYPE => 'settings.shadow.presets',
        SpacingSize::TYPE => 'settings.spacing.spacingSizes',
        FontSize::TYPE => 'settings.typography.fontSizes',
        FontFamily::TYPE => 'settings.typography.fontFamilies',
        Custom::TYPE => 'settings.custom',
    ];

    /**
     * @var array<string, list<string>>
     */
    private const SETTINGS_PATH_BY_TYPE = [
        AspectRatio::TYPE => ['dimensions', 'aspectRatios'],
        RadiusSize::TYPE => ['border', 'radiusSizes'],
        Palette::TYPE => ['color', 'palette'],
        Custom::TYPE => ['custom'],
        DimensionSize::TYPE => ['dimensions', 'dimensionSizes'],
        Duotone::TYPE => ['color', 'duotone'],
        FontFamily::TYPE => ['typography', 'fontFamilies'],
        FontSize::TYPE => ['typography', 'fontSizes'],
        Gradient::TYPE => ['color', 'gradients'],
        Shadow::TYPE => ['shadow', 'presets'],
        SpacingSize::TYPE => ['spacing', 'spacingSizes'],
    ];

    /**
     * @param ConfigInterface<array-key, mixed> $config
     */
    public function __invoke(ConfigInterface $config, PresetsInterface $presets): void
    {
        $this->setRootPresets($config, $presets);
        $this->setBlockPresets($config, $presets);
    }

    /**
     * @param ConfigInterface<array-key, mixed> $config
     */
    private function setRootPresets(ConfigInterface $config, PresetsInterface $presets): void
    {
        $collection = $presets->collection();

        foreach (self::ROOT_PATH_BY_TYPE as $type => $path) {
            $items = $collection[$type] ?? null;
            if (!\is_array($items)) {
                continue;
            }

            $config->set($path, $this->serializeCollection($presets, $type, $items));
        }
    }

    /**
     * @param ConfigInterface<array-key, mixed> $config
     */
    private function setBlockPresets(ConfigInterface $config, PresetsInterface $presets): void
    {
        $blocks = $presets->collection()['blocks'] ?? [];
        if (!\is_array($blocks)) {
            return;
        }

        /**
         * @var array<string, array<string, array<array-key, mixed>>> $blocks
         */
        foreach ($blocks as $block => $categories) {
            foreach ($categories as $type => $items) {
                $config->set(
                    $this->blockSettingsPath($block, $type),
                    $this->serializeCollection($presets, $type, $items)
                );
            }
        }
    }

    /**
     * @param array<array-key, mixed> $collection
     * @return array<array-key, mixed>
     */
    private function serializeCollection(PresetsInterface $presets, string $type, array $collection): array
    {
        if ($type === Custom::TYPE) {
            return $this->processCustomCollection($presets, $collection);
        }

        return $this->processPresetCollection($presets, ...$this->presetLeaves($collection));
    }

    /**
     * @param array<array-key, mixed> $collection
     * @return list<PresetInterface>
     */
    private function presetLeaves(array $collection): array
    {
        $presets = [];
        foreach ($collection as $value) {
            if ($value instanceof PresetInterface) {
                $presets[] = $value;
                continue;
            }

            if (\is_array($value)) {
                $presets = [...$presets, ...$this->presetLeaves($value)];
            }
        }

        return $presets;
    }

    /**
     * @param PresetInterface ...$collection
     * @return array<int, array<string, mixed>>
     */
    private function processPresetCollection(PresetsInterface $presets, PresetInterface ...$collection): array
    {
        return \array_values(\array_map(
            static function (PresetInterface $item) use ($presets): array {
                $newItems = [];
                foreach ($item->toArray() as $key => $value) {
                    if (\is_string($value)) {
                        $value = $presets->parse($value);
                    }

                    $newItems[$key] = $value;
                }

                return $newItems;
            },
            $collection
        ));
    }

    /**
     * @param array<array-key, mixed> $collection
     * @return array<array-key, mixed>
     */
    private function processCustomCollection(PresetsInterface $presets, array $collection): array
    {
        $processed = [];
        /** @var array<array-key, mixed>|PresetInterface $value */
        foreach ($collection as $key => $value) {
            if (\is_array($value)) {
                $processed[$key] = $this->processCustomCollection($presets, $value);
                continue;
            }

            $processed[$key] = $presets->parse((string)$value);
        }

        return $processed;
    }

    private function blockSettingsPath(string $block, string $type): string
    {
        $path = self::SETTINGS_PATH_BY_TYPE[$type] ?? [$type];

        return \implode('.', ['settings', 'blocks', $block, ...$path]);
    }
}
