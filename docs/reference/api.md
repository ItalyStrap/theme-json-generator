# API overview and root methods

[Documentation](../README.md) · [Settings API](settings.md) · [Styles API](styles.md) · [Presets](presets.md)

The public configuration namespace is `ItalyStrap\ThemeJsonGenerator`. Start
with the CLI-injected `ThemeJson`; settings and styles builders obtained from it
share the current output and preset collection.

## ThemeJson

| Method | Result and behavior |
| --- | --- |
| `schema(string $schema): self` | Set `$schema` |
| `version(int $version): self` | Set the JSON format version |
| `title(string $title): self` | Set the title |
| `description(string $description): self` | Set the description |
| `slug(string $slug): self` | Set root variation metadata |
| `settings(): Settings` | Start global settings |
| `styles(): Styles` | Start global styles |
| `customTemplates(): CustomTemplates` | Access custom template metadata |
| `templateParts(): TemplateParts` | Access template part metadata |
| `patterns(): Patterns` | Access Pattern Directory slugs |
| `blockTypes(): BlockTypes` | Access block style variation targets |
| `set(string\|array $key, mixed $value): self` | Set an explicit path; throws on failed writes |
| `get(string\|array $key, mixed $default = null): mixed` | Read an explicit path |
| `appendTo(string\|array $key, mixed $value): self` | Append through the underlying Config implementation; throws on failure |
| `merge(array ...$arrays): void` | Import arrays; used by generated `init` entrypoints |

Use `set()`, `get()`, and `appendTo()` as generic path escape hatches when a typed
method is unavailable. Prefer arrays of path segments; strings use Config dot
notation. Generic writes do not register preset objects, resolve all references,
or verify schema support. Prefer the typed collection methods below for append
operations, which handle the expected collection shapes.

See [entrypoints](../configuration/entrypoints.md) for examples. Default CLI
configuration has version `0` and an empty schema; choose these explicitly.

## Root collections

| Entry method | Collection method |
| --- | --- |
| `customTemplates()` | `addTemplate(string $name, string $title, array $postTypes = []): self` |
| `templateParts()` | `addPart(string $name, string $area = 'uncategorized', ?string $title = null): self` |
| `patterns()` | `add(string $pattern): self` |
| `blockTypes()` | `add(string $blockType): self` |

These methods append metadata. They do not create template HTML, register local
pattern PHP, or enable arbitrary editor blocks. See [templates and patterns](../configuration/templates.md).

## Configurators and Pipeline

`ConfiguratorInterface` requires `__invoke(ThemeJson $themeJson): void`.
`Pipeline::process(iterable $configurators): void` accepts implementing instances
or implementing class names. It resolves class names through the container and
executes the configurators in order. See [configuration classes](../organization/configurators.md).

## Entrypoint attributes

| Attribute | Constructor | Applies to |
| --- | --- | --- |
| `Attribute\Autoload` | `__construct(string $path)` | Returned callable; repeatable directory mapping |
| `Attribute\ScssOutput` | `__construct(string $path, ?string $filename = null)` | Returned callable; SCSS export destination |

Paths in attributes are relative to the declaring entrypoint, unless absolute.
Root autoload mappings are shared with variations; SCSS destinations are not.
See [autoloading](../organization/autoloading.md) and [SCSS export](../assets/scss-export.md).

## API boundaries

Use settings and styles builders through the root object rather than directly
instantiating builders that need contexts. Classes under `Cli` implement the
command runner and its infrastructure; typical theme configuration does not need
them. Classes with `Experimental` in their names are not a stable foundation for
new configuration.

The locally generated schema inventory maps schema paths to typed methods. It
describes the schema used by the report and is not a guarantee that every listed
setting is supported by every WordPress version. The generated files are not
committed; follow the [inventory instructions](../../CONTRIBUTING.md#schema-api-inventory)
to create them.
