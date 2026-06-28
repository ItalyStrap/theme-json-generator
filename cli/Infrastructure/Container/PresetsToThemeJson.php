<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Container;

use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Settings\Border;
use ItalyStrap\ThemeJsonGenerator\Settings\Border\RadiusSize;
use ItalyStrap\ThemeJsonGenerator\Settings\Color;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Color as ColorPreset;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Duotone;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Gradient;
use ItalyStrap\ThemeJsonGenerator\Settings\Custom\Custom;
use ItalyStrap\ThemeJsonGenerator\Settings\Dimensions;
use ItalyStrap\ThemeJsonGenerator\Settings\Dimensions\AspectRatio;
use ItalyStrap\ThemeJsonGenerator\Settings\Dimensions\DimensionSize;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Shadow\Shadow;
use ItalyStrap\ThemeJsonGenerator\Settings\Spacing;
use ItalyStrap\ThemeJsonGenerator\Settings\Spacing\SpacingSize;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\FontFamily;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\FontSize;

final class PresetsToThemeJson
{
    /**
     * @var array<string, list<string>>
     */
    private const SETTINGS_PATH_BY_TYPE = [
        AspectRatio::TYPE => [Dimensions::SECTION, AspectRatio::SECTION],
        Custom::TYPE => [Settings\Custom::SECTION],
        RadiusSize::TYPE => [Border::SECTION, RadiusSize::SECTION],
        ColorPreset::TYPE => [Color::SECTION, ColorPreset::SECTION],
        Duotone::TYPE => [Color::SECTION, Duotone::SECTION],
        Gradient::TYPE => [Color::SECTION, Gradient::SECTION],
        DimensionSize::TYPE => [Dimensions::SECTION, DimensionSize::SECTION],
        Shadow::TYPE => [Settings\Shadow::SECTION, Shadow::SECTION],
        SpacingSize::TYPE => [Spacing::SECTION, SpacingSize::SECTION],
        FontFamily::TYPE => [Typography::SECTION, FontFamily::SECTION],
        FontSize::TYPE => [Typography::SECTION, FontSize::SECTION],
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
        foreach (self::SETTINGS_PATH_BY_TYPE as $type => $path) {
            $items = $presets->get($type);
            if (!\is_array($items)) {
                continue;
            }

            /** @phpstan-ignore-next-line Config accepts array paths, StoreInterface only advertises string keys. */
            $config->set([Settings::SECTION,  ...$path], $this->serializeCollection($presets, $type, $items));
        }
    }

    /**
     * @param ConfigInterface<array-key, mixed> $config
     */
    private function setBlockPresets(ConfigInterface $config, PresetsInterface $presets): void
    {
        $blocks = $presets->get(Settings::BLOCKS_SECTION, []);
        if (!\is_array($blocks)) {
            return;
        }

        /**
         * @var array<string, array<string, array<array-key, mixed>>> $blocks
         */
        foreach ($blocks as $block => $categories) {
            foreach ($categories as $type => $items) {
                $config->set(
                    /** @phpstan-ignore-next-line Config accepts array paths, StoreInterface only advertises string keys. */
                    [Settings::SECTION, Settings::BLOCKS_SECTION, $block, ...self::SETTINGS_PATH_BY_TYPE[$type]],
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
}
