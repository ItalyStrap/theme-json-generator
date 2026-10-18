# Settings

[Documentation](../README.md) · Previous: [Entrypoints](entrypoints.md) · Next: [Presets](presets.md)

Settings define editor capabilities, available presets, and layout defaults.
Styles define the values applied to the theme. Enabling a color control does not
choose a text color; registering a color makes it available to use.

## Configure editor controls

Inside your entrypoint callable:

```php
$themeJson->settings()->enableAppearanceTools();
$themeJson->settings()->color()->disableCustom()->disableDefaultPalette();
$themeJson->settings()->spacing()->enablePadding()->enableMargin();
$themeJson->settings()->spacing()->units('px', 'rem', '%');
```

These calls write values under `settings`. An excerpt of the result is:

```json
{
    "settings": {
        "appearanceTools": true,
        "color": { "custom": false, "defaultPalette": false },
        "spacing": { "padding": true, "margin": true, "units": ["px", "rem", "%"] }
    }
}
```

Most switches have matching `enable…()` and `disable…()` methods. Disabling writes
`false`; it does not remove a previously configured property. `disableBlockGapAndLayoutStyles()`
is a special case that writes `null` for the block-gap setting.

## Set layout dimensions

```php
$themeJson->settings()->layout()->contentSize('42rem')->wideSize('72rem');
$themeJson->settings()->layout()->disableCustomContentAndWideSize();
```

Layout sizes accept literal values or registered preset keys. Register a preset
before referring to it.

## Scope settings to a block

```php
$themeJson->settings()->blocks('core/paragraph')->color()->disableCustom();
$themeJson->settings()->blocks('core/heading')->typography()->enableFontWeight();
```

These calls write to `settings.blocks.<block-name>`. A new call to `settings()`
starts at global settings. Keep block builders in separate variables when needed;
you cannot nest one settings block scope inside another.

## Define spacing values and scales

```php
$themeJson->settings()->spacing()->addSpacingSize('section', 'Section', '3rem');
$themeJson->settings()->spacing()->scale()
    ->operator('*')
    ->increment(1.5)
    ->steps(7)
    ->mediumStep(1.5)
    ->unit('rem');
```

The scale API writes `settings.spacing.spacingScale`. WordPress generates the
scale's values; the generator does not expand the scale into explicit presets.
Use explicit spacing sizes when you need those values in exported SCSS references.

## Add custom values

```php
$themeJson->settings()->custom()->add(['measure', 'reading'], '42rem');
$themeJson->settings()->custom()->addMultiple([
    'transition' => ['fast' => '150ms', 'slow' => '300ms'],
]);
$themeJson->styles()->dimensions()->width('custom.measure.reading');
```

Custom values are non-empty strings. Nested arrays in `addMultiple()` form nested
custom settings. Avoid assigning both a value and a subtree to the same path.
The example width resolves to `var(--wp--custom--measure--reading)`.

Use [presets](presets.md) for reusable named colors, spacing, dimensions, borders,
shadows, and typography. See the [Settings API](../reference/settings.md) for all
available categories, including background, visibility, viewport, and lightbox.
Availability in WordPress depends on the version you target.
