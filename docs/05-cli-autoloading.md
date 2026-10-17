# Configuration autoloading

You can install the generator locally in each theme or globally for use across
multiple themes. Both installations run the same CLI and read the same
`theme.json.php` and `styles/*.json.php` entrypoints.

To generate SCSS references automatically during `dump`, or export them from an
existing JSON file, see [SCSS export](06-scss-export.md).

For a local installation, run these commands from the theme root:

```shell
composer require --dev italystrap/theme-json-generator
vendor/bin/theme-json init
vendor/bin/theme-json dump
```

Composer installs the generator in the theme's `vendor/` directory as a
development dependency. Commit the theme's `composer.lock` so development and CI
use the same dependency versions. Run `init` once to create entrypoints from
existing JSON files, then run `dump` whenever you change your PHP configuration.

For a global installation, install the generator in Composer's global environment
and add its executable directory to your shell's `PATH`:

```shell
composer global require italystrap/theme-json-generator
export PATH="$(composer global config bin-dir --absolute):$PATH"

cd /path/to/theme
theme-json init
theme-json dump
```

Persist the `PATH` setting in your shell configuration to use `theme-json` in
future terminals. The global installation supplies the generator and its
dependencies; the theme does not need its own Composer installation when its
configuration uses the attribute below. To generate files from another working
directory, run `theme-json dump --path=/path/to/theme`.

The examples below use `theme-json`; substitute `vendor/bin/theme-json` when
running a local installation from the theme root.

Declare configuration directories on the returned callable. You can configure
`ThemeJson` directly and use `Pipeline` to run configuration classes in order:

```php
<?php

// theme.json.php

use ItalyStrap\ThemeJsonGenerator\Attribute\Autoload;
use ItalyStrap\ThemeJsonGenerator\Pipeline;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;
use MyTheme\Configuration\Colors;
use MyTheme\Configuration\Typography;

return #[Autoload('config')]
static function (ThemeJson $themeJson, Pipeline $pipeline): void {
    $themeJson->title('My Theme');

    $pipeline->process([
        Colors::class,
        Typography::class,
    ]);
};
```

Here, `Colors` and `Typography` are your classes under `config/`. Each pipeline
class must implement `ItalyStrap\ThemeJsonGenerator\ConfiguratorInterface`,
providing `public function __invoke(ThemeJson $themeJson): void`. The CLI supplies
`ThemeJson` and `Pipeline` to the entrypoint through dependency injection.

The CLI scans PHP declarations recursively and registers an in-memory classmap
before invoking the callable. Classes, interfaces, traits, and enums are
supported; directory layout does not need to follow PSR-4. Scanning does not
execute files. Files returning arrays or closures still need explicit `require`.
No bootstrap file, loader snippet, or generated autoloader is needed.

Relative attribute paths resolve against the declaring entrypoint's directory.
Absolute paths, including `__DIR__ . '/config'`, work too. Repeat `#[Autoload(...)]`
to declare additional directories.

The attribute also works on style variation entrypoints:

```php
<?php

// styles/dark.json.php

use ItalyStrap\ThemeJsonGenerator\Attribute\Autoload;
use ItalyStrap\ThemeJsonGenerator\Pipeline;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;
use MyTheme\Configuration\DarkColors;

return #[Autoload('../config')]
static function (ThemeJson $themeJson, Pipeline $pipeline): void {
    $themeJson->title('Dark');
    $pipeline->process([DarkColors::class]);
};
```

`../config` resolves to the theme's `config/` directory because this entrypoint
lives in `styles/`. `DarkColors` implements the same configurator interface.
Running `dump` generates `theme.json` and `styles/dark.json` from their respective
entrypoints.

Root mappings are available to `styles/*.json.php`, so repeating the shared
directory attribute on a variation is optional when the root already declares
it. A variation can also declare additional directories. Even when selecting one
variation, the CLI loads the root entrypoint to read its metadata without
invoking its callback:

```shell
theme-json dump --file=styles/dark.json
```

Both `theme.json` and `theme.json.php` forms are accepted by `--file`; paths are
relative to the theme root. Missing selected entrypoints fail. Supporting PHP
files under `styles/` are not treated as entrypoints unless named `*.json.php`.

Autoload configuration takes this precedence:

1. `--autoload=./config` overrides all root and variation attributes. Its path is
   relative to the working directory.
2. Root attributes plus attributes on the selected variation.
3. Theme-root `vendor/autoload.php`, loaded with `require_once` when no applicable
   directories are configured and the file exists.

Missing, unreadable, or empty configured directories fail instead of falling
back. Ambiguous class declarations also fail. An entrypoint needing no external
classes remains valid without any autoload configuration.

The entrypoint must be loadable before its attributes are inspected: put work
requiring configuration classes inside the returned callable. The generator
always loads its own dependencies first. A local Composer installation may have
already registered the theme's autoloader. Loading another Composer autoloader
does not isolate dependency versions when using a global installation.

```shell
# Local
vendor/bin/theme-json dump

# Global, also when run outside the theme directory
theme-json dump --path=/path/to/theme

# Explicit override
theme-json dump --path=/path/to/theme --autoload=/path/to/config
```

To start a GitHub Actions workflow that installs the generator globally,
generates the JSON files, and commits changed outputs back to the branch, run:

```shell
# Global installation, from the theme root
theme-json workflow:init

# Local installation
vendor/bin/theme-json workflow:init

# Specify another theme directory
theme-json workflow:init --path=/path/to/theme
```

This copies [templates/theme-json.yml](../templates/theme-json.yml) into
`.github/workflows/theme-json.yml` in the theme directory. It is a one-time copy:
the command refuses to overwrite an existing file, and later generator updates
do not change it. Edit the copied workflow to match your project's conventions.

Set `THEME_JSON_VERSION` to a published tag or Composer `dev-branch` containing
the generator features you use. The template deliberately requires this choice
instead of installing an unspecified version. A branch available only on your
machine must be published before GitHub Actions can install it.

The template assumes the theme is the repository root. For a nested theme, move
the copied file into the repository's `.github/workflows/` directory and set
`THEME_PATH` to the theme's path relative to the repository root.

On branch pushes or manual runs, the workflow installs the generator in a
separate global Composer environment, runs `dump`, and commits only `theme.json`
and JSON outputs corresponding to `styles/*.json.php`. Unchanged output produces
no commit. Your configuration's `Autoload` attributes avoid needing a theme
Composer installation during generation. Enable the commented
`composer install --no-dev` step afterward if packaging needs the theme's
production dependencies, and add any other asset build or release steps.

The workflow uses `contents: write`; repository rules must permit its push.
Commits made with the default `GITHUB_TOKEN` do not start another push workflow,
so put any required packaging steps in this workflow or explicitly arrange a
separate trigger. See [GitHub's workflow triggering documentation](https://docs.github.com/en/actions/how-tos/write-workflows/choose-when-workflows-run/trigger-a-workflow).
