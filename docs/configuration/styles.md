# Styles and scopes

[Documentation](../README.md) · Previous: [Typography](typography.md) · Next: [Templates](templates.md)

Style methods write CSS values into the current `theme.json` scope. Use values
such as `#2457ab`, `1rem`, `inherit`, or a registered preset key. Register referenced
presets first. The following snippets belong inside an entrypoint callable.

## Global defaults

```php
$themeJson->settings()->color()->addColor('primary', 'Primary', '#2457ab');
$themeJson->styles()->color()->text('color.primary')->background('#ffffff');
$themeJson->styles()->typography()->lineHeight('1.6');
$themeJson->styles()->spacing()->padding()->horizontal('1rem');
```

Each call to `styles()` starts at the root styles scope. Calls write immediately
to the shared root configuration; you do not need to collect builder arrays or
attach them later.

Chains stay within a builder. For example, after `color()->text()`, the result is
a color builder. Start again from `styles()` to configure typography or spacing.

## Blocks, elements, and states

```php
$themeJson->styles()->blocks('core/paragraph')->typography()->fontSize('1rem');
$themeJson->styles()->elements('link')->color()->text('color.primary');
$themeJson->styles()->elements('link')->state(':hover')->color()->text('#17396f');
$themeJson->styles()->blocks('core/button')->elements('link')
    ->typography()->fontWeight('700');
```

The paragraph example writes to:

```json
{
    "styles": {
        "blocks": {
            "core/paragraph": { "typography": { "fontSize": "1rem" } }
        }
    }
}
```

Use the block's full registered name. `state()` takes a single pseudo-class name
such as `:hover`, rather than an arbitrary selector. A state must follow a block
or element scope. Availability of elements and states in WordPress depends on
the schema and WordPress version you target.

## Block style variations

```php
$themeJson->styles()->blocks('core/button')->variations('outline')
    ->border()->width('2px');
```

This writes a named variation under the block's styles. It does not create or
register the block style itself. The block style must be available to the theme.
This nested variation API is separate from full [style variation JSON files](../organization/style-variations.md).

Supported scope transitions are:

| Current scope | Next scopes |
| --- | --- |
| Root styles | Block, element, variation |
| Block | Element, variation, state |
| Element | State |
| Variation | Block, element |
| State | No further nested scope |

Use supported schema paths for your target WordPress release even when an API
transition permits more combinations. To configure a sibling, start from a saved
parent builder or from `styles()` again.

## Borders, spacing, and shadows

```php
$themeJson->styles()->blocks('core/button')->border()
    ->color('color.primary')->style('solid')->width('1px')->radius('0.25rem');
$themeJson->styles()->spacing()->margin()->vertical('0');
$themeJson->styles()->spacing()->padding()->shorthand(['1rem', '2rem']);
$themeJson->styles()->shadow('0 2px 8px rgba(0,0,0,0.15)');
```

Use `border()->top()` and its sibling methods for individual sides. Spacing
shorthand accepts a list of one to four values and writes individual sides.

See [custom CSS](../assets/custom-css.md) for rules that do not fit typed style
methods, and the [Styles API](../reference/styles.md) for the complete method list.
