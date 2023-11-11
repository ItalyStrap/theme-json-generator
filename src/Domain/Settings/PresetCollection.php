<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings;

use ItalyStrap\ThemeJsonGenerator\Helper\ConvertCase;

/**
 * @psalm-api
 */
final class PresetCollection implements CollectionInterface, CollectibleInterface
{
    use Collectible;
    use ConvertCase;

    /**
     * @var array<int|string, array<string,string>>
     */
    private array $collection;

    private string $category;

    private string $key;

    /**
     * @param array<int|string, array<string,string>> $collection
     * @param string $category
     * @param string $key
     */
    public function __construct(array $collection, string $category, string $key = '')
    {
        $this->collection = $collection;
        $this->category = $category;
        $this->key = '' === $key ? $category : $key ;
    }

    public function category(): string
    {
        return $this->category;
    }

    public function add($valueObject): self
    {
        $this->collection[] = $valueObject->toArray();
        return $this;
    }

    public function addMultiple(array $valueObjects): self
    {
        foreach ($valueObjects as $valueObject) {
            $this->add($valueObject);
        }
        return $this;
    }

    public function get(string $slug): array
    {
        /** @var array<string, mixed> $item */
        foreach ($this->collection as $item) {
            if (\in_array($slug, $item, true)) {
                return $item;
            }
        }

        throw new \RuntimeException("{$slug} does not exists.");
    }

    public function propOf(string $slug): string
    {

        /** @var array<string, mixed> $item */
        foreach ($this->collection as $item) {
            if (\in_array($slug, $item, true)) {
                return \sprintf(
                    '--wp--preset--%s--%s',
                    $this->camelToUnderscore($this->category()),
                    $this->camelToUnderscore($slug)
                );
            }
        }

        throw new \RuntimeException("{$slug} does not exists.");
    }

    public function varOf(string $slug): string
    {
        return \sprintf(
            'var(%s)',
            $this->propOf($slug)
        );
    }

    public function value(string $slug): string
    {

        /** @var array<string, mixed> $item */
        foreach ($this->toArray() as $item) {
            if (\in_array($slug, $item, true)) {
                return (string) $item[ $this->key ];
            }
        }

        throw new \RuntimeException("Value of {$slug} does not exists.");
    }

    public function toArray(): array
    {

        /**
         * @var array<string, mixed> $item
         */
        foreach ($this->collection as $key => $item) {
            if (!\array_key_exists($this->key, $item)) {
//              echo \PHP_EOL;
//              echo \sprintf(
//                  'Key %s does not exist in %s',
//                  $this->key,
//                  \json_encode($item, JSON_PRETTY_PRINT)
//              );
//              echo \PHP_EOL;
                continue;
            }

            \preg_match_all(
                '/(?:{{.*?}})+/',
                (string) $item[ $this->key ],
                $matches
            );

            foreach ($matches[0] as $match) {
                $this->collection[ $key ][ $this->key ] = \str_replace(
                    $match,
                    $this->findCssVariable(\str_replace(['{{', '}}' ], '', $match)),
                    $this->collection[ $key ][ $this->key ]
                );
            }
        }

        return $this->collection;
    }
}
