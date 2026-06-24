<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Styles;

final readonly class BoxSpacing
{
    public function __construct(
        private Spacing $box,
    ) {
    }

    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::MARGIN, Spacing::TOP])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::PADDING, Spacing::TOP])]
    public function top(string $value): self
    {
        return $this->setProperty(Spacing::TOP, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::MARGIN, Spacing::RIGHT])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::PADDING, Spacing::RIGHT])]
    public function right(string $value): self
    {
        return $this->setProperty(Spacing::RIGHT, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::MARGIN, Spacing::BOTTOM])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::PADDING, Spacing::BOTTOM])]
    public function bottom(string $value): self
    {
        return $this->setProperty(Spacing::BOTTOM, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::MARGIN, Spacing::LEFT])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::PADDING, Spacing::LEFT])]
    public function left(string $value): self
    {
        return $this->setProperty(Spacing::LEFT, $value);
    }

    /**
     * @param string[] $values
     */
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::MARGIN, Spacing::TOP])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::MARGIN, Spacing::RIGHT])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::MARGIN, Spacing::BOTTOM])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::MARGIN, Spacing::LEFT])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::PADDING, Spacing::TOP])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::PADDING, Spacing::RIGHT])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::PADDING, Spacing::BOTTOM])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::PADDING, Spacing::LEFT])]
    public function shorthand(array $values): self
    {
        return match (\count($values)) {
            1 => $this->top((string)$values[0])
                ->right((string)$values[0])
                ->bottom((string)$values[0])
                ->left((string)$values[0]),
            2 => $this->top((string)$values[0])
                ->right((string)$values[1])
                ->bottom((string)$values[0])
                ->left((string)$values[1]),
            3 => $this->top((string)$values[0])
                ->right((string)$values[1])
                ->bottom((string)$values[2])
                ->left((string)$values[1]),
            4 => $this->top((string)$values[0])
                ->right((string)$values[1])
                ->bottom((string)$values[2])
                ->left((string)$values[3]),
            default => throw new \InvalidArgumentException(\sprintf(
                'The shorthand method accept only 1, 2, 3 or 4 values, %d given',
                \count($values)
            )),
        };
    }

    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::MARGIN, Spacing::TOP])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::MARGIN, Spacing::BOTTOM])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::PADDING, Spacing::TOP])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::PADDING, Spacing::BOTTOM])]
    public function vertical(string $value): self
    {
        return $this
            ->top($value)
            ->bottom($value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::MARGIN, Spacing::RIGHT])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::MARGIN, Spacing::LEFT])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::PADDING, Spacing::RIGHT])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::PADDING, Spacing::LEFT])]
    public function horizontal(string $value): self
    {
        return $this
            ->right($value)
            ->left($value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::MARGIN, Spacing::TOP])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::MARGIN, Spacing::BOTTOM])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::PADDING, Spacing::TOP])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::PADDING, Spacing::BOTTOM])]
    public function verticalAsync(string $top, string $bottom): self
    {
        return $this
            ->top($top)
            ->bottom($bottom);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::MARGIN, Spacing::RIGHT])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::MARGIN, Spacing::LEFT])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::PADDING, Spacing::RIGHT])]
    #[ThemeSchemaCoverage([Styles::SECTION, Spacing::SECTION, Spacing::PADDING, Spacing::LEFT])]
    public function horizontalAsync(string $right, string $left): self
    {
        return $this
            ->right($right)
            ->left($left);
    }

    private function setProperty(string $property, string $value): self
    {
        $this->box->property($property, $value);

        return $this;
    }
}
