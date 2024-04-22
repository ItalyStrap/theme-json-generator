<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles;

use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\OutputStyle;

/**
 * @psalm-api
 */
class Scss
{
    private Css $css;
    private Compiler $compiler;

    /**
     * @var 'compressed'|'expanded'
     */
    private string $outputStyle = OutputStyle::COMPRESSED;

    public function __construct(
        Css $css,
        Compiler $compiler
    ) {
        $this->css = $css;
        $this->compiler = $compiler;
    }

    public function expanded(): self
    {
        $this->css->expanded();
        $this->outputStyle = OutputStyle::EXPANDED;
        return $this;
    }

    public function parse(string $scss, string $selector = ''): string
    {
        $this->compiler->setOutputStyle($this->outputStyle);

        $css = $this->compiler->compileString($scss);

        return $this->css->parse($css->getCss(), $selector);
    }
}
