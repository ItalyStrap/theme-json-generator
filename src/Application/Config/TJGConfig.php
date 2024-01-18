<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Config;

use ItalyStrap\Config\Config;

/**
 * @psalm-api
 * @template TKey as array-key
 * @template TValue
 * @template-extends Config<TKey,TValue>
 * @psalm-suppress DeprecatedInterface
 */
class TJGConfig extends Config
{
}
