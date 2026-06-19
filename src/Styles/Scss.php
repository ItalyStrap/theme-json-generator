<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use ItalyStrap\ThemeJsonGenerator\Settings\NullPresets;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\OutputStyle;

/**
 * @see ScssTest
 */
final class Scss implements CssInterface
{
    private readonly PresetsInterface $presets;

    /**
     * @var 'compressed'|'expanded'
     */
    private string $outputStyle = OutputStyle::EXPANDED;

    public function __construct(
        private readonly Css $css,
        private readonly Compiler $compiler,
        ?PresetsInterface $presets = null
    ) {
        $this->presets = $presets ?? new NullPresets();
    }

    public function compress(): self
    {
        $this->css->compressed();
        $this->outputStyle = OutputStyle::COMPRESSED;
        return $this;
    }

    public function expanded(): self
    {
        $this->css->expanded();
        $this->outputStyle = OutputStyle::EXPANDED;
        return $this;
    }

    public function parse(string $css, string $selector = ''): string
    {
        if (\str_starts_with(\trim($css), '&')) {
            throw new \RuntimeException(CssInterface::M_AMPERSAND_MUST_NOT_BE_AT_THE_BEGINNING);
        }

        $css = $this->presets->parse($css);

        $selector = \trim($selector);

        $this->compiler->setOutputStyle($this->outputStyle);
        $cssCompiled = $this->compiler->compileString($css);

        return $this->css->parseResolved($cssCompiled->getCss(), $selector);
    }
}
