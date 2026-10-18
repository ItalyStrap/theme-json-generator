# Settings API

[Documentation](../README.md) · [API overview](api.md) · [Settings guide](../configuration/settings.md)

Start with `$themeJson->settings()`, or
`$themeJson->settings()->blocks('core/paragraph')` for block settings. Category
builders write to that scope and normally return themselves for chaining.
Builder names below are relative to `ItalyStrap\ThemeJsonGenerator`.

Switches use matching `enable…(): self` and `disable…(): self` methods. For example,
`color()->enableText()` writes `true`, and `color()->disableText()` writes `false`.
The switch lists give the shared suffix, rather than repeating both signatures.
Typed support in this package does not guarantee support in your target WordPress
release; choose fields against its schema.

## Root settings

Use `enableAppearanceTools()` / `disableAppearanceTools()` and
`enableUseRootPaddingAwareAlignments()` / `disableUseRootPaddingAwareAlignments()`.
Use `blocks(string $block): Settings` to start a block scope. Do not nest settings
block scopes. `addPreset(PresetInterface $preset): void` registers an object in
this scope; the category-specific `add…()` helpers are usually more convenient.

## color()

Builder: [Settings\Color](../../src/Settings/Color.php).

Switch suffixes: `Text`, `Background`, `Link`, `Custom`, `CustomDuotone`, `CustomGradient`, `DefaultDuotone`, `DefaultGradients`, `DefaultPalette`, `Heading`, `Button`, `Caption`.

| Method |
| --- |
| `addColor(string $slug, string $name, string\|CssColorInterface $color): self` |
| `addColors(iterable $colors): self` |
| `addDuotone(string $slug, string $name, ColorPreset ...$colors): self` |
| `addGradient(string $slug, string $name, GradientInterface $gradient): self` |

## typography()

Builder: [Settings\Typography](../../src/Settings/Typography.php).

Switch suffixes: `DefaultFontSizes`, `TextShadow`, `DefaultTextShadowPresets`, `CustomFontSize`, `FontStyle`, `FontWeight`, `Fluid`, `LetterSpacing`, `LineHeight`, `TextAlign`, `TextColumns`, `TextDecoration`, `WritingMode`, `TextTransform`, `DropCap`.

| Method |
| --- |
| `addTextShadow(string $slug, string $name, string $textShadow): self` |
| `fluid(): Fluid` |
| `textIndent(string $textIndent): self` |
| `disableTextIndent(): self` |
| `addFontFamily(string $slug, string $name, string $fontFamily, FontFace ...$fontFace): self` |
| `addFontSize(string $slug, string $name, string $size, FontSizeFluid\|false\|null $fluid = null): self` |

## spacing()

Builder: [Settings\Spacing](../../src/Settings/Spacing.php).

Switch suffixes: `BlockGap`, `Margin`, `Padding`, `CustomSpacingSize`, `DefaultSpacingSizes`.

| Method |
| --- |
| `disableBlockGapAndLayoutStyles(): self` |
| `units(string ...$units): self` |
| `addSpacingSize(string $slug, string $name, string $size): self` |
| `scale(): Scale` |

## layout()

Builder: [Settings\Layout](../../src/Settings/Layout.php).

Switch suffixes: `Editing`, `CustomContentAndWideSize`.

| Method |
| --- |
| `contentSize(string $keyOrValue): self` |
| `wideSize(string $keyOrValue): self` |

## background()

Builder: [Settings\Background](../../src/Settings/Background.php).

Switch suffixes: `BackgroundImage`, `BackgroundSize`, `BackgroundClip`, `Gradient`.

| Method |
| --- |
| `backgroundClip(string ...$values): self` |

## border()

Builder: [Settings\Border](../../src/Settings/Border.php).

Switch suffixes: `Color`, `Radius`, `Style`, `Width`.

| Method |
| --- |
| `addRadiusSize(string $slug, string $name, string $size): self` |

## dimensions()

