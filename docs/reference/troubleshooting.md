# Troubleshooting

[Documentation](../README.md) · [CLI reference](cli.md)

## The executable is not found

For a theme-local installation, run `vendor/bin/theme-json` from the theme root.
For a global installation, add Composer's global binary directory to your shell's
`PATH`. Check [installation](../getting-started/installation.md) and run `list`
with the executable you installed.

## Composer rejects development dependencies

The current generator requires some development branches. Allow those dependencies
explicitly in the installing Composer project, choose a generator version containing
the features you use, and commit your lockfile. A theme's Composer settings do not
configure a separate global Composer installation. See [installation](../getting-started/installation.md).

## init creates nothing

`init` imports existing JSON files. It does not create `theme.json` when missing,
and it skips entrypoints that already exist. Start a new configuration using the
[quick start](../getting-started/quick-start.md). Run `init` from the theme root;
it does not accept `--path`.

## dump prints “No file found”

Check for `theme.json.php` or a `*.json.php` file under `styles/`. Other supporting
PHP files are not entrypoints. Run from the theme root or supply `dump --path=…`.
An empty scan can exit successfully without generating a file; check that the
expected outputs exist when scripting builds.

## Generation cannot find style.css

The destination theme needs a readable root `style.css` containing `Theme Name:`.
For a non-current directory, use `dump --path=/path/to/theme`. This requirement
applies to JSON generation; standalone `scss` export does not require it.

## A configuration class cannot be loaded

Check the namespace, declaration, and [Autoload directory](../organization/autoloading.md).
Attribute paths are relative to their entrypoint; the `--autoload` flag is
relative to the working directory and overrides attributes. Remove duplicate
declarations. A configured but invalid directory fails rather than falling back
to Composer. Put work needing those classes inside the callable, after metadata
can be inspected.

## A configurator is rejected

Pipeline classes must implement `ConfiguratorInterface` and define
`__invoke(ThemeJson $themeJson): void`. Plain invokable classes are not enough.
Use an explicit configured instance for dependencies that cannot be resolved
automatically. See [configurators](../organization/configurators.md).

## A preset key does not resolve

Register presets before using them. Use the type key (`fontSize.body`), not the
JSON collection name (`fontSizes.body`). Block keys include their block scope.
Missing plain keys pass through literally; missing placeholders throw. Generic
root writes do not register objects in the preset collection. See [presets](../configuration/presets.md).

## Duplicate presets or custom path conflicts

Choose unique paths within each type and scope. Avoid registering a common
palette and then another palette with the same slugs in one output. Do not use
a custom path as both a scalar and a parent group. Each variation has a separate
collection; configure only the presets its build needs.

## A variation is missing root values

Every entrypoint builds independently. Root autoload directories are shared,
but root configuration is not executed for a selected variation. Use shared
[configurators](../organization/configurators.md) explicitly in the relevant files.

## Scoped CSS is rejected

Without a selector argument, use declarations and nested rules starting with `&`.
With a selector argument, every input selector must belong to that scope. `.card-title`
and `.card_title` are independent classes, not descendants of `.card`. Keep scoped
at-rules out of block CSS; write complete global rules instead.
See [custom CSS](../assets/custom-css.md).

## Fluid typography conflicts with clamp()

Opt an already fluid size out with the preset's `false` fluid argument. Choose
either global boolean fluid configuration or its object options; do not configure
both alternatives. See [typography](../configuration/typography.md).

## SCSS output is missing, stale, or conflicting

`dump` exports SCSS only with a `ScssOutput` attribute or `--scss`. Check whether
`--no-scss` suppressed it. Root SCSS attributes are not inherited by variations.
Each selected entrypoint must target a distinct output file. File symlink outputs
are rejected. Standalone `scss --check` compares with its input JSON, so regenerate
JSON first when the PHP source changed. See [SCSS export](../assets/scss-export.md).

## Validation succeeds in CI despite printed errors

The current `validate` command prints schema errors without reliably failing the
process. `dump --validate` does not run validation. Use a separate validator with
a chosen schema and a failing exit status for CI gates. See [the CLI reference](cli.md).

## Validation cannot download a schema

The command caches Gutenberg's schema as `theme.schema.json`, refreshing it after
a week or with `--force`. Check network access, PHP URL stream support, and write
permissions. The cached schema is independent of your entrypoint's `$schema` URL.

## The generated workflow stops immediately or cannot push

Replace `THEME_JSON_VERSION` with an installable tag or branch. Check `THEME_PATH`,
the root entrypoint, and whether repository rules permit the workflow token to
push. Existing workflow copies are not updated automatically. See [GitHub Actions](../automation/github-actions.md).

## Files cannot be written

Check ownership and write access for the destination and its parent directory.
Run generation as the user who owns the project. Avoid fixing a theme permission
problem by running the generator as root. Review the reported destination before
changing project permissions.
