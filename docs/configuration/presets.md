# Presets and references

[Documentation](../README.md) · Previous: [Settings](settings.md) · Next: [Typography](typography.md)

A preset is a named, reusable value. Its slug identifies it in configuration and
in WordPress-generated CSS references. Register presets before using their keys
in styles or custom CSS.

## Register through settings

Inside your entrypoint callable:

```php
$themeJson->settings()->color()->addColor('primary', 'Primary', '#2457ab');
$themeJson->settings()->typography()->addFontSize('body', 'Body', '1rem');
$themeJson->settings()->spacing()->addSpacingSize('section', 'Section', '3rem');

$themeJson->styles()->color()->text('color.primary');
$themeJson->styles()->typography()->fontSize('fontSize.body');
$themeJson->styles()->spacing()->padding()->top('spacing.section');
```

The first argument is the slug; the second is the human-readable name. The final
argument is the value. The CLI writes registered presets into the matching
settings collections after your callable runs.

## Understand reference forms

| Form | Example | Use |
| --- | --- | --- |
| Preset key | `color.primary` | A complete value in a style method |
| Placeholder | `{{color.primary}}` | A reference inside a longer string |
| CSS variable | `var(--wp--preset--color--primary)` | A literal CSS reference |
| WordPress reference | `var:preset\|color\|primary` | Passed through for WordPress to interpret |

For example:

```php
$themeJson->styles()->border()->color('color.primary');
$themeJson->styles()->css('.brand { border: 1px solid {{color.primary}}; }');
```

Both resolve to the registered color's CSS variable. A missing plain key is kept
as a literal string, so a typo may produce an invalid CSS value. A missing
`{{…}}` placeholder throws an exception. Generic `ThemeJson::set()` paths do not
automatically perform the same reference resolution as style builders.

## Work with preset objects

For reuse in PHP, inject the shared collection and add objects:

```php
<?php

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Color;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

return static function (ThemeJson $themeJson, PresetsInterface $presets): void {
    $themeJson->schema('https://schemas.wp.org/wp/6.6/theme.json');
    $themeJson->version(3);

    $primary = new Color('primary', 'Primary', new CssColor('#2457ab'));
    $presets->add($primary);

    $themeJson->styles()->color()->text($primary->var());
};
```

A preset exposes `type()`, `slug()`, `ref()`, `prop()`, `var($fallback)`, and
`toArray()`. For an ordinary preset, casting to string returns its CSS variable.
Custom preset objects are different: casting returns their stored value; call
`var()` when you need their CSS variable reference.

Use `get('color.primary')` to retrieve a registered object. `addMultiple()` adds
a list of preset objects. `presets()` iterates the collection's preset leaves.

## Name and scope presets

Ordinary slugs must be non-empty and contain only ASCII letters, digits, and
hyphens. Prefer lowercase hyphenated slugs to keep CSS names predictable. A preset
path can only be registered once; choose unique slugs within each type and scope.
Custom values support nested paths instead of ordinary preset slug validation.

Block presets are registered through a block settings builder:

```php
$themeJson->settings()->blocks('core/button')->color()
    ->addColor('accent', 'Accent', '#ab2457');
$themeJson->styles()->blocks('core/button')->color()
    ->text('blocks.core/button.color.accent');
```

The collection key includes the block name, while the CSS variable name remains
based on the preset type and slug. Check that the target WordPress version and
block support the setting and style you configure.

See [preset constructors](../reference/presets.md), [color palettes](../assets/colors.md),
and [SCSS reference export](../assets/scss-export.md).
