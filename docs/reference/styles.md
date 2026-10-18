# Styles API

[Documentation](../README.md) · [API overview](api.md) · [Styles guide](../configuration/styles.md)

Start with `$themeJson->styles()`. Every call starts at global styles. Scope
methods return another styles builder; property methods write immediately into
that builder's context. Register presets before using their keys in values.

## Scopes and direct root methods

| Method |
| --- |
| `css(string $css, string $selector = ''): self` |
| `appendCss(string $css, string $selector = ''): self` |
| `scss(string $scss, string $selector = ''): self` |
| `appendScss(string $scss, string $selector = ''): self` |
| `background(): Background` |
| `border(): Border` |
| `color(): Color` |
| `dimensions(): Dimensions` |
| `filter(): Filter` |
| `outline(): Outline` |
| `shadow(string $value): self` |
| `spacing(): Spacing` |
| `typography(): Typography` |
| `elements(string $path): self` |
| `blocks(string $path): self` |
| `variations(string $variation): self` |
| `state(string $state): self` |

`blocks()` requires a full lowercase block name such as `core/paragraph`.
`variations()` requires a lowercase variation slug. `state()` accepts a single
colon-prefixed state such as `:hover`, following a block or element scope.
The [scope guide](../configuration/styles.md) documents allowed transitions.

`css()` / `scss()` replace the current `css` field. Their `append…()` counterparts
add to it. Embedded SCSS compiles into that field, rather than creating an SCSS
file. Read [custom CSS](../assets/custom-css.md) for the required selector syntax.

## Property builders

Each property setter below returns its property builder. String values can be
literal CSS, a registered preset key such as `color.primary`, or text containing
`{{color.primary}}`. An unknown plain key is passed through literally; a missing
placeholder throws. Builder names are relative to `ItalyStrap\ThemeJsonGenerator`.

### background()

Builder: [Styles\Background](../../src/Styles/Background.php).

| Method |
| --- |
| `backgroundImage(string $value): self` |
| `backgroundPosition(string $value): self` |
| `backgroundRepeat(string $value): self` |
| `backgroundSize(string $value): self` |
| `backgroundAttachment(string $value): self` |
| `backgroundClip(string $value): self` |

### border()

Builder: [Styles\Border](../../src/Styles/Border.php).

| Method |
| --- |
| `color(string $value): Border` |
| `radius(string $value): Border` |
| `style(string $value): Border` |
| `width(string $value): Border` |
| `top(): Border` |
| `right(): Border` |
| `bottom(): Border` |
| `left(): Border` |

### color()

Builder: [Styles\Color](../../src/Styles/Color.php).

| Method |
| --- |
| `background(string $value): Color` |
| `gradient(string $value): Color` |
| `text(string $value): Color` |

### dimensions()

Builder: [Styles\Dimensions](../../src/Styles/Dimensions.php).

| Method |
| --- |
| `aspectRatio(string $value): self` |
| `height(string $value): self` |
| `minHeight(string $value): self` |
| `minWidth(string $value): self` |
| `width(string $value): self` |

### filter()

Builder: [Styles\Filter](../../src/Styles/Filter.php).

| Method |
| --- |
| `duotone(string $value): self` |

### outline()

Builder: [Styles\Outline](../../src/Styles/Outline.php).

| Method |
| --- |
| `color(string $value): Outline` |
| `offset(string $value): Outline` |
| `style(string $value): Outline` |
| `width(string $value): Outline` |

### spacing()

Builder: [Styles\Spacing](../../src/Styles/Spacing.php).

| Method |
| --- |
| `blockGap(string $value): self` |
| `margin(): BoxSpacing` |
| `padding(): BoxSpacing` |

### typography()

Builder: [Styles\Typography](../../src/Styles/Typography.php).

| Method |
| --- |
| `fontFamily(string $value): self` |
| `fontSize(string $value): self` |
| `fontStyle(string $value): self` |
| `fontWeight(string $value): self` |
| `letterSpacing(string $value): self` |
| `lineHeight(string $value): self` |
| `textIndent(string $value): self` |
| `textAlign(string $value): self` |
| `textColumns(string $value): self` |
| `textDecoration(string $value): self` |
| `writingMode(string $value): self` |
| `textTransform(string $value): self` |
| `textShadow(string $value): self` |

## Margin and padding

`spacing()->margin()` and `spacing()->padding()` return `Styles\BoxSpacing`:

| Method |
| --- |
| `top(string $value): self` |
| `right(string $value): self` |
| `bottom(string $value): self` |
| `left(string $value): self` |
| `shorthand(array $values): self` |
| `vertical(string $value): self` |
| `horizontal(string $value): self` |
| `verticalSides(string $top, string $bottom): self` |
| `horizontalSides(string $right, string $left): self` |

`shorthand()` takes a list of one to four strings, following CSS side order.
It expands them into individual sides. `vertical()` sets top and bottom;
`horizontal()` sets right and left. Borders expose `top()`, `right()`, `bottom()`,
and `left()` builders for per-side properties.

## Shared property-builder methods

Property builders using `CommonTrait` expose `property(string $property, string
$value): self` to set a property within their existing category, and `toArray()`
and `jsonSerialize()` to inspect their local property values. `toArray()` is not
the complete root configuration. Prefer the typed property setters. For generic
schema paths, use the root `ThemeJson` escape hatches described in [the API overview](api.md).

Get builders through `ThemeJson`; creating property builders without a context
does not provide the intended connection to the generated output.

## Standalone CSS parsers

`Styles\Css` offers `parse(string $css, string $selector = ''): string`,
`expanded()`, and `compressed()`. `Styles\Scss` offers the corresponding `parse()`
method, `expanded()`, and `compress()`. These format strings; theme configuration
normally uses the root styles facade instead of constructing parsers directly.

Generate the local [schema API inventory](../../CONTRIBUTING.md#schema-api-inventory)
for property-to-schema mappings, and check your target WordPress version's support.
