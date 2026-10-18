# Color values, modifiers, and palette generators

[Documentation](../README.md) · Previous: [SCSS export](scss-export.md) · Next: [Build integration](../automation/development.md)

For a hand-picked palette, use `settings()->color()->addColor()` as shown in the
[preset guide](../configuration/presets.md). The classes in this chapter help
compute color values and turn generated palettes into registered presets.

## Parse and convert a color

```php
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;

$primary = new CssColor('#2457ab');
$rgba = $primary->toRgba(0.5);
$hsla = $primary->toHsla();
```

`CssColor` accepts the formats supported by the installed Spatie Color parser.
Hexadecimal, RGB/RGBA, and HSL/HSLA forms are useful starting points. Unsupported
color strings throw; these classes do not resolve CSS variables to concrete colors.

`toRgb()`, `toHsl()`, and `toHex()` produce opaque colors. `toRgba()` and `toHsla()`
preserve alpha unless you supply an override. `alpha()` can return a hexadecimal
byte string for a hex input; it is not always a float. Use conversion methods
when you need a normalized representation.

## Modify without replacing the source

```php
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Transformers\ColorModifier;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;

$primary = new CssColor('#2457ab');
$modifier = new ColorModifier($primary);
$lighter = $modifier->lighten(10);
$tinted = $modifier->tint(20);
$transparent = $modifier->opacity(0.5);
```

The modifier returns new color values. Each call operates on the original color
passed to its constructor. To apply another operation to a result, construct a
modifier for that result.

| Operation | Meaning |
| --- | --- |
| `lighten()`, `darken()` | Add or subtract HSL lightness percentage points |
| `saturate()` | Adjust HSL saturation; negative amounts reduce saturation |
| `tint()`, `shade()`, `tone()` | Mix RGB channels with white, black, or gray |
| `hueRotate()` | Rotate hue by signed degrees |
| `complementary()` | Rotate hue by 180 degrees, retaining achromatic colors |
| `opacity()` | Set alpha; no argument returns the original color |
| `contrast()` | Move HSL lightness away from the midpoint |
| `invert()` | Invert HSL lightness |

Mix weights are percentages from 0 to 100 and are clamped to that range. Lightness
and saturation results are rounded and clamped. Alpha is retained for alpha-capable
results, with modifier normalization to two decimal places. `contrast()` is a visual
lightness adjustment, not an accessibility contrast measurement.

## Generate named harmony colors

```php
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Factories\ColorPresetsFromGeneratorFactory;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel\AnalogousColors;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;

$generator = new AnalogousColors(
    new CssColor('#2457ab'),
    angle: 30,
    identifiers: ['base', 'warm', 'cool']
);
$presets = (new ColorPresetsFromGeneratorFactory($generator, 'brand', 'Brand'))->create();
$themeJson->settings()->color()->addColors($presets);
```

Inside an entrypoint callable, this registers `brand-base`, `brand-warm`, and
`brand-cool`. `addColors()` expects color preset objects, so use the factory rather
than passing the generator's raw color values.

| Generator | Default rotations after the base color |
| --- | --- |
| `AnalogousColors` | +30°, −30° |
| `ComplementaryColors` | 180° |
| `SplitComplementaryColors` | +150°, −150° |
| `TriadicColors` | +120°, −120° |
| `TetradicColors` | 90°, 180°, 270° |

Harmony generators include the base first. Optional identifiers must be a list
of unique, nonblank strings matching the number of color positions. Without
identifiers, output uses numeric keys; explicit zero rotations are skipped in
that default mode. `AchromaticColors` generates black and white without a base.

## Define explicit rotation or lightness steps

```php
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel\HueRotation;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\HueRotationStep;

$colors = (new HueRotation(new CssColor('#2457ab'), [
    new HueRotationStep('base', 0),
    new HueRotationStep('accent', 180),
]))->generate();
```

`HueRotation` includes only the supplied steps and preserves their order and
identifiers. A zero or full-turn step returns the original color instance.

For monochromatic palettes:

```php
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\Monochromatic\TintAndShades;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\ColorVariationStep;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\VariationDirection;

$colors = (new TintAndShades(new CssColor('#2457ab'), [
    new ColorVariationStep('light', 20, VariationDirection::Lighter),
    new ColorVariationStep('dark', 20, VariationDirection::Darker),
]))->generate();
```

`TintAndShades` uses RGB mix percentages; `HslLightness` accepts the same step
objects but interprets the amounts as HSL lightness percentage points. Both apply
each step to the original color, preserve equal colors, and add no implicit base.
Amounts must be finite and between 0 and 100; step identifiers must be unique and
nonblank. The preset factory uses identifiers in slugs, so choose identifiers that
also satisfy [preset naming rules](../configuration/presets.md).

## Compare colors and preserve precision

```php
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;

$red = new CssColor('#ff0000');
$red->equals('rgb(255,0,0)'); // true
$red->equals('rgba(255,0,0,0.5)'); // false
```

`equals()` compares normalized 8-bit RGB channels and alpha exactly, without a
tolerance. Different RGB channels remain unequal even when both colors are fully
transparent. Hex alpha uses the byte value divided by 255, so `#ff000080` is not
exactly equal to `rgba(255,0,0,0.5)`.

Object conversions retain internal channels where supported, but string
serialization can round HSL components and alpha. Converting a color object and
parsing its formatted string are therefore different operations:

```php
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;

$yellow = new CssColor('#ffc107');
$hsla = $yellow->toHsla();
$yellow->equals($hsla); // true
$yellow->equals((string)$hsla); // false: rounded representation
```

The [preset and utility reference](../reference/presets.md) also covers gradients
and shadow builders. See [migration notes](../reference/migration.md) when updating
a custom `CssColorInterface` implementation.
