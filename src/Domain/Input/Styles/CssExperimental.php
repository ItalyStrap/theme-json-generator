<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles;

use ItalyStrap\Tests\Unit\Domain\Input\Styles\CssTest;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\NullPresets;

/**
 * @psalm-api
 * @see CssTest
 */
class CssExperimental
{
    private PresetsInterface $collection;

    public function __construct(
        PresetsInterface $collection = null
    ) {
        $this->collection = $collection ?? new NullPresets();
    }

    public function parseString(string $css, string $selector = ''): string
    {
        $css = $this->collection->parse($css);

        if ($selector === '') {
            return $css;
        }

        $exploded = (array)\explode($selector, $css);

        $rootRule = [];
        $explodedNew = [];
        foreach ($exploded as $key => $value) {
            // @todo remove after PHP >= 8
            // phpcs:disable
            if (\str_starts_with(\trim($value), '{')) {
                $value = \str_replace(['{', '}'], '', \rtrim($value));
                $value = \preg_replace('/^ +/m', '', $value);
                $rootRule[] = $value;
                continue;
            }
            // phpcs:enable

            $explodedNew[$key] = $value;
        }

        return \ltrim(\implode('', $rootRule) . \implode('&', $explodedNew), "\t\n\r\0\x0B&");
    }
}
