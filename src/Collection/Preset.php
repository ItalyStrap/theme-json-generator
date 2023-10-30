<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Collection;

use ItalyStrap\ThemeJsonGenerator\Helper\ConvertCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Collectible;
use ItalyStrap\ThemeJsonGenerator\Settings\CollectionInterface;

/**
 * @deprecated
 */
final class Preset implements CollectionInterface
{
    use Collectible;
    use ConvertCase;
    use DeprecationMethod;

    /**
     * @var array<int|string, array<string,string>>
     */
    private $collection;

    /**
     * @var string
     */
    private $category;

    /**
     * @var string
     */
    private $key;

    private \ItalyStrap\ThemeJsonGenerator\Settings\PresetCollection $preset_new;

    /**
     * @param array<int|string, array<string,string>> $collection
     * @param string $category
     * @param string $key
     */
    public function __construct(array $collection, string $category, string $key = '')
    {
        $this->preset_new = new \ItalyStrap\ThemeJsonGenerator\Settings\PresetCollection(...func_get_args());
//      $this->collection = $collection;
//      $this->category = $category;
//      $this->key = '' === $key ? $category : $key ;
    }

    /**
     * @inerhitDoc
     */
    public function category(): string
    {
        $this->deprecatePreset();
        return $this->preset_new->category();
    }

    /**
     * @inerhitDoc
     */
    public function propOf(string $slug): string
    {
        $this->deprecatePreset();
        return $this->preset_new->propOf(...func_get_args());
    }

    /**
     * @inerhitDoc
     */
    public function varOf(string $slug): string
    {
        $this->deprecatePreset();
        return $this->preset_new->varOf(...func_get_args());
    }

    /**
     * @inerhitDoc
     */
    public function value(string $slug): string
    {
        $this->deprecatePreset();
        return $this->preset_new->value(...func_get_args());

//      /** @var array<string, mixed> $item */
//      foreach ( $this->toArray() as $item ) {
//          if ( \in_array( $slug, $item, true ) ) {
//              return (string) $item[ $this->key ];
//          }
//      }
//
//      throw new \RuntimeException("Value of {$slug} does not exists." );
    }

    /**
     * @inerhitDoc
     */
    public function toArray(): array
    {
        $this->deprecatePreset();
        return $this->preset_new->toArray();

        /**
         * @var array<string, mixed> $item
         */
//      foreach ( $this->collection as $key => $item ) {
//          \preg_match_all(
//              '/(?:{{.*?}})+/',
//              (string) $item[ $this->key ],
//              $matches
//          );
//
//          foreach ( $matches[0] as $match ) {
//              $this->collection[ $key ][ $this->key ] = \str_replace(
//                  $match,
//                  $this->findCssVariable( \str_replace( ['{{', '}}' ], '', $match ) ),
//                  $this->collection[ $key ][ $this->key ]
//              );
//          }
//      }
//
//      return $this->collection;
    }
}
