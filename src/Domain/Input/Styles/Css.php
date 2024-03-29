<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles;

use ItalyStrap\Tests\Unit\Domain\Input\Styles\CssTest;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\NullPresets;

/**
 * @link https://make.wordpress.org/core/2023/03/06/custom-css-for-global-styles-and-per-block/
 * @link https://fullsiteediting.com/lessons/how-to-use-custom-css-in-theme-json/
 * @link https://developer.wordpress.org/news/2023/04/21/per-block-css-with-theme-json/
 * @link https://github.com/WordPress/wordpress-develop/blob/trunk/tests/phpunit/tests/theme/wpThemeJson.php
 * @link https://developer.wordpress.org/themes/global-settings-and-styles/
 *
 * @link https://www.google.it/search?q=php+inline+css+content&sca_esv=596560865&ei=mAicZaTCGp3Axc8Pq7yT8AQ&ved=0ahUKEwik7p-Rgs6DAxUdYPEDHSveBE4Q4dUDCBA&uact=5&oq=php+inline+css+content&gs_lp=Egxnd3Mtd2l6LXNlcnAiFnBocCBpbmxpbmUgY3NzIGNvbnRlbnQyBRAhGKABMgUQIRigATIIECEYFhgeGB0yCBAhGBYYHhgdMggQIRgWGB4YHUjvogFQmgdYwJcBcAF4AZABAJgBsQGgAZkSqgEEMC4xOLgBA8gBAPgBAcICChAAGEcY1gQYsAPCAgoQABiABBiKBRhDwgIFEAAYgATCAgYQABgWGB7CAgcQABiABBgTwgIIEAAYFhgeGBPiAwQYACBBiAYBkAYI&sclient=gws-wiz-serp#ip=1
 * @link https://github.com/topics/inline-css?l=php
 * @link https://github.com/sabberworm/PHP-CSS-Parser
 *
 * @psalm-api
 * @see CssTest
 */
class Css
{
    private PresetsInterface $presets;

    public function __construct(
        PresetsInterface $presets = null
    ) {
        $this->presets = $presets ?? new NullPresets();
    }

    public function parseString(string $css, string $selector = ''): string
    {
        $css = $this->presets->parse($css);

        if ($selector === '') {
            return $css;
        }

        $exploded = \explode($selector, $css);

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
