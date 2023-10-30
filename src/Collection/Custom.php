<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Collection;

use ItalyStrap\Config\Config;
use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\ThemeJsonGenerator\Helper\ConvertCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Collectible;
use ItalyStrap\ThemeJsonGenerator\Settings\CollectionInterface;

/**
 * @deprecated
 */
final class Custom implements CollectionInterface
{
    use Collectible;
    use ConvertCase;
    use DeprecationMethod;

    /**
     * @var array<int|string, mixed>
     */
    private array $collection;

    private string $category;

    /**
     * @var ConfigInterface
     */
    private $config;

    private \ItalyStrap\ThemeJsonGenerator\Settings\CustomCollection $custom_new;

    /**
     * @param array<int|string, mixed> $collection
     * @param ConfigInterface<mixed>|null $config
     */
    public function __construct(
        array $collection,
        ConfigInterface $config = null
    ) {
        $this->custom_new = new \ItalyStrap\ThemeJsonGenerator\Settings\CustomCollection(...func_get_args());
        $this->collection = $collection;
        $this->category = 'custom';
        $this->config = $config ?? new Config();
    }

    /**
     * @inerhitDoc
     */
    public function category(): string
    {
        $this->deprecateCustom();
        return $this->custom_new->category();
    }

    /**
     * @inerhitDoc
     */
    public function propOf(string $slug): string
    {
        $this->deprecateCustom();
        return $this->custom_new->propOf(...func_get_args());
    }

    /**
     * @inerhitDoc
     */
    public function varOf(string $slug): string
    {
        $this->deprecateCustom();
        return $this->custom_new->varOf(...func_get_args());
    }

    /**
     * @inerhitDoc
     */
    public function value(string $slug): string
    {
        $this->deprecateCustom();
        return $this->custom_new->value(...func_get_args());
    }

    /**
     * @inerhitDoc
     */
    public function toArray(): array
    {
        $this->deprecateCustom();
        return $this->custom_new->toArray();
    }
}
