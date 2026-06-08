<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;

final readonly class BoxSpacing
{
    public function __construct(
        private Spacing $box,
    ) {
    }

    #[ThemeSchemaCoverage(['styles', 'spacing', 'margin', 'top'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'padding', 'top'])]
    public function top(string $value): self
    {
        $this->setProperty(Spacing::TOP, $value);

        return $this;
    }

    #[ThemeSchemaCoverage(['styles', 'spacing', 'margin', 'right'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'padding', 'right'])]
    public function right(string $value): self
    {
        $this->setProperty(Spacing::RIGHT, $value);

        return $this;
    }

    #[ThemeSchemaCoverage(['styles', 'spacing', 'margin', 'bottom'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'padding', 'bottom'])]
    public function bottom(string $value): self
    {
        $this->setProperty(Spacing::BOTTOM, $value);

        return $this;
    }

    #[ThemeSchemaCoverage(['styles', 'spacing', 'margin', 'left'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'padding', 'left'])]
    public function left(string $value): self
    {
        $this->setProperty(Spacing::LEFT, $value);

        return $this;
    }

    /**
     * @param string[] $values
     */
    #[ThemeSchemaCoverage(['styles', 'spacing', 'margin', 'top'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'margin', 'right'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'margin', 'bottom'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'margin', 'left'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'padding', 'top'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'padding', 'right'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'padding', 'bottom'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'padding', 'left'])]
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

    #[ThemeSchemaCoverage(['styles', 'spacing', 'margin', 'top'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'margin', 'bottom'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'padding', 'top'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'padding', 'bottom'])]
    public function vertical(string $value): self
    {
        return $this
            ->top($value)
            ->bottom($value);
    }

    #[ThemeSchemaCoverage(['styles', 'spacing', 'margin', 'right'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'margin', 'left'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'padding', 'right'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'padding', 'left'])]
    public function horizontal(string $value): self
    {
        return $this
            ->right($value)
            ->left($value);
    }

    #[ThemeSchemaCoverage(['styles', 'spacing', 'margin', 'top'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'margin', 'bottom'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'padding', 'top'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'padding', 'bottom'])]
    public function verticalAsync(string $top, string $bottom): self
    {
        return $this
            ->top($top)
            ->bottom($bottom);
    }

    #[ThemeSchemaCoverage(['styles', 'spacing', 'margin', 'right'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'margin', 'left'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'padding', 'right'])]
    #[ThemeSchemaCoverage(['styles', 'spacing', 'padding', 'left'])]
    public function horizontalAsync(string $right, string $left): self
    {
        return $this
            ->right($right)
            ->left($left);
    }

    private function setProperty(string $property, string $value): void
    {
        $this->box->property($property, $value);
    }
}
