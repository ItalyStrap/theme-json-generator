# Preset and utility API

[Documentation](../README.md) · [API overview](api.md) · [Preset guide](../configuration/presets.md)

Preset classes live below `ItalyStrap\ThemeJsonGenerator\Settings`. The shorter
names in the table are relative to that namespace. Type keys identify objects in
the shared `PresetsInterface` collection; JSON paths identify their serialized
settings destinations.

## Preset constructors

Ordinary preset constructors start with `string $slug, string $name`, followed
by the value arguments listed here:

| Class | Type key | Remaining constructor arguments | JSON path under settings |
| --- | --- | --- | --- |
| `Color\Color` | `color` | `CssColorInterface $color` | `color.palette` |
| `Color\Gradient` | `gradient` | `GradientInterface $gradient` | `color.gradients` |
| `Color\Duotone` | `duotone` | `Color ...$colors` | `color.duotone` |
| `Typography\FontFamily` | `fontFamily` | `string $fontFamily, FontFace ...$fontFace` | `typography.fontFamilies` |
| `Typography\FontSize` | `fontSize` | `string $size, Fluid\|false\|null $fluid = null` | `typography.fontSizes` |
| `Typography\TextShadow` | `textShadow` | `string $textShadow` | `typography.textShadowPresets` |
| `Spacing\SpacingSize` | `spacing` | `string $size` | `spacing.spacingSizes` |
| `Shadow\Shadow` | `shadow` | `BoxShadow ...$shadow` | `shadow.presets` |
| `Dimensions\AspectRatio` | `aspectRatio` | `string $ratio` | `dimensions.aspectRatios` |
| `Dimensions\DimensionSize` | `dimension` | `string $size` | `dimensions.dimensionSizes` |
| `Border\RadiusSize` | `borderRadius` | `string $size` | `border.radiusSizes` |

`CssColorInterface` and `GradientInterface` are in `Settings\Color\Values`.
`Color` in the duotone row is the `Color\Color` preset. `Fluid` and `FontFace`
are in `Settings\Typography\Utilities`. `BoxShadow` is in
`Settings\Shadow\Utilities`.

Custom settings use a different constructor:
`Custom\Custom(array|string $key, mixed $value)`. The value must be a non-empty
string; arrays of key segments and dotted keys support nested custom settings.
They serialize below `settings.custom`, rather than into a list of named presets.

Ordinary slugs must be non-empty ASCII letters, digits, or hyphens. Duplicate
registered paths throw. Prefer lowercase hyphenated slugs to avoid surprises
when names become CSS custom properties.

## Shared preset methods

| Method | Purpose |
| --- | --- |
| `type(): string` | Collection type key |
| `slug(): string` | Preset slug, or custom key path |
| `ref(): string` | Placeholder such as `{{color.primary}}` |
| `prop(): string` | CSS custom property name |
| `var(string $fallback = ''): string` | CSS `var()` reference with optional fallback |
| `toArray(): array` | Serialized preset fields |
| `__toString(): string` | CSS reference for ordinary presets; raw stored value for custom presets |

`Color\Color` additionally provides `color(): CssColorInterface`.

## Preset collection

The concrete `Settings\Presets` implements `Settings\PresetsInterface`:

| Method | Purpose |
| --- | --- |
| `add(PresetInterface $item): self` | Register a root preset |
| `addToBlock(string $block, PresetInterface $item): self` | Register a block preset |
| `addMultiple(array $items): self` | Register several root preset objects |
| `get(array\|string $key, $default = null)` | Resolve a collection path |
| `parse(string $content): string` | Resolve `{{…}}` references |
| `presets(): iterable` | Iterate preset leaves, including block presets |
| `collection(): array` | Inspect the underlying collection |

Inject the CLI's shared collection when configuring an entrypoint. Registering
presets in an unrelated collection does not add them to the current output.

Keys use the preset type, not the JSON collection name: `fontSize.body`, rather
than `fontSizes.body`. A block key includes its scope, for example
`blocks.core/button.color.accent`. Missing placeholders and placeholders resolving
to a group instead of a preset throw.

## Gradients and duotone

`Color\Values\LinearGradient` provides `direction(string $direction): self` and
`colorStop(Color|CssColorInterface|string $color, string $stop = ''): self`.
Use at least two stops before serializing. A color preset stop retains its CSS
variable reference.

