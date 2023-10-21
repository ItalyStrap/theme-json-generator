<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Helper;

use function preg_replace;
use function strtolower;

trait ConvertCase
{
    /**
     * @link https://stackoverflow.com/a/40514305/7486194
     * @param string $string
     * @param string $us
     * @return string
     */
    private function camelToUnderscore(string $string, string $us = '-'): string
    {
        return strtolower((string)preg_replace(
            '/(?<=\d)(?=[A-Za-z])|(?<=[A-Za-z])(?=\d)|(?<=[a-z])(?=[A-Z])/',
            $us,
            $string
        ));
    }
}
