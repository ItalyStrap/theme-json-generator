# Theme JSON Generator documentation

Write WordPress theme configuration in PHP, then generate `theme.json` and style
variation JSON files during development or your build. WordPress uses the generated
files; it does not need to run this generator on production requests.

These pages describe the current repository API. When using a published version,
check that its commands and methods match the examples. The package is experimental;
review changes before upgrading.

## Start here

1. [Installation and requirements](getting-started/installation.md)
2. [Generate your first theme.json](getting-started/quick-start.md)
3. [Understand the generation workflow](getting-started/workflow.md)

The examples follow a small theme with a `primary` color, a `body` font size, and
a dark variation. Each chapter explains which file to edit. Commands use
`vendor/bin/theme-json` from the theme root; global installations use `theme-json`.

## Write your configuration

| Chapter | What you will learn |
| --- | --- |
| [Entrypoints and ThemeJson](configuration/entrypoints.md) | Return a callable, set metadata, and use generic paths |
| [Settings](configuration/settings.md) | Configure editor controls, layout, spacing, and custom values |
| [Presets and references](configuration/presets.md) | Register reusable values and use them in styles |
| [Typography and fonts](configuration/typography.md) | Font families, font files, font sizes, and fluid typography |
| [Styles and scopes](configuration/styles.md) | Global styles, blocks, elements, states, and block style variations |
| [Templates and patterns](configuration/templates.md) | Register template metadata and Pattern Directory slugs |

## Organize a larger theme

- [Configuration classes and pipelines](organization/configurators.md)
- [Autoloading and shared configuration](organization/autoloading.md)
- [Style variation files](organization/style-variations.md)

## Work with assets

- [Custom CSS and embedded SCSS](assets/custom-css.md)
- [Export SCSS references](assets/scss-export.md)
- [Color values, modifiers, and palette generators](assets/colors.md)

## Automate generation

- [Development and build integration](automation/development.md)
- [GitHub Actions and deployment](automation/github-actions.md)

## Look something up

- [CLI commands and options](reference/cli.md)
- [API overview and root methods](reference/api.md)
- [Settings API](reference/settings.md)
- [Styles API](reference/styles.md)
- [Preset and utility API](reference/presets.md)
- [Troubleshooting](reference/troubleshooting.md)
- [Migration from older versions](reference/migration.md)

For work on the generator itself, see [Contributing](../CONTRIBUTING.md).
