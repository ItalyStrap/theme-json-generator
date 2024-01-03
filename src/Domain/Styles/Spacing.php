<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Styles;

use function array_replace;
use function implode;

/**
 * @psalm-api
 */
final class Spacing implements ArrayableInterface, \JsonSerializable
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
        return $this->setProperty(self::TOP, $value);
    }

    public function right(string $value): self
    {
        return $this->setProperty(self::RIGHT, $value);
    }

    public function bottom(string $value): self
    {
        return $this->setProperty(self::BOTTOM, $value);
    }

    public function left(string $value): self
    {
        return $this->setProperty(self::LEFT, $value);
    }

    /**
     * @todo maybe https://3v4l.org/4ia5Dt#v7.4.24
     *
     * This is not a ["t-o-d-o"] but only a reference https://3v4l.org/EtCFE
     * One value => 0px => 0px 0px 0px 0px
     * Two values => 5px 0px => 5px 0px 5px 0px
     * Three values => 10px auto 0px => 10px auto 0px auto
     * Four values => 1px 2px 3px 4px => 1px 2px 3px 4px
     */
    public function shorthand(array $values): self
    {
        switch (\count($values)) {
            case 1:
                return $this->setProperty(self::TOP, (string)$values[0])
                    ->setProperty(self::RIGHT, (string)$values[0])
                    ->setProperty(self::BOTTOM, (string)$values[0])
                    ->setProperty(self::LEFT, (string)$values[0]);
            case 2:
                return $this->setProperty(self::TOP, (string)$values[0])
                    ->setProperty(self::RIGHT, (string)$values[1])
                    ->setProperty(self::BOTTOM, (string)$values[0])
                    ->setProperty(self::LEFT, (string)$values[1]);
            case 3:
                return $this->setProperty(self::TOP, (string)$values[0])
                    ->setProperty(self::RIGHT, (string)$values[1])
                    ->setProperty(self::BOTTOM, (string)$values[2])
                    ->setProperty(self::LEFT, (string)$values[1]);
            case 4:
                return $this->setProperty(self::TOP, (string)$values[0])
                    ->setProperty(self::RIGHT, (string)$values[1])
                    ->setProperty(self::BOTTOM, (string)$values[2])
                    ->setProperty(self::LEFT, (string)$values[3]);
            default:
                throw new \InvalidArgumentException(\sprintf(
                    'The shorthand method accept only 1, 2, 3 or 4 values, %d given',
                    \count($values)
                ));
        }
    }

    public function vertical(string $value): self
    {
        return $this
            ->setProperty(self::TOP, $value)
            ->setProperty(self::BOTTOM, $value);
    }

    public function horizontal(string $value): self
    {
        return $this
            ->setProperty(self::RIGHT, $value)
            ->setProperty(self::LEFT, $value);
    }

    public function verticalAsync(string $top, string $bottom): self
    {
        return $this
            ->setProperty(self::TOP, $top)
            ->setProperty(self::BOTTOM, $bottom);
    }

    public function horizontalAsync(string $right, string $left): self
    {
        return $this
            ->setProperty(self::RIGHT, $right)
            ->setProperty(self::LEFT, $left);
    }
}
