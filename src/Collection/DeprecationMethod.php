<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Collection;

trait DeprecationMethod
{
    private function deprecatePreset()
    {
        \trigger_error(
            \sprintf(
                'Deprecated %s called. Use %s instead',
                self::class,
                \ItalyStrap\ThemeJsonGenerator\Settings\PresetCollection::class
            ),
            E_USER_DEPRECATED
        );
    }

    private function deprecateCustom()
    {
        \trigger_error(
            \sprintf(
                'Deprecated %s called. Use %s instead',
                self::class,
                \ItalyStrap\ThemeJsonGenerator\Settings\CustomCollection::class
            ),
            E_USER_DEPRECATED
        );
    }
}
