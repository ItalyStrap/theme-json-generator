<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles;

use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\CollectionInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\NullCollection;

trait CommonTrait
{
    /**
     * @var array<string, string>
     */
    private array $properties;

    private ?CollectionInterface $collection;

    public function __construct(
        ?CollectionInterface $collection = null,
        array $properties = []
    ) {
        $this->collection = $collection ?? new NullCollection();
        $this->properties = $properties;
    }

    /**
     * Example:
     * $this->property('fontFamily', 'fontFamily.base') === ['fontFamily' => 'var(--wp--preset--font-family--base)']
     * $this->property('fontSize', '20px') === ['fontSize' => '20px']
     *
     * Explanation:
     * If the $value `fontFamily.base` is found in the collection
     * (because $value is treated as a key of the collection),
     * then the CSS variable found in the collection will be returned.
     *
     * If the $value `20px` is not found in the collection (because $value is treated as a key of the collection),
     * then the value 20px will be returned as is.
     */
    public function property(string $property, string $value): self
    {
        return $this->setProperty($property, $value);
    }

    private function setProperty(string $key, string $value): self
    {
        $this->properties[$key] = $this->collection->value($value, $value);
        $class = self::class;
        return new $class($this->collection, $this->properties);
    }

    final public function __clone()
    {
        $this->properties = [];
    }

    /**
     * @return array<array-key, string>
     */
    public function toArray(): array
    {
        $result = \array_filter($this->properties, static fn ($value): bool => $value !== '' && $value !== null);
        $this->properties = [];
        return $result;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
