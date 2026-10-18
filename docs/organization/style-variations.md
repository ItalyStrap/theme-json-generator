# Style variation files

[Documentation](../README.md) · Previous: [Autoloading](autoloading.md) · Next: [Custom CSS](../assets/custom-css.md)

Add `*.json.php` files under the theme's `styles/` directory to generate matching
variation JSON files. Supporting PHP files without that suffix are not treated
as entrypoints.

## Add a dark variation

Create `styles/dark.json.php`:

```php
<?php

use ItalyStrap\ThemeJsonGenerator\ThemeJson;

return static function (ThemeJson $themeJson): void {
    $themeJson->schema('https://schemas.wp.org/wp/6.6/theme.json');
    $themeJson->version(3);
    $themeJson->title('Dark');
    $themeJson->slug('dark');
    $themeJson->description('Dark colors for Example Theme.');

    $themeJson->settings()->color()->addColor('primary', 'Primary', '#a8c8ff');
    $themeJson->styles()->color()->text('color.primary')->background('#151a22');
};
```

Run:

```shell
vendor/bin/theme-json dump
```

The command generates both the root output and `styles/dark.json`, provided both
entrypoints exist. The variation receives a fresh container: it does not inherit
the root callable's settings, presets, or styles during generation.

WordPress applies variation data within its own theme configuration system. The
generator does not compute that final merged result. If a variation callable
uses a generator preset key, register that preset in the variation's own build.

## Generate one output

```shell
vendor/bin/theme-json dump --file=styles/dark.json
vendor/bin/theme-json dump --file=styles/dark.json.php --dry-run
```

Both filename forms select the same entrypoint. The root entrypoint may still
be read for autoload metadata, but its callable is not executed for this selection.

## Reuse selected configuration

Extract shared settings into [configurators](configurators.md), then run them from
each entrypoint that needs them. Root [autoload mappings](autoloading.md) are shared
with variations; configuration values are not.

Avoid running a common configurator and then registering another preset with the
same type and slug. Duplicate preset paths throw. Instead, use a parameterized
configurator or distinct palettes for the outputs.

## Other kinds of variations

The root `blockTypes()` collection can target blocks in an appropriate block style
variation file. The `styles()->blocks(...)->variations(...)` builder writes nested
style variation data within an output. See [styles and scopes](../configuration/styles.md)
for that separate API. Use the format supported by your target WordPress version.

Each entrypoint can independently opt in to [SCSS reference export](../assets/scss-export.md)
with `ScssOutput`; those attributes are not inherited from the root.
