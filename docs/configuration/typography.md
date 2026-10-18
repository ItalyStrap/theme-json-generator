# Typography and fonts

[Documentation](../README.md) · Previous: [Presets](presets.md) · Next: [Styles](styles.md)

Typography settings provide font choices and editor controls. Typography styles
select their default values. Examples in this chapter go inside an entrypoint
callable unless a complete file is shown.

## Register a family and size

```php
$themeJson->settings()->typography()
    ->addFontFamily('body', 'Body', 'system-ui, sans-serif')
    ->addFontSize('body', 'Body', '1rem');

$themeJson->styles()->typography()
    ->fontFamily('fontFamily.body')
    ->fontSize('fontSize.body')
    ->lineHeight('1.6');
```

Preset types are `fontFamily` and `fontSize`, while the JSON collection names are
`fontFamilies` and `fontSizes`. See [presets and references](presets.md) for the
relationship between those names and CSS variables.

## Describe bundled font files

Use a `FontFace` object when a family has downloadable font files:

```php
<?php

use ItalyStrap\ThemeJsonGenerator\Settings\Typography\Utilities\FontFace;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

return static function (ThemeJson $themeJson): void {
    $themeJson->schema('https://schemas.wp.org/wp/6.6/theme.json');
    $themeJson->version(3);

    $themeJson->settings()->typography()->addFontFamily(
        'body',
        'Body',
        '"Example Sans", sans-serif',
        new FontFace(
            fontFamily: 'Example Sans',
            fontWeight: '100 900',
            fontStyle: 'normal',
            fontStretch: '',
            src: ['file:./assets/fonts/example-sans.woff2'],
            fontDisplay: 'swap'
        )
    );
};
```

Replace the source with a real font file in your theme and include that file in
the deployed theme. The generator serializes this metadata; it does not copy or
download fonts. Add more `FontFace` arguments for separate weights or styles.
`src` accepts a non-empty string or list of non-empty strings. Supported display
values are `auto`, `block`, `fallback`, `swap`, and `optional`. The [WordPress
typography guide](https://developer.wordpress.org/themes/global-settings-and-styles/settings/typography/)
explains how WordPress uses these font definitions.

## Configure fluid typography

For global fluid typography, choose either a boolean or an options object:

```php
$themeJson->settings()->typography()->enableFluid();
```

Alternatively, configure the object on a fresh entrypoint:

```php
$themeJson->settings()->typography()->fluid()
    ->minFontSize('0.875rem')
    ->minViewportWidth('320px')
    ->maxViewportWidth('1440px');
```

Do not combine these alternatives: the API rejects replacing an already configured
boolean with an object, or an object with a boolean.

Use the separate per-preset `Utilities\Fluid` class to specify a size range:

```php
<?php

use ItalyStrap\ThemeJsonGenerator\Settings\Typography\Utilities\Fluid;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

return static function (ThemeJson $themeJson): void {
    $themeJson->schema('https://schemas.wp.org/wp/6.6/theme.json');
    $themeJson->version(3);

    $themeJson->settings()->typography()->enableFluid();
    $themeJson->settings()->typography()->addFontSize(
        'heading', 'Heading', '2rem', new Fluid('1.5rem', '3rem')
    );
    $themeJson->settings()->typography()->addFontSize(
        'manual', 'Manual', 'clamp(1rem, 2vw, 2rem)', false
    );
};
```

Pass `false` to opt a preset out of fluid generation. When global fluid typography
is active, a size already containing `clamp()` must opt out explicitly. Registering
the conflicting size first does not bypass this check.

See the [Settings API](../reference/settings.md) for typography controls and the
[preset reference](../reference/presets.md) for font descriptors.
