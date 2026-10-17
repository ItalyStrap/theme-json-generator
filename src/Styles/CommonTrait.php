<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\StyleContext;

trait CommonTrait
{
    /**
     * @var array<string, string>
     */
    private array $properties;

    private PresetsInterface $presets;

    private StyleContext $context;

    /**
     * @param array<string, string> $properties
     */
    public function __construct(
        PresetsInterface $presets,
        array $properties,
        StyleContext $context,
    ) {
        $this->presets = $presets;
        $this->properties = $properties;
        $this->context = $context;
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
     * Now if you need to pass other CSS accepted values, you can also use them,
     * and because they are not keys of the collection, they will be returned as is.
     *
     * In short:
     *
     * If you pass a key of the collection, you will get the value of the item.
     * Example:
     * Collection::get('color.base') === 'var(--wp--preset--color--base)'
     *
     * If you pass a CSS value, you will get the same value (because all CSS values are not keys of the collection)
     * Example:
     * Collection::get('nonExistentKey', 'inherit') === 'inherit'
     *
     * @duplicated snippet
     * @see \ItalyStrap\ThemeJsonGenerator\Styles::parseStyleValue
     */
    private function setProperty(string $key, string $value): self
    {
        $value = $this->presets->get($value, $value);

        if ($value instanceof PresetInterface) {
            $value = $value->var();
        }

        if (!\is_scalar($value) && !$value instanceof \Stringable) {
            throw new \RuntimeException(\sprintf(
                'Expected style value to be stringable, got %s.',
                \get_debug_type($value)
            ));
        }

        /**
         * This prevents returning a string with the placeholder like this:
         * {{color.base}}
         * instead we want to return the value of the placeholder like this:
         * var(--wp--preset--color--base)
         */
        $value = $this->presets->parse((string)$value);
        $this->properties[$key] =  $value;

        $this->context->set($key, $value);

        $class = self::class;
        return new $class($this->presets, $this->properties, $this->context);
    }

    protected function at(string|int $segment): self
    {
        $class = self::class;

        return new $class($this->presets, [], $this->context->at($segment));
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
        return \array_filter($this->properties, static fn ($value): bool => $value !== '');
    }

    /**
     * @return array<array-key, string>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
