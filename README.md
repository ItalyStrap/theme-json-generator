# ItalyStrap Theme JSON Generator

[![Tests Status](https://github.com/ItalyStrap/theme-json-generator/actions/workflows/test.yml/badge.svg)](https://github.com/ItalyStrap/theme-json-generator/actions/workflows/test.yml)
[![Latest Stable Version](https://img.shields.io/packagist/v/italystrap/theme-json-generator.svg)](https://packagist.org/packages/italystrap/theme-json-generator)
[![Total Downloads](https://img.shields.io/packagist/dt/italystrap/theme-json-generator.svg)](https://packagist.org/packages/italystrap/theme-json-generator)
[![License](https://img.shields.io/packagist/l/italystrap/theme-json-generator.svg)](LICENSE)

Write WordPress `theme.json` configuration in PHP. Define settings, presets, and
styles through a fluent API, share configuration between variations, and generate
JSON during development or CI.

The package is experimental. These docs describe the current repository API;
choose an installed version containing the features you need and review changes
before upgrading.

## Install

Requires PHP 8.2 or newer, the JSON extension, and Composer. From your theme root:

```shell
composer require --dev italystrap/theme-json-generator
```

The current package requires some development-branch dependencies. See
[installation](docs/getting-started/installation.md) for dependency stability,
version selection, and global installation.

## Generate your first configuration

Create `theme.json.php` in a theme containing `style.css` with a `Theme Name:` header:

```php
<?php

use ItalyStrap\ThemeJsonGenerator\ThemeJson;

return static function (ThemeJson $themeJson): void {
    $themeJson->schema('https://schemas.wp.org/wp/6.6/theme.json');
    $themeJson->version(3);
    $themeJson->title('Example Theme');

    $themeJson->settings()->color()->addColor('primary', 'Primary', '#2457ab');
    $themeJson->settings()->typography()->addFontSize('body', 'Body', '1rem');

    $themeJson->styles()->color()->text('color.primary');
    $themeJson->styles()->typography()->fontSize('fontSize.body');
};
```

Choose a schema and format version for your target WordPress release. Then run:

```shell
vendor/bin/theme-json dump
```

The command writes `theme.json` beside the entrypoint, resolving preset keys to
CSS variables. Edit PHP and regenerate; direct changes to generated JSON are
overwritten. To import an existing `theme.json` into a PHP entrypoint, run `init`.
For a complete walkthrough, follow the [quick start](docs/getting-started/quick-start.md).

## What you can build

- Configure editor controls, layout, typography, and reusable presets.
- Apply global styles or target blocks, elements, states, and block style variations.
- Organize configuration classes into ordered pipelines.
- Generate variation JSON from `styles/*.json.php` entrypoints.
- Add custom CSS or compile embedded SCSS into JSON.
- Export SCSS references for use in a separate Sass build.
- Start a customizable GitHub Actions generation workflow.

Generate JSON and assets before deployment. WordPress consumes the output;
this generator does not need to run during production requests.

## Documentation

Start at the [documentation homepage](docs/README.md), or open a topic directly:

| Task | Guide |
| --- | --- |
| Understand inputs, outputs, and generation | [Workflow](docs/getting-started/workflow.md) |
| Configure settings and reusable values | [Settings](docs/configuration/settings.md) · [Presets](docs/configuration/presets.md) |
| Style a theme or block | [Styles and scopes](docs/configuration/styles.md) · [Custom CSS](docs/assets/custom-css.md) |
| Split configuration into classes | [Configurators](docs/organization/configurators.md) · [Autoloading](docs/organization/autoloading.md) |
| Add alternative theme styles | [Style variation files](docs/organization/style-variations.md) |
| Work with generated references or palettes | [SCSS export](docs/assets/scss-export.md) · [Colors](docs/assets/colors.md) |
| Automate a build | [Build integration](docs/automation/development.md) · [GitHub Actions](docs/automation/github-actions.md) |
| Look up a command or method | [CLI](docs/reference/cli.md) · [API](docs/reference/api.md) |
| Upgrade or solve a problem | [Migration](docs/reference/migration.md) · [Troubleshooting](docs/reference/troubleshooting.md) |

Prefer typed configuration APIs. For unsupported paths, use the root
`ThemeJson::set()`, `get()`, and `appendTo()` methods with explicit array paths;
string paths use Config dot notation. Root metadata methods such as `schema()`,
`version()`, `title()`, and `slug()` are covered in the [entrypoint guide](docs/configuration/entrypoints.md).
`slug()` is primarily for style variation metadata.

## Contributing

See [Contributing](CONTRIBUTING.md) for development checks, source architecture,
and documentation maintenance. For schema/API planning, `make report/api-inventory`
generates Markdown and CSV reports in `report/`. See the [inventory instructions](CONTRIBUTING.md#schema-api-inventory)
to map schema paths to typed methods.

## Resources

- [WordPress theme.json documentation](https://developer.wordpress.org/themes/global-settings-and-styles/)
- [Issues and feature requests](https://github.com/ItalyStrap/theme-json-generator/issues)

## License

Copyright (c) 2021 Enea Overclokk, ItalyStrap. Licensed under the [MIT license](LICENSE).
