<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles;

use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\CollectionInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\ItemInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\NullCollection;

trait CommonTrait
{
    /**
     * @var array<string, string>
     */
    private array $properties;

    private CollectionInterface $collection;

    /**
     * @param array<string, string> $properties
     */
    public function __construct(
        CollectionInterface $collection = null,
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

    /**
     * This method is meant to be used only in the Styles Section or the Theme.json file
     * that because inside the styles section you only need to get a css variable created in the
     * Settings section.
     *
     * Now obviously if you need to pass other CSS accepted values you can also use them
     * and because they are not keys of the collection they will be returned as is.
     *
     * In short:
     *
     * If you pass a key of the collection you will get the value of the item.
     * Example:
     * Collection::get('color.base') === 'var(--wp--preset--color--base)'
     *
     * If you pass a CSS value you will get the same value (because all CSS value are not keys of the collection)
     * Example:
     * Collection::get('nonExistentKey', 'inherit') === 'inherit'
     */
    private function setProperty(string $key, string $value): self
    {
        /**
         * @var ItemInterface|mixed $value
         */
        $value = $this->collection->get($value, $value);

        if ($value instanceof ItemInterface) {
            $value = $value->var();
        }

        /**
         * This prevents to return a string with the placeholder like this:
         * {{color.base}}
         * instead we want to return the value of the placeholder like this:
         * var(--wp--preset--color--base)
         */
        $this->properties[$key] =  $this->collection->parse((string)$value);

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
        $result = \array_filter($this->properties, static fn ($value): bool => $value !== '');
        $this->properties = [];
        return $result;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