This complete entrypoint registers a gradient and a duotone preset:

```php
<?php

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Color;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\LinearGradient;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

return static function (ThemeJson $themeJson, PresetsInterface $presets): void {
    $themeJson->schema('https://schemas.wp.org/wp/6.6/theme.json');
    $themeJson->version(3);

    $primary = new Color('primary', 'Primary', new CssColor('#2457ab'));
    $light = new Color('light', 'Light', new CssColor('#ffffff'));
    $presets->addMultiple([$primary, $light]);

    $gradient = (new LinearGradient())
        ->direction('135deg')
        ->colorStop($primary)
        ->colorStop($light);

    $themeJson->settings()->color()->addGradient('brand', 'Brand', $gradient);
    $themeJson->settings()->color()->addDuotone('brand', 'Brand', $primary, $light);
};
```

Duotone requires a non-empty name and at least two color preset objects. Its
colors are serialized as concrete RGBA values.

## Box shadows

`Shadow\Utilities\BoxShadow` builds one shadow. It requires both offsets before
serialization. Each dimensional field and color can be assigned once; cloning
resets the builder rather than copying its configured values.

```php
<?php

use ItalyStrap\ThemeJsonGenerator\Settings\Shadow\Utilities\BoxShadow;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

return static function (ThemeJson $themeJson): void {
    $themeJson->schema('https://schemas.wp.org/wp/6.6/theme.json');
    $themeJson->version(3);

    $shadow = (new BoxShadow())
        ->offsetX('0')
        ->offsetY('2px')
        ->blur('8px')
        ->color('rgba(0,0,0,0.15)');

    $themeJson->settings()->shadow()->addShadow('card', 'Card', $shadow);
    $themeJson->styles()->shadow('shadow.card');
};
```

Available methods are `inset(bool $inset = true)`, `offsetX(string $x)`,
`offsetY(string $y)`, `blur(string $blur)`, `spread(string $spread)`, and
`color($color)`, all returning the builder. `color()` accepts a color preset,
`CssColorInterface`, or supported color string. Dimensions accept the builder's
supported CSS units or unitless `0`; arbitrary CSS expressions are not parsed.
Pass several shadows to `addShadow()` for a comma-separated preset.

## Font descriptors

`Typography\Utilities\FontFace` takes these required named arguments:
`fontFamily`, `fontWeight`, `fontStyle`, `fontStretch`, and `src`. Weight accepts
a string or integer; source accepts a non-empty string or list of strings.
Use an empty stretch string to omit it.

Optional arguments are `fontDisplay` (default `fallback`), `ascentOverride`,
`descentOverride`, `fontVariant`, `fontFeatureSettings`, `fontVariationSettings`,
`lineGapOverride`, `sizeAdjust`, and `unicodeRange`. Optional descriptors default
to an empty string and are omitted when empty. Display must be `auto`, `block`,
`fallback`, `swap`, or `optional`. `toArray()` exposes the serialized descriptors.

`Typography\Utilities\Fluid(string $min, string $max)` defines a per-preset size
range; it is distinct from the global fluid options builder. See [typography](../configuration/typography.md)
for complete examples and `clamp()` constraints.

## Color values and generators

`Color\Values\CssColor` exposes conversion methods, channel accessors, exact
`equals()`, and lightness/luminance helpers. It also provides
`relativeLuminance(CssColorInterface $color): float`. These helpers are not a
substitute for validating accessibility against the actual rendered foreground
and background.

`Color\Transformers\ColorModifier` adjusts a source color.
`Color\Generators\ColorsGenerator::generate(): array` is implemented by harmony,
rotation, monochromatic, and achromatic generators.
`Color\Factories\ColorPresetsFromGeneratorFactory($generator, $slugPrefix, $namePrefix)`
provides `create(): array` to turn generated colors into preset objects.

`Color\Factories\ColorFactory` offers `fromColorString(string $color)`,
`fromCssColor(CssColorInterface $colorValue)`,
`rgba(int $red, int $green, int $blue, float $alpha = 1)`, and
`hsla(int $hue, float $saturation, float $lightness, float $alpha = 1)`, returning
color value objects.

See [the color guide](../assets/colors.md) for modifier units, generator steps,
identifiers, conversion precision, and runnable examples.
