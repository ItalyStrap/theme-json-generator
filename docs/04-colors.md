# Colors

## Comparing Colors

`CssColor::equals()` accepts a supported color string or a `CssColorInterface`
instance. It compares normalized 8-bit RGB channels and alpha exactly, without
comparing formatted strings or applying a tolerance. Different notation, spacing,
and hex casing do not affect equality when the normalized channels match.

Compatibility: `equals(string|CssColorInterface $color): bool` is now required
by `CssColorInterface`. Third-party implementations must add this method before
upgrading; this is a breaking interface change for those implementations.

```php
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;

$red = new CssColor('#ff0000');

$red->equals('rgb(255,0,0)'); // true
$red->equals('hsla(0,100%,50%,1)'); // true
$red->equals(new CssColor('hsl(0,100%,50%)')); // true
$red->equals('rgba(255,0,0,0.5)'); // false: different alpha
```

Equality is defined by the normalized channels, not perceptual similarity.
Even fully transparent colors with different RGB channels compare unequal.
Alpha is compared before display rounding: `0.501` and `0.504` are unequal,
although both format as `0.50` in an RGBA string. Hex alpha is normalized by
dividing its byte value by 255, so `#ff000080` is not exactly equal to
`rgba(255,0,0,0.5)`.

## Conversion Precision

Conversions between `CssColor` objects retain the internal RGBA channels when
the target format supports alpha. `toHsl()`, `toRgb()`, and `toHex()` produce
opaque colors; overriding alpha in `toHsla()` or `toRgba()` changes equality.

Serializing a color to a string can lose precision. The current Spatie formatter
rounds HSL/HSLA hue, saturation, and lightness to integers and RGBA/HSLA alpha to
two decimal places. Parsing that string creates a new color from the rounded
values; the original channels cannot always be recovered.

```php
$yellow = new CssColor('#ffc107');
$hsla = $yellow->toHsla();

$yellow->equals($hsla); // true: preserved internal channels
$yellow->equals('hsla(45,100%,51%,1)'); // false: independently parsed color
$yellow->equals((string)$hsla); // false: the same rounded HSLA string

(string)$yellow->toRgb(); // rgb(255,193,7)
(string)(new CssColor('hsla(45,100%,51%,1)'))->toRgb(); // rgb(255,193,5)
```

Use independent strings known to normalize to the same channels when testing
cross-format equality. Arbitrary rounded HSL/HSLA strings are not exact
equivalents of their source RGB colors. Keeping the object avoids serialization
loss; exact string round trips would require different parsing and formatting.

## Color Generators

ColorWheelRotation is the generic engine: "given degrees, generate colors."

AnalogousColorsExperimental, TriadicColorsExperimental, etc. are semantic wrappers that provide known angle sets; we can call them "recipes".

That is a good design if callers may need both:

- custom rotations directly
- named color harmony generators for convenience/type clarity.

