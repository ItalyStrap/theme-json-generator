# Generate your first theme.json

[Documentation](../README.md) · Previous: [Installation](installation.md) · Next: [Workflow](workflow.md)

This example creates a theme configuration with one color, one font size, and
default text styles. Start in your theme root after [installing the package](installation.md).

## 1. Check the theme header

If you are creating a new theme, add its metadata to `style.css`:

```css
/*
Theme Name: Example Theme
*/
```

Keep the existing header when working on an existing theme. The generator checks
this file when writing JSON; this example does not create the other files needed
for a complete WordPress theme.

## 2. Create the PHP entrypoint

Create `theme.json.php` in the theme root:

```php
<?php

declare(strict_types=1);

use ItalyStrap\ThemeJsonGenerator\ThemeJson;

return static function (ThemeJson $themeJson): void {
    $themeJson->schema('https://schemas.wp.org/wp/6.6/theme.json');
    $themeJson->version(3);
    $themeJson->title('Example Theme');

    $themeJson->settings()->color()->addColor('primary', 'Primary', '#2457ab');
    $themeJson->settings()->typography()->addFontSize('body', 'Body', '1rem');

    $themeJson->styles()->color()->text('color.primary');
    $themeJson->styles()->typography()->fontSize('fontSize.body');
};
```

The schema URL is a pinned example. Choose the schema and format version for your
target WordPress release using the [WordPress reference](https://developer.wordpress.org/block-editor/reference-guides/theme-json-reference/).
Adding a newer setting does not add support to an older WordPress installation.
The CLI injects `ThemeJson` into the callable.

## 3. Generate the JSON

```shell
vendor/bin/theme-json dump
```

The command writes `theme.json` beside the entrypoint. Among the generated values
you will find:

```json
{
    "settings": {
        "color": {
            "palette": [
                { "slug": "primary", "name": "Primary", "color": "#2457ab" }
            ]
        }
    },
    "styles": {
        "color": { "text": "var(--wp--preset--color--primary)" },
        "typography": { "fontSize": "var(--wp--preset--font-size--body)" }
    }
}
```

This excerpt omits the other root fields and the font-size definition. Preset
keys such as `color.primary` resolve to CSS variable references.

Edit `theme.json.php`, then run `dump` again. Direct edits to generated JSON are
overwritten on the next generation.

## Starting with existing JSON

If your theme already has `theme.json`, run:

```shell
vendor/bin/theme-json init
```

`init` imports discovered JSON files into corresponding PHP entrypoints without
overwriting existing entrypoints. It does not create a missing `theme.json` or
scaffold a new theme. The generated callable uses a compatibility import; update
it to `ItalyStrap\ThemeJsonGenerator\ThemeJson` when editing the file.

You can gradually replace imported array configuration with typed methods.
Continue with [the workflow](workflow.md) and [entrypoints](../configuration/entrypoints.md).