Builder: [Settings\Dimensions](../../src/Settings/Dimensions.php).

Switch suffixes: `AspectRatio`, `DefaultAspectRatios`, `Height`, `MinHeight`, `MinWidth`, `Width`.

| Method |
| --- |
| `addAspectRatio(string $slug, string $name, string $ratio): self` |
| `addDimensionSize(string $slug, string $name, string $size): self` |

## shadow()

Builder: [Settings\Shadow](../../src/Settings/Shadow.php).

Switch suffixes: `DefaultPresets`.

| Method |
| --- |
| `addShadow(string $slug, string $name, BoxShadow ...$shadow): self` |

## custom()

Builder: [Settings\Custom](../../src/Settings/Custom.php).

| Method |
| --- |
| `add(array\|string $key, string $value): self` |
| `addMultiple(array $customs): self` |

## position()

Builder: [Settings\Position](../../src/Settings/Position.php).

Switch suffixes: `Sticky`.

## lightbox()

Builder: [Settings\Lightbox](../../src/Settings/Lightbox.php).

Use `enable()` / `disable()` and `enableEditing()` / `disableEditing()`.

## blockVisibility()

Builder: [Settings\BlockVisibility](../../src/Settings/BlockVisibility.php).

Switch suffixes: `Editing`.

## viewport()

Builder: [Settings\Viewport](../../src/Settings/Viewport.php).

| Method |
| --- |
| `mobile(string $width): self` |
| `tablet(string $width): self` |

## Nested builders and value types

| Access | Methods or values |
| --- | --- |
| `spacing()->scale()` | `operator(string $operator)`, `increment(float $increment)`, `steps(int $steps)`, `mediumStep(float $mediumStep)`, `unit(string $unit)` |
| `typography()->fluid()` | `minFontSize(string $value)`, `minViewportWidth(string $value)`, `maxViewportWidth(string $value)` |
| `FontSizeFluid` | `Settings\Typography\Utilities\Fluid`, constructed with minimum and maximum size strings |
| `FontFace` | `Settings\Typography\Utilities\FontFace`; see [font descriptors](presets.md) |
| `BoxShadow` | `Settings\Shadow\Utilities\BoxShadow`; see [shadow builder](presets.md) |
| `GradientInterface` | `Settings\Color\Values\GradientInterface`; implemented by `LinearGradient` |
| `ColorPreset` | `Settings\Color\Color`, distinct from the settings category builder |
| `CssColorInterface` | `Settings\Color\Values\CssColorInterface` |

The `FontSizeFluid` alias in the table corresponds to the parameter alias used
by the typography builder. It is distinct from the global `Typography\Fluid`
options builder. All nested builder methods above return their builder.

## Constraints and special cases

- `color()->addColors()` requires iterable color preset objects, not raw colors.
- Ordinary preset slugs contain only ASCII letters, digits, and hyphens, and
  duplicate registered paths throw. See [presets](../configuration/presets.md).
- `spacing()->units()` requires at least one value. `disableBlockGap()` writes
  `false`; `disableBlockGapAndLayoutStyles()` writes `null`.
- Scale operators are `+` or `*`; increments and medium steps must be positive,
  and steps must be between 1 and 10.
- `background()->backgroundClip()` accepts unique values from `text`,
  `padding-box`, `border-box`, and `content-box`.
- `typography()->textIndent()` accepts `subsequent` or `all`;
  `disableTextIndent()` writes `false`.
- Global fluid typography cannot switch between boolean and object forms once
  configured. Presets containing `clamp()` must opt out when global fluid is
  active. See [typography](../configuration/typography.md).
- `custom()->add()` takes a non-empty string value and a string or array key.
  `addMultiple()` accepts nested arrays with non-empty string leaves; custom
  preset objects are accepted only at its first level.
- Viewport widths must be non-negative lengths in `px`, `em`, or `rem`.

For property paths and schema descriptions, generate the local inventory using
the [schema API inventory instructions](../../CONTRIBUTING.md#schema-api-inventory).
