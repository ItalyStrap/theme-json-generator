<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Spacing;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings\Spacing;

final readonly class Scale
{
    public function __construct(private Spacing $spacing)
    {
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'spacingScale', 'operator'])]
    public function operator(string $operator): self
    {
        if (!\in_array($operator, ['+', '*'], true)) {
            throw new \InvalidArgumentException(\sprintf('Expected "+" or "*", got "%s".', $operator));
        }

        return $this->set('operator', $operator);
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'spacingScale', 'increment'])]
    public function increment(float $increment): self
    {
        return $this->set('increment', $this->positive($increment, 'increment'));
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'spacingScale', 'steps'])]
    public function steps(int $steps): self
    {
        if ($steps < 1 || $steps > 10) {
            throw new \InvalidArgumentException(\sprintf('Expected steps between 1 and 10, got %d.', $steps));
        }

        return $this->set('steps', $steps);
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'spacingScale', 'mediumStep'])]
    public function mediumStep(float $mediumStep): self
    {
        return $this->set('mediumStep', $this->positive($mediumStep, 'medium step'));
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'spacingScale', 'unit'])]
    public function unit(string $unit): self
    {
        return $this->set('unit', $unit);
    }

    private function set(string $property, mixed $value): self
    {
        $this->spacing->writeScale($property, $value);
        return $this;
    }

    private function positive(float $value, string $name): float
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException(\sprintf('Expected a positive %s, got %s.', $name, $value));
        }

        return $value;
    }
}
