<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Benchmarks\Domain\Input\Styles;

use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\CommonTrait;

class StyleBench
{
    private object $styles;

    public function init(): void
    {
        $this->styles = new class {
            use CommonTrait;
        };
    }

    /**
     * @Revs(10000)
     * @Iterations(5)
     * @BeforeMethods({"init"})
     */
    public function benchProperty(): void
    {
        $this->styles
            ->property('fontFamily', 'fontFamily.base')
            ->property('fontSize', '20px')
            ->property('fontSize', '20px')
            ->property('fontSize', '20px')
            ->property('fontSize', '20px')
            ->property('fontSize', '20px')
            ->property('fontSize', '20px')
            ->property('fontSize', '20px');

        \json_encode($this->styles);
    }
}
