# Entrypoints and ThemeJson

[Documentation](../README.md) · Previous: [Workflow](../getting-started/workflow.md) · Next: [Settings](settings.md)

An entrypoint is a PHP file that returns a callable. The CLI invokes it to build
one JSON file. Use `theme.json.php` for the root and `styles/<name>.json.php` for
style variation files.

```php
<?php

use ItalyStrap\ThemeJsonGenerator\ThemeJson;

return static function (ThemeJson $themeJson): void {
    $themeJson->schema('https://schemas.wp.org/wp/6.6/theme.json');
    $themeJson->version(3);
    $themeJson->title('Example Theme');
    $themeJson->description('A small theme configured in PHP.');
};
```

Choose a schema for your target WordPress version. Defaults in a fresh CLI
configuration include an empty schema and version `0`; explicitly set these fields
for a usable theme configuration.

## Start from the root object

`ThemeJson` provides access to the user-facing configuration API:

| Method | Purpose |
| --- | --- |
| `settings()` | Editor controls, presets, layout, and custom values |
| `styles()` | Default styles and scoped styles |
| `customTemplates()` | Custom template metadata |
| `templateParts()` | Template part metadata |
| `patterns()` | Pattern Directory slugs |
| `blockTypes()` | Blocks targeted by a block style variation file |

Get builders from this root object. They share the current configuration and
preset collection, so changes are written to the correct output. Constructing
settings or styles builders yourself requires their internal collaborators.

## Use callable parameters

The CLI resolves typed parameters through its container. Common parameters are
`ThemeJson`, `Pipeline`, and `Settings\PresetsInterface`. You do not need to
initialize a container or include the generator's autoloader inside an entrypoint.

Configuration classes used by the callable must be autoloadable. See
[autoloading](../organization/autoloading.md) before splitting configuration into files.

## Import existing values

`init` generates a callable using `merge()` to import existing JSON as a PHP array.
You can keep imported values while introducing typed configuration:

```php
<?php

use ItalyStrap\ThemeJsonGenerator\ThemeJson;

return static function (ThemeJson $themeJson): void {
    $themeJson->merge([
        'version' => 3,
        'settings' => ['appearanceTools' => true],
    ]);

    $themeJson->settings()->layout()->contentSize('42rem');
};
```

`merge()` returns `void`. Avoid overlapping raw preset arrays and registered
presets: the CLI serializes registered preset collections after the callable and
may replace the corresponding collection imported through an array.

## Set paths without a typed method

Prefer typed APIs for supported fields. For future or unsupported paths, use the
root object's `set()`, `get()`, and `appendTo()` methods:

```php
<?php

use ItalyStrap\ThemeJsonGenerator\ThemeJson;

return static function (ThemeJson $themeJson): void {
    $themeJson->schema('https://schemas.wp.org/wp/6.6/theme.json');
    $themeJson->version(3);

    $themeJson->set(['settings', 'custom', 'measure'], '42rem');
    $measure = $themeJson->get(['settings', 'custom', 'measure'], '40rem');
    $themeJson->styles()->dimensions()->width($measure);
};
```

Array paths make each segment explicit, including block names such as
`core/paragraph`. String paths use Config dot notation. These generic methods do
not validate schema support or register raw values as preset objects. Use the
typed [collection methods](templates.md) for templates, patterns, and block types.

`title()`, `description()`, and `slug()` set root metadata. `slug()` is primarily
useful for style variation files; it does not rename preset slugs.

See the [root API reference](../reference/api.md) for signatures.
