<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Styles;

use ItalyStrap\ThemeJsonGenerator\Domain\Settings\CollectionInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\NullCollection;

trait CommonTrait
{
    /**
     * @var array<string, string>
     */
    private array $properties = [];

    private ?CollectionInterface $collection;

    public function __construct(?CollectionInterface $collection = null)
    {
        $this->collection = $collection ?? new NullCollection();
    }

    /**
     * Example:
     * $this->property('fontFamily', 'fontFamily.base') === ['fontFamily' => 'var(--wp--preset--font-family--base)']
     * $this->property('fontSize', '20px') === ['fontSize' => '20px']
     *
     * Explanation:
     * If the $value `fontFamily.base` is found in the collection (because $value is treated as a key of the collection),
     * then the CSS variable found in the collection will be returned.
     *
     * If the $value `20px` is not found in the collection (because $value is treated as a key of the collection),
     * then the value 20px will be returned as is.
     */
    public function property(string $property, string $value): self
    {
        $this->setProperty($property, $value);
        return $this;
    }

    private function setProperty(string $key, string $value): self
    {
        $this->assertIsImmutable($key);
        $this->properties[$key] = $this->collection->value($value, $value);
        return $this;
    }

    private function assertIsImmutable(string $key): void
    {
        if (\array_key_exists($key, $this->properties)) {
            $bt = \debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

            $bt[1] ??= [];

            if (empty($bt[1])) {
                $bt[1] = ['file' => '', 'line' => ''];
            }

            throw new \RuntimeException(\sprintf(
                'The key "%s" is already provided | File:  %s | Line: %s',
                $key,
                $bt[1]['file'] ?? '',
                $bt[1]['line'] ?? ''
            ));
        }
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
        $result = \array_filter($this->properties, static fn($value): bool => $value !== '' && $value !== null);
        $this->properties = [];
        return $result;
    }

    /**
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
