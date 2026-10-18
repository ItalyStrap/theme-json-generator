# Autoloading and shared configuration

[Documentation](../README.md) · Previous: [Configurators](configurators.md) · Next: [Style variations](style-variations.md)

The generator loads its own dependencies. To load theme configuration classes,
declare directories on the callable returned by an entrypoint:

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

This example uses the `Colors` class from [the configurator guide](configurators.md).

## Directory paths

Attribute paths are relative to the file declaring the callable. For example:

| Entrypoint | Attribute | Resolved directory |
| --- | --- | --- |
| `theme.json.php` | `#[Autoload('config')]` | `<theme>/config` |
| `styles/dark.json.php` | `#[Autoload('../config')]` | `<theme>/config` |

Absolute paths work too. Repeat the attribute for several directories:

```php
return #[Autoload('config')] #[Autoload('shared')]
static function (ThemeJson $themeJson, Pipeline $pipeline): void {
    $themeJson->schema('https://schemas.wp.org/wp/6.6/theme.json');
    $themeJson->version(3);
    $pipeline->process([Colors::class]);
};
```

Create both declared directories and populate them with the required class
declarations before using this alternative.

## What gets loaded

The scanner reads PHP declarations recursively and registers an in-memory
classmap. Classes, interfaces, traits, and enums are supported; directory layout
does not need to follow PSR-4. Scanning itself does not execute those files.
Files that return arrays or closures still need an explicit `require`.

Keep work that needs theme classes inside the returned callable. The entrypoint
must be loadable before the CLI can inspect its attributes.

## Root and variation directories

Root `Autoload` mappings are also available to variation entrypoints. A variation
can add its own directories. Even when selecting only one variation, the CLI
loads the root entrypoint to inspect metadata without invoking its callback.

## Override from the command line

```shell
vendor/bin/theme-json dump --autoload=./config
vendor/bin/theme-json dump --path=/path/to/theme --autoload=/path/to/config
```

Loading follows this precedence:

1. `--autoload` overrides root and variation directory attributes. Relative flag
   paths use the current working directory, not the directory selected by `--path`.
2. Otherwise, root attributes and the selected variation's attributes apply.
3. Without applicable directory configuration, the CLI loads the theme-root
   `vendor/autoload.php` with `require_once` when it exists.

An entrypoint without external classes needs no directory configuration. Missing,
unreadable, or empty configured directories fail instead of silently falling back.
Ambiguous class declarations also fail.

These attributes work with local and global installations. They do not install
third-party dependencies needed by your theme configuration. A local installation
may already have registered the theme's Composer autoloader; loading another
Composer autoloader does not isolate conflicting dependency versions.
