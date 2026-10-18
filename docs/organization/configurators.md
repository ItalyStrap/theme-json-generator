# Configuration classes and pipelines

[Documentation](../README.md) · Previous: [Templates](../configuration/templates.md) · Next: [Autoloading](autoloading.md)

Keep a small configuration in its entrypoint. When several sections or variations
need the same configuration, extract focused classes implementing
`ConfiguratorInterface` and run them through `Pipeline`.

## Create a configurator

Save this as `config/Colors.php`:

```php
<?php

declare(strict_types=1);

namespace ExampleTheme\Configuration;

use ItalyStrap\ThemeJsonGenerator\ConfiguratorInterface;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class Colors implements ConfiguratorInterface
{
    public function __invoke(ThemeJson $themeJson): void
    {
        $themeJson->settings()->color()->addColor('primary', 'Primary', '#2457ab');
        $themeJson->styles()->color()->text('color.primary');
    }
}
```

The interface requires exactly `public function __invoke(ThemeJson $themeJson): void`.
Use constructor parameters for any additional dependencies.

## Run classes in order

In `theme.json.php`:

```php
<?php

use ExampleTheme\Configuration\Colors;
use ItalyStrap\ThemeJsonGenerator\Attribute\Autoload;
use ItalyStrap\ThemeJsonGenerator\Pipeline;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

return #[Autoload('config')]
static function (ThemeJson $themeJson, Pipeline $pipeline): void {
    $themeJson->schema('https://schemas.wp.org/wp/6.6/theme.json');
    $themeJson->version(3);
    $pipeline->process([Colors::class]);
};
```

The pipeline resolves class names through the entrypoint container and executes
them in the supplied order against the same `ThemeJson`. Put preset definitions
before configurators that use their references. A class name that does not
implement `ConfiguratorInterface` is rejected.

## Pass configured instances

`process()` also accepts configurator instances, including objects you construct
with explicit dependencies. For example, change the `Colors` configurator above
to accept a color string through its constructor, then pass an instance with
the chosen value instead of its class name.

Use this approach when configuration values should be explicit. For automatically
resolved classes, constructor dependencies must be resolvable by the container;
arbitrary scalar parameters and application-specific interface bindings need
explicit configuration or explicit instances.

## Share configuration across outputs

Use the same configurator class in several entrypoint pipelines. Every entrypoint
has its own container and preset collection. Sharing a class does not make the
root theme's values appear automatically in another output.

A practical organization is:

```text
theme.json.php
config/
  Colors.php
  Typography.php
  Layout.php
styles/
  dark.json.php
```

Only `Colors.php` is defined by the example above; add the other configurators as
your theme grows. Configure their loading using [autoloading](autoloading.md),
then decide which belong in each [variation](style-variations.md).
