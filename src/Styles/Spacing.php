<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

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

    /**
     * @var string
     */
    public const BLOCK_GAP = 'blockGap';

    /**
     * @var string
     */
    public const MARGIN = 'margin';

    /**
     * @var string
     */
    public const PADDING = 'padding';

    public function blockGap(string $value): self
    {
        return $this->setProperty(self::BLOCK_GAP, $value);
    }

    public function margin(): BoxSpacing
    {
        return new BoxSpacing($this, $this->at(self::MARGIN));
    }

    public function padding(): BoxSpacing
    {
        return new BoxSpacing($this, $this->at(self::PADDING));
    }

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
     * @param string[] $values
     */
    public function shorthand(array $values): self
    {
        return match (\count($values)) {
            1 => $this->setProperty(self::TOP, (string)$values[0])
                ->setProperty(self::RIGHT, (string)$values[0])
                ->setProperty(self::BOTTOM, (string)$values[0])
                ->setProperty(self::LEFT, (string)$values[0]),
            2 => $this->setProperty(self::TOP, (string)$values[0])
                ->setProperty(self::RIGHT, (string)$values[1])
                ->setProperty(self::BOTTOM, (string)$values[0])
                ->setProperty(self::LEFT, (string)$values[1]),
            3 => $this->setProperty(self::TOP, (string)$values[0])
                ->setProperty(self::RIGHT, (string)$values[1])
                ->setProperty(self::BOTTOM, (string)$values[2])
                ->setProperty(self::LEFT, (string)$values[1]),
            4 => $this->setProperty(self::TOP, (string)$values[0])
                ->setProperty(self::RIGHT, (string)$values[1])
                ->setProperty(self::BOTTOM, (string)$values[2])
                ->setProperty(self::LEFT, (string)$values[3]),
            default => throw new \InvalidArgumentException(\sprintf(
                'The shorthand method accept only 1, 2, 3 or 4 values, %d given',
                \count($values)
            )),
        };
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
