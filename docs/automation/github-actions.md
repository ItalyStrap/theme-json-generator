# GitHub Actions and deployment

[Documentation](../README.md) · Previous: [Build integration](development.md) · Next: [CLI reference](../reference/cli.md)

The generator can copy a workflow template into your theme repository. The
template installs the generator globally, runs generation, and commits changed
JSON outputs to the branch.

## Create the workflow

From the theme root:

```shell
vendor/bin/theme-json workflow:init
```

With a global installation:

```shell
theme-json workflow:init --path=/path/to/theme
```

The theme must already contain `theme.json` or `theme.json.php`. The command
creates `.github/workflows/theme-json.yml` once and refuses to overwrite an
existing file. Future generator updates do not update that copy automatically.

## Configure it before committing

Open the copied file and set:

| Setting | Purpose |
| --- | --- |
| `THEME_JSON_VERSION` | A published tag or Composer `dev-branch` containing the features your configuration uses |
| `THEME_PATH` | Theme directory relative to the repository root; defaults to `.` |

The placeholder version intentionally fails until replaced. A branch available
only on your machine cannot be installed by GitHub Actions. The template accepts
development dependencies because the current generator requires them.

For a nested theme, move the workflow to the repository's `.github/workflows/`
directory and adjust `THEME_PATH`. Use [Autoload attributes](../organization/autoloading.md)
when configuration classes should load without installing a theme Composer project.

## Understand the generated workflow

The [source template](../../templates/theme-json.yml) runs for branch pushes and
manual dispatch. It uses a separate Composer environment, invokes `dump`, stages
`theme.json` plus JSON outputs corresponding to `styles/*.json.php`, and commits
when those staged outputs changed. It requires a root `theme.json.php` and a
nonempty generated `theme.json`.

The template grants `contents: write`; repository rules must allow its push.
It stages only its configured outputs, rather than every modified theme file.

## Include SCSS and other assets

`dump` also exports SCSS when entrypoints declare `ScssOutput`. The template
includes commented examples for staging those outputs; uncomment and adapt them
to your configured paths if you want generated SCSS committed.

For a JSON-only project, replace the PHP generation step with the commented
standalone `scss` step. Run Sass compilation after reference generation. Add your
own asset build, packaging, and deployment steps as needed.

If your theme needs production Composer dependencies, enable and adapt the
optional installation step before packaging.

## Workflow triggers and deployment

Commits pushed with the default `GITHUB_TOKEN` do not normally trigger another
push workflow. Put required packaging and deployment steps in the same workflow,
or explicitly arrange another trigger. See [GitHub's trigger documentation](https://docs.github.com/en/actions/how-tos/write-workflows/choose-when-workflows-run/trigger-a-workflow).

If your project prefers checking output freshness instead of committing from CI,
adapt the workflow to fail on changed generated files. See [build integration](development.md).
