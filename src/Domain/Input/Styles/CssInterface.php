<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles;

/**
 * @psalm-api
 */
interface CssInterface
{
    public const M_AMPERSAND_MUST_NOT_BE_AT_THE_BEGINNING = 'CSS cannot begin with an ampersand (&)';

    public function expanded(): self;

    public function parse(string $css, string $selector = ''): string;
}
