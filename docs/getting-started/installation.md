# Installation and requirements

[Documentation](../README.md) · Next: [Quick start](quick-start.md)

You need PHP 8.2 or newer, the JSON extension, and Composer. Composer checks the
remaining dependency requirements when installing the package. For generation,
use a theme directory containing a `style.css` file with a `Theme Name:` header.
You do not need a running WordPress installation to generate files.

The standalone `scss` command only needs a JSON input file; it does not require
theme metadata or a PHP entrypoint.

## Install in a theme

From the theme directory:

```shell
composer require --dev italystrap/theme-json-generator
vendor/bin/theme-json list
```

Keep `composer.json` and `composer.lock` in version control so development and CI
can install the same dependencies. Use the executable installed in this theme's
`vendor/bin` directory.

The current package depends on some development branches. If Composer rejects
their stability, explicitly allow the required development dependencies in your
project. A project that accepts development dependencies can use
`"minimum-stability": "dev"` with `"prefer-stable": true`. Choose and pin a
generator version or branch that contains the features you need; an older
published release may not provide the API documented here.

## Install globally

To use one installation across multiple themes:

```shell
composer global require italystrap/theme-json-generator
export PATH="$(composer global config bin-dir --absolute):$PATH"
theme-json list
```

Persist the `PATH` setting in your shell configuration. Version and dependency
stability requirements also apply to the global Composer project. A global
installation supplies the generator; use [autoloading attributes](../organization/autoloading.md)
to load your theme's configuration classes without a theme Composer installation.

With a global executable, run commands from the theme root. `dump` and
`workflow:init` also accept `--path=/path/to/theme`. Other commands do not share
that option; see the [CLI reference](../reference/cli.md).

## Production files

Install and run the generator during development or CI, then deploy its generated
JSON and any compiled assets. A theme using the output does not need the generator
as a runtime dependency. Keep any separate runtime dependencies required by the
theme itself.

Continue with the [quick start](quick-start.md).
