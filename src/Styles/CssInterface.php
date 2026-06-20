<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

interface CssInterface
{
    public const M_AMPERSAND_MUST_NOT_BE_AT_THE_BEGINNING = 'CSS cannot begin with an ampersand (&)';

    public const M_AT_RULES_ARE_NOT_SUPPORTED_IN_SCOPED_CSS = 'Scoped custom CSS does not support %s '
        . 'because WordPress cannot preserve at-rules through WP_Theme_JSON.';

    public const M_NESTED_SELECTORS_MUST_BE_SCOPED = 'Nested scoped CSS selectors must begin with an ampersand (&).';

    public const M_SELECTOR_IS_OUTSIDE_SCOPE = 'Selector "%s" is outside the "%s" scope. '
        . 'Use "& %s" for a descendant selector or styles()->css() for global CSS.';

    public function expanded(): self;

    public function parse(string $css, string $selector = ''): string;
}
