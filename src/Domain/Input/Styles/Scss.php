<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles;

use ItalyStrap\Tests\Unit\Domain\Input\Styles\ScssTest;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\NullPresets;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetsInterface;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\OutputStyle;

/**
 * @psalm-api
 * @see ScssTest
 */
class Scss implements CssInterface
{
    private Css $css;
    private Compiler $compiler;
    private PresetsInterface $presets;

    /**
     * @var 'compressed'|'expanded'
     */
    private string $outputStyle = OutputStyle::COMPRESSED;

    public function __construct(
        Css $css,
        Compiler $compiler,
        PresetsInterface $presets = null
    ) {
        $this->css = $css;
        $this->compiler = $compiler;
        $this->presets = $presets ?? new NullPresets();
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

        $this->css->stopResolveVariables();
        $css = $this->presets->parse($css);

        $selector = \trim($selector);

        if ($selector === '') {
            return $css;
        }

        $this->compiler->setOutputStyle($this->outputStyle);
        $cssCompiled = $this->compiler->compileString($css);

        return $this->css->parse($cssCompiled->getCss(), $selector);
    }
}
