# Migration from older versions

[Documentation](../README.md) · [API overview](api.md)

These notes describe the current repository API. They are not a version-by-version
release history. Review your installed version and generated diff before upgrading;
the package remains experimental.

## Use the current root namespace

New entrypoints should import:

```php
use ItalyStrap\ThemeJsonGenerator\ThemeJson;
```

The repository explicitly provides compatibility aliases for
`Application\Config\Blueprint` and `Api\ThemeJson` under the generator namespace.
The `init` template still uses `Api\ThemeJson`; update that import when editing
generated entrypoints. Do not assume other historical namespaces are aliased.

## Start settings and styles from ThemeJson

Use `$themeJson->settings()` and `$themeJson->styles()` rather than building
disconnected settings or style objects and assembling their arrays manually.
Scoped builders write through a shared context. Begin a new root call when
changing categories or configuring a sibling scope.

Typed methods cover supported paths. Use `ThemeJson::set()`, `get()`, and
`appendTo()` for generic paths. Prefer explicit arrays of path segments. Keep
`merge()` for importing raw configuration where useful; it returns `void`.

## Register and reference presets deliberately

Use category helpers such as `addColor()` and `addFontSize()`, or inject the shared
`PresetsInterface`. The CLI serializes that collection after the entrypoint runs.
Avoid defining the same collection both as raw JSON arrays and registered objects.

Ordinary slugs now reject empty strings, dots, and characters outside ASCII
letters, digits, and hyphens. Use nested custom keys for custom setting hierarchies.
Duplicate preset paths and scalar/group conflicts throw instead of silently
overwriting values. A placeholder resolving to a group is rejected.

Update corresponding source references when changing a slug, then regenerate
derived JSON and assets. See [presets](../configuration/presets.md).

## Use explicit configuration classes

Pipeline entries must implement `ConfiguratorInterface`; an arbitrary invokable
class is not sufficient. Put configuration in `__invoke(ThemeJson $themeJson): void`
and dependencies in the constructor. Use `#[Autoload('config')]` to discover
configuration classes locally or with a global executable. See [configurators](../organization/configurators.md).

## Review variations separately

Only `*.json.php` files under `styles/` are entrypoints. Each runs in a fresh
container; share configuration explicitly through classes. Root autoload mappings
are available to variations, but root `ScssOutput` destinations are not inherited.

## Update custom CSS syntax

Global custom CSS uses complete rules. Scoped CSS uses declarations and `&`
nested rules, or regular CSS with an explicit root-selector argument. Unrelated
selectors and scoped at-rules are rejected. See [the CSS guide](../assets/custom-css.md)
before carrying over old examples that relied on flattening or silent omission.

## Review color integrations

Custom `CssColorInterface` implementations must provide
`equals(string|CssColorInterface $color): bool`. Equality is exact normalized RGB
and alpha equality, not formatted-string equality. Keep color objects when a
string round trip would lose precision.

Use the current `Color\Generators` classes and
`Color\Factories\ColorPresetsFromGeneratorFactory` for new palette configuration.
Prefer explicit step identifiers when stable generated slugs matter. See [colors](../assets/colors.md).

## Replace assumptions about CLI options

`init` imports existing JSON and does not create a missing theme. `dump --validate`
currently does not invoke validation. `validate` is not a reliable blocking CI
check. `--path` is supported on `dump` and `workflow:init`, not every command.
Consult [the CLI reference](cli.md) when migrating shell scripts.

For assets, distinguish embedded `styles()->scss()` compilation from generating
a reference partial with `ScssOutput` or the standalone `scss` command.
