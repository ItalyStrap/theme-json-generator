<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\Config\Config;
use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\ThemeJsonGenerator\Helper\ConvertCase;

/**
 * @psalm-api
 */
final class CustomCollection implements CollectionInterface, CollectibleInterface
{
    use Collectible;
    use ConvertCase;

    /**
     * @var array<int|string, mixed>
     */
    private array $collection;

    private string $category;

    private ConfigInterface $config;

    /**
     * @param array<int|string, mixed> $collection
     */
    public function __construct(
        array $collection,
        ConfigInterface $config = null
    ) {
        $this->collection = $collection;
        $this->category = 'custom';
        $this->config = $config ?? new Config();
    }

    public function category(): string
    {
        return $this->category;
    }

    public function propOf(string $slug): string
    {
        $config = clone $this->config;
        $config->merge($this->collection);

        if (! $config->has($slug)) {
            throw new \RuntimeException("{$slug} does not exists.");
        }

        $keys = \explode('.', $slug);

        $property = '';
        foreach ($keys as $word) {
            $property .= '--' . $word;
        }

        return \sprintf(
            '--wp--%s%s',
            $this->category(),
            $this->camelToUnderscore($property)
        );
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
        $this->toArray();

        if ($this->config->has($slug)) {
            return (string) $this->config->get($slug);
        }

        throw new \RuntimeException("Value of {$slug} does not exists.");
    }

    public function toArray(): array
    {
        /**
         * @var string|array<int|string, mixed> $item
         */
        foreach ($this->collection as $key => $item) {
            $item = (array) $item;

            \array_walk_recursive($item, function (string &$input) {
                if (\strpos($input, '{{') !== false) {
                    $input = $this->replacePlaceholder($input);
                }
            });

            if ($this->hasSingleValue($item)) {
                $item = $this->convertToString($item);
            }

            $this->config->set(
                (string)$key,
                $item
            );
        }

        return $this->config->toArray();
    }

    /**
     * @param string $item
     * @return string
     */
    private function replacePlaceholder(string $item): string
    {
        \preg_match_all(
            '/(?:{{.*?}})+/',
            $item,
            $matches
        );

        foreach ($matches[ 0 ] as $match) {
            $item = \str_replace(
                $match,
                $this->findCssVariable(\str_replace(['{{', '}}'], '', $match)),
                $item
            );
        }
        return $item;
    }

    /**
     * @param array<int|string, mixed> $item
     * @return bool
     */
    private function hasSingleValue(array $item): bool
    {
        return \count($item) === 1 && \array_key_exists(0, $item);
    }

    /**
     * @param array<int|string, mixed> $item
     * @return string
     */
    private function convertToString(array $item): string
    {
        return (string) $item[ 0 ];
    }
}
