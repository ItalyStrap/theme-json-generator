<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Styles;

use function array_replace;
use function implode;

/**
 * @psalm-api
 */
final class Spacing implements ArrayableInterface
{
    use CommonTrait;

    /**
     * @var string
     */
    public const TOP = 'top';

    /**
     * @var string
     */
    public const RIGHT = 'right';

    /**
     * @var string
     */
    public const BOTTOM = 'bottom';

    /**
     * @var string
     */
    public const LEFT = 'left';

    public function top(string $value): self
    {
        $this->setProperty(self::TOP, $value);
        return $this;
    }

    public function right(string $value): self
    {
        $this->setProperty(self::RIGHT, $value);
        return $this;
    }

    public function bottom(string $value): self
    {
        $this->setProperty(self::BOTTOM, $value);
        return $this;
    }

    public function left(string $value): self
    {
        $this->setProperty(self::LEFT, $value);
        return $this;
    }

    /**
     * @todo Test in case a value is 0: ['0'] because 0 is a valid css value
     *       (new Spacing())->shorthand(['0']) this print an empty array
     *
     * @todo https://3v4l.org/4ia5Dt#v7.4.24
     *  This is not a todo but only a reference https://3v4l.org/EtCFE
     *  One value => 0px => 0px 0px 0px 0px
     *  Two values => 5px 0px => 5px 0px 5px 0px
     *  Three values => 10px auto 0px => 10px auto 0px auto
     *  Four values => 1px 2px 3px 4px => 1px 2px 3px 4px
     *
     */
    public function shorthand(array $values): self
    {
        switch (\count($values)) {
            case 1:
                $this->setProperty(self::TOP, (string)$values[0]);
                $this->setProperty(self::RIGHT, (string)$values[0]);
                $this->setProperty(self::BOTTOM, (string)$values[0]);
                $this->setProperty(self::LEFT, (string)$values[0]);
                break;
            case 2:
                $this->setProperty(self::TOP, (string)$values[0]);
                $this->setProperty(self::RIGHT, (string)$values[1]);
                $this->setProperty(self::BOTTOM, (string)$values[0]);
                $this->setProperty(self::LEFT, (string)$values[1]);
                break;
            case 3:
                $this->setProperty(self::TOP, (string)$values[0]);
                $this->setProperty(self::RIGHT, (string)$values[1]);
                $this->setProperty(self::BOTTOM, (string)$values[2]);
                $this->setProperty(self::LEFT, (string)$values[1]);
                break;
            case 4:
                $this->setProperty(self::TOP, (string)$values[0]);
                $this->setProperty(self::RIGHT, (string)$values[1]);
                $this->setProperty(self::BOTTOM, (string)$values[2]);
                $this->setProperty(self::LEFT, (string)$values[3]);
                break;
        }

        return $this;
    }

    public function vertical(string $value): self
    {
        $this->setProperty(self::TOP, $value);
        $this->setProperty(self::BOTTOM, $value);
        return $this;
    }

    public function horizontal(string $value): self
    {
        $this->setProperty(self::RIGHT, $value);
        $this->setProperty(self::LEFT, $value);
        return $this;
    }

    public function verticalAsync(string $top, string $bottom): self
    {
        $this->setProperty(self::TOP, $top);
        $this->setProperty(self::BOTTOM, $bottom);
        return $this;
    }

    public function horizontalAsync(string $right, string $left): self
    {
        $this->setProperty(self::RIGHT, $right);
        $this->setProperty(self::LEFT, $left);
        return $this;
    }

    public function __toString(): string
    {

        $defaultProperties = [
            self::TOP       => '0',
            self::RIGHT     => '0',
            self::BOTTOM    => '0',
            self::LEFT      => '0',
        ];

        $properties = array_replace($defaultProperties, $this->properties);

        if ($this->assertPropertiesHasAllFourValuesAreEqual($properties)) {
            foreach ($properties as $unit) {
                return $unit;
            }
        }

        return implode(' ', $properties);
    }

    /**
     * @param array<string, string> $properties
     * @return bool
     */
    private function assertPropertiesHasAllFourValuesAreEqual(array $properties): bool
    {

        $propertiesFiltered = \array_map(static fn($value): ?string => \preg_replace('#\D#', '', $value), $properties);

        return \count(\array_unique($propertiesFiltered)) === 1 && \count($this->properties) === 4;
    }
}
