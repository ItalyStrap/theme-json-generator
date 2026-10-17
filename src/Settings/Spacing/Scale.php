<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Spacing;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Settings\Spacing;

final readonly class Scale
{
    public const SECTION = 'spacingScale';

    public const OPERATOR = 'operator';

    public const INCREMENT = 'increment';

    public const STEPS = 'steps';

    public const MEDIUM_STEP = 'mediumStep';

    public const UNIT = 'unit';

    public function __construct(private Spacing $spacing)
    {
    }

    #[ThemeSchemaCoverage([Settings::SECTION, Spacing::SECTION, self::SECTION, self::OPERATOR])]
    public function operator(string $operator): self
    {
        if (!\in_array($operator, ['+', '*'], true)) {
            throw new \InvalidArgumentException(\sprintf('Expected "+" or "*", got "%s".', $operator));
        }

        return $this->set(self::OPERATOR, $operator);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, Spacing::SECTION, self::SECTION, self::INCREMENT])]
    public function increment(float $increment): self
    {
        return $this->set(self::INCREMENT, $this->positive($increment, self::INCREMENT));
    }

    #[ThemeSchemaCoverage([Settings::SECTION, Spacing::SECTION, self::SECTION, self::STEPS])]
    public function steps(int $steps): self
    {
        if ($steps < 1 || $steps > 10) {
            throw new \InvalidArgumentException(\sprintf('Expected steps between 1 and 10, got %d.', $steps));
        }

        return $this->set(self::STEPS, $steps);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, Spacing::SECTION, self::SECTION, self::MEDIUM_STEP])]
    public function mediumStep(float $mediumStep): self
    {
        return $this->set(self::MEDIUM_STEP, $this->positive($mediumStep, 'medium step'));
    }

    #[ThemeSchemaCoverage([Settings::SECTION, Spacing::SECTION, self::SECTION, self::UNIT])]
    public function unit(string $unit): self
    {
        return $this->set(self::UNIT, $unit);
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
