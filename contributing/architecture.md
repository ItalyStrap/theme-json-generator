# Architecture and source layout

[Contributing](../CONTRIBUTING.md)

## Source responsibilities

| Directory or file | Responsibility |
| --- | --- |
| `src/ThemeJson.php` | Public root configuration facade |
| `src/Settings.php`, `src/Settings/` | Scoped settings builders, presets, and value helpers |
| `src/Styles.php`, `src/Styles/` | Scoped style builders and CSS/SCSS formatting |
| `src/SettingsContext.php`, `src/StyleContext.php` | Paths and permitted scope transitions |
| `src/ConfiguratorInterface.php`, `src/Pipeline.php` | Reusable configuration steps |
| `src/Attribute/` | Entrypoint metadata for autoloading and SCSS output |
| `cli/Application/` | Commands, messages, and generation/validation middleware |
| `cli/Infrastructure/` | Autoloading, containers, filesystem writes, and SCSS export |
| `bin/` | Executable entrypoints |
| `report/` | Schema/API coverage tooling and generated inventory |
| `tools/rector/` | Repository-specific PHP refactoring rules |
| `tests/` | Unit, integration, and functional suites and fixtures |
| `templates/` | Files copied into consuming projects, including the workflow |

## Generation flow

The CLI bootstrap registers commands in its application container. `dump` creates
a `DumpMessage` carrying file selection, output, autoload, and preview options.
Its middleware discovers entrypoints and uses `EntrypointLoader` to read callables
and register project declarations.

`ThemeJsonContainerFactory` creates a fresh theme container for each selected
entrypoint. `ThemeJsonModule` shares Config, ThemeJson, and the preset collection
within that build. The injector invokes the callable with resolved parameters.
`Pipeline` resolves configurator classes from that same container.

Settings and styles builders obtained from ThemeJson use contexts to write into
the shared root configuration. Preset registration also updates the shared
collection. `PresetsToThemeJson` serializes registered root and block presets after
the callable finishes, so raw preset collections can be replaced at that stage.

Dump prepares selected configurations and SCSS destinations before writes.
The JSON writer serializes Config with pretty printing, unescaped slashes and
Unicode, and a final newline. The SCSS exporter renders references from the final
configuration. Output preparation is not an atomic transaction across files.

## CSS processing

The root styles facade distinguishes global and scoped CSS. `Css` resolves preset
references and validates selector relationships. `Scss` resolves references,
compiles SCSS, then passes compiled CSS through scoped formatting. This embedded
compilation is separate from SCSS reference export.

## Compatibility

The public namespace is `ItalyStrap\ThemeJsonGenerator`. CLI classes live below
`ItalyStrap\ThemeJsonGenerator\Cli`. Explicit legacy aliases are registered in
`namespace-bc-aliases.php`; they are not a general translation of all historical
namespace layouts. Avoid documenting internal contexts or container construction
as the normal theme authoring workflow.

## Relevant verification

Unit suites cover builders, scopes, presets, and helpers. CLI integration suites
exercise entrypoint autoloading, workflow copying, and SCSS export. WordPress
integration cases characterize generated custom CSS behavior. Functional suites
exercise the executable and command output. Use these cases alongside source
inspection when updating public behavior descriptions.
