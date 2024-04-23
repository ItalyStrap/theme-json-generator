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
class Scss
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

    public function parse(string $scss, string $selector = ''): string
    {
        $this->compiler->setOutputStyle($this->outputStyle);

        $this->css->stopResolveVariables();
        $scss = $this->presets->parse($scss);

        $css = $this->compiler->compileString($scss);

        return $this->css->parse($css->getCss(), $selector);
    }
}
