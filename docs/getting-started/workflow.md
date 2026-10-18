# Understand the generation workflow

[Documentation](../README.md) · Previous: [Quick start](quick-start.md) · Next: [Entrypoints](../configuration/entrypoints.md)

The PHP files are your editable source. JSON and optional SCSS reference files
are build outputs.

```text
theme.json.php + configuration classes → dump → theme.json
styles/dark.json.php                   → dump → styles/dark.json
                                              + optional SCSS references
```

## What dump does

1. Finds the root `theme.json.php` and `*.json.php` entrypoints under `styles/`.
2. Registers configured autoload directories.
3. Executes each selected callable with its own configuration container.
4. Serializes registered presets into settings.
5. Writes JSON beside each entrypoint and any requested SCSS output.

Each entrypoint builds its own output. Root configuration is not automatically
merged into a variation's generated JSON. Share PHP configurators when you need
the same configuration in several outputs.

Generation does not read Site Editor database changes, resolve parent-theme
configuration, or calculate the final settings and styles of a running WordPress
site. Its input is your PHP configuration.

## Preview and select files

```shell
vendor/bin/theme-json dump --dry-run
vendor/bin/theme-json dump --file=styles/dark.json
vendor/bin/theme-json dump --path=/path/to/theme
```

`--dry-run` executes configuration and prepares output but skips the generator's
JSON and SCSS writes. Your own callable still runs, so keep filesystem or network
side effects out of configuration code. It is not a content diff or freshness check.

`--file` accepts either the JSON filename or its `.json.php` entrypoint, relative
to the theme root. A missing selection fails. A scan with no entrypoints prints
`No file found`.

## Validate separately

From the theme root:

```shell
vendor/bin/theme-json dump
vendor/bin/theme-json validate
```

`validate` reads existing JSON and caches a downloaded Gutenberg schema as
`theme.schema.json`. It refreshes the cache after a week or with `--force`.
It currently uses that schema rather than the `$schema` URL in your entrypoint.

The current `dump --validate` option is accepted but does not invoke validation.
Also, validation errors are printed without reliably producing a failing exit
status. Use the output for manual checks; use a validator with a failing exit code
and an explicitly chosen schema when you need a blocking CI check.

## Commit and deploy

Choose whether to commit generated outputs or generate them in your release
build. In either case, deploy `theme.json`, variation JSON, font files, and compiled
assets required by the theme. Run generation before packaging and before compiling
Sass that imports [exported references](../assets/scss-export.md).

See [build integration](../automation/development.md) for a repeatable sequence.
