# Development and build integration

[Documentation](../README.md) · Previous: [Colors](../assets/colors.md) · Next: [GitHub Actions](github-actions.md)

Treat generation as a build step. Edit PHP configuration, regenerate outputs,
compile dependent assets, then package the theme.

## Run locally

From the theme root:

```shell
vendor/bin/theme-json dump
```

This discovers the root and variation entrypoints. For a smaller iteration:

```shell
vendor/bin/theme-json dump --file=styles/dark.json
vendor/bin/theme-json dump --dry-run
```

The generator does not include a file watcher. If your build already watches
files, have it run `dump` after changes to entrypoints and configuration classes.
Watch the source directories, rather than triggering another build whenever a
generated JSON or SCSS file changes.

## Build assets in order

For a theme that uses `ScssOutput` attributes:

```text
Install build dependencies
→ Run theme-json dump
→ Compile Sass and other theme assets
→ Package or deploy the theme
```

For an existing JSON-only configuration, use the standalone exporter before your
Sass build:

```shell
vendor/bin/theme-json scss --input=theme.json --output=assets/scss/_tokens.scss
```

The [SCSS export guide](../assets/scss-export.md) explains the differences between
these workflows. Avoid generating the same SCSS destination through both.

## Check generated files

For committed JSON, regenerate in CI and inspect the resulting Git diff for the
output paths your project owns. A nonempty diff means the committed outputs need
updating. `dump --dry-run` does not compare existing output with expected content.

For SCSS exported from an existing JSON file:

```shell
vendor/bin/theme-json scss --input=theme.json --output=assets/scss/_tokens.scss --check
```

`--check` returns a nonzero status if that SCSS file is missing, stale, or invalid.
It compares against the supplied JSON, not against PHP entrypoints.

## Keep validation separate

```shell
vendor/bin/theme-json validate
```

The current command prints schema errors but does not reliably fail on an invalid
configuration. It also downloads a Gutenberg schema independently of your chosen
`$schema`. Use a separate validator with a pinned target schema and failing exit
status if schema validation must block a release. See [CLI limitations](../reference/cli.md).

## Decide what to commit

Commit PHP entrypoints, configuration classes, and dependency lockfiles. Either
commit generated JSON and assets or create them during CI; ensure the final theme
archive includes them. Add the validator cache `theme.schema.json` to your theme's
ignore rules if you do not want it tracked.

Install production dependencies separately when packaging requires them. The
generator itself can remain a development dependency. Avoid placing configuration
classes in code that runs on every WordPress request unless the theme independently
needs those classes at runtime.
