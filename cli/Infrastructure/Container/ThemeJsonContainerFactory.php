<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Container;

use Auryn\Injector;
use ItalyStrap\Empress\ContainerBuilder;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\ThemeJsonContainerFactoryInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Border\RadiusSize;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Duotone;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Gradient;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Palette;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Shadow;
use ItalyStrap\ThemeJsonGenerator\Settings\Custom\Custom;
use ItalyStrap\ThemeJsonGenerator\Settings\Dimensions\AspectRatio;
use ItalyStrap\ThemeJsonGenerator\Settings\Dimensions\DimensionSize;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Spacing\SpacingSize;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\FontFamily;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\FontSize;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;
use Psr\Container\ContainerInterface;

final class ThemeJsonContainerFactory implements ThemeJsonContainerFactoryInterface
{
    private function create(): ContainerInterface
    {
        return (new ContainerBuilder())
            ->addModule(new ThemeJsonModule())
            ->build();
    }

    public function execute(callable $entrypoint): ThemeJson
    {
        $container = $this->create();
        $injector = $container->get(Injector::class);

        /**
         * Injector resolves to null if a param is nullable,
         * so we need to be explicit and declare the param
         * I need this for all the classes under the Styles namespace
         */
        $injector->defineParam('presets', $injector->make(PresetsInterface::class));

        $injector->execute($entrypoint);

        /**
         * @TODO Add an empty schema to the ThemeJson class
         */
        $themeJson = $container->get(ThemeJson::class);
        $this->setPresets($themeJson, $container->get(PresetsInterface::class));

        return $themeJson;
    }

    private function setPresets(ThemeJson $themeJson, PresetsInterface $presets): void
    {
        $keys = [
            'settings.border.radiusSizes' => RadiusSize::TYPE,
            'settings.color.palette' => Palette::TYPE,
            'settings.color.gradients' => Gradient::TYPE,
            'settings.color.duotone' => Duotone::TYPE,
            'settings.dimensions.aspectRatios' => AspectRatio::TYPE,
            'settings.dimensions.dimensionSizes' => DimensionSize::TYPE,
            'settings.shadow.presets' => Shadow::TYPE,
            'settings.spacing.spacingSizes' => SpacingSize::TYPE,
            'settings.typography.fontSizes' => FontSize::TYPE,
            'settings.typography.fontFamilies' => FontFamily::TYPE,
            'settings.custom' => Custom::TYPE,
        ];

        foreach ($keys as $key => $value) {
            try {
                $themeJson->set($key, $presets->toArrayByCategory($value));
            } catch (\Exception) {
                continue;
            }
        }

        foreach ($presets->toArraysByPath() as $path => $collection) {
            $themeJson->set($path, $collection);
        }
    }
}
