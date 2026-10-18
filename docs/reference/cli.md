# CLI commands and options

[Documentation](../README.md) · [Troubleshooting](troubleshooting.md)

Examples use a theme-local installation from the theme root. With a global
installation, replace `vendor/bin/theme-json` with `theme-json`. Run `list` to
see available commands and `help <command>` to inspect the installed version.

## Command overview

| Command | Current behavior |
| --- | --- |
| `init` | Import discovered JSON files into missing PHP entrypoints |
| `dump` | Execute selected PHP entrypoints and write JSON and optional SCSS |
| `scss` | Export SCSS references from an existing JSON file |
| `validate` | Print validation results for discovered JSON files |
| `workflow:init` | Copy a customizable GitHub Actions workflow once |
| `info` | Print discovered JSON filenames |

Options belong to individual commands. In particular, `--path` is available on
`dump` and `workflow:init`, not on every command.

## init

```shell
vendor/bin/theme-json init
```

Reads root `theme.json` and JSON files under `styles/`, then creates corresponding
`.json.php` files when missing. Existing entrypoints are kept. It does not create
a missing root JSON file or scaffold a theme. Use the [quick start](../getting-started/quick-start.md)
for a new configuration.

`--styles` / `-s` is declared as an optional-value option but currently does not
affect execution. Existing style JSON files are scanned without it.

The generated callable currently imports the compatibility name
`ItalyStrap\ThemeJsonGenerator\Api\ThemeJson`. It works through an alias; use
`ItalyStrap\ThemeJsonGenerator\ThemeJson` for new or edited configuration.

## dump

```shell
vendor/bin/theme-json dump
vendor/bin/theme-json dump --file=styles/dark.json --dry-run
```

| Option | Meaning |
| --- | --- |
| `--path`, `-p` | Theme directory; defaults to the working directory |
| `--file` | Select a root-relative JSON or `.json.php` entrypoint filename |
| `--autoload` | Directory containing configuration declarations; overrides directory attributes |
| `--scss` | Output directory for SCSS references; overrides SCSS attributes |
| `--no-scss` | Suppress all SCSS export, including attributes and `--scss` |
| `--dry-run` | Execute configuration and prepare outputs without generator writes |
| `--validate` | Accepted, but currently does not invoke validation |

Supply values explicitly, for example `--path=/path/to/theme` and
`--file=styles/dark.json`. Relative `--autoload` and `--scss` paths use the working
directory, while `--file` uses the theme root. Attribute paths use their declaring
entrypoint's directory. `--scss` uses default filenames even when an attribute
sets a custom filename.

`dump` discovers root `theme.json.php` and `*.json.php` files under `styles/`.
It writes each result beside its source and requires a theme `style.css` with a
`Theme Name:` header when writing JSON. A missing explicit selection fails; an
empty scan prints `No file found`.

Dry-run executes user PHP, so user-defined side effects can still occur. It does
not provide a content diff or compare output freshness. Configurations are
prepared before writing, including checks for conflicting SCSS destinations.
This is not a transaction across all output files.

## scss

```shell
vendor/bin/theme-json scss --input=theme.json --output=assets/scss/_tokens.scss
```

| Option | Meaning |
| --- | --- |
| `--input` | JSON input; defaults to `theme.json` |
| `--output` | SCSS file; defaults to `_<input-name>.scss` beside the input |
| `--dry-run` | Report the destination without creating files or directories |
| `--check` | Check freshness without writing; fails for missing, stale, or invalid output |

Relative flag paths use the working directory. `--check` and `--dry-run` cannot
be combined. The command does not execute project PHP, rewrite JSON, or require
theme metadata. See [SCSS export](../assets/scss-export.md) for coverage and naming.

## validate

```shell
vendor/bin/theme-json validate
vendor/bin/theme-json validate --force
```

`--force` / `-f` recreates the cached `theme.schema.json`. Without it, the command
downloads a schema when missing or older than one week. Network access and PHP
URL stream support are needed to download it.

The current implementation fetches the Gutenberg trunk schema and uses it for
discovered JSON files, independently of each file's `$schema` value. Validation
errors are printed but do not reliably change the exit status to failure. This
command alone is not a blocking CI validator. It does not validate compiled SCSS.

## workflow:init

```shell
vendor/bin/theme-json workflow:init --path=/path/to/theme
```

`--path` / `-p` selects a theme directory, defaulting to the working directory.
The command requires `theme.json` or `theme.json.php` and refuses to overwrite
`.github/workflows/theme-json.yml`. Configure the copied version placeholder and
project paths before using it. See [GitHub Actions](../automation/github-actions.md).

## info

```shell
vendor/bin/theme-json info
```

Currently lists discovered JSON filenames. It does not provide a full settings,
preset usage, or schema-coverage report.

## Exit status

Normal command completion returns `0`; command failures and uncaught exceptions
produce nonzero statuses. `scss --check` explicitly uses failure status for stale
or missing output. Do not infer validation success from `validate` returning `0`,
or the presence of outputs from an empty `dump` scan returning `0`.
