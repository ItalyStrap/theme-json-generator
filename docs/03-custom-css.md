# Custom CSS in theme.json

WordPress handles global and scoped custom CSS differently. The generator keeps
those two formats separate and validates scoped CSS before writing it to
`theme.json`.

Use `styles()->css()` and `styles()->appendCss()` for global CSS, or call the
same methods after `blocks()` or `elements()` for scoped CSS. The first argument
contains the CSS. The optional second argument declares its root selector,
which the generator removes and converts into WordPress `&` scoped syntax.

## The Selector Argument

`css()`, `appendCss()`, `scss()`, and `appendScss()` accept an optional selector
as their second argument:

```php
$styles->css($css, $selector);
```

Without it, the generator expects CSS already written for its destination:

- global CSS uses complete rules;
- scoped CSS uses declarations and nested rules beginning with `&`.

With it, the generator accepts regular CSS rooted at that selector and converts
it to the scoped format required by WordPress:

```php
$button->css(
    '.wp-block-button { color: red; }'
    . '.wp-block-button a { color: blue; }',
    '.wp-block-button'
);
```

This produces a scoped value equivalent to:

```css
color: red;
& a {
    color: blue;
}
```

Every input selector must be the supplied selector or structurally belong to
it. Unrelated selectors cause an `InvalidArgumentException`.

## Global CSS

Global CSS is stored in `styles.css`. WordPress adds it to the stylesheet
without changing it, so write complete CSS rules:

```php
$themeJson
    ->styles()
    ->css('body { color: red; }');
```

Use `appendCss()` to add more global rules without replacing existing CSS:

```php
$styles = $themeJson->styles();

$styles->css('body { color: red; }');
$styles->appendCss('a { color: blue; }');
```

The resulting `theme.json` contains:

```json
{
    "styles": {
        "css": "body { color: red; }a { color: blue; }"
    }
}
```

Global CSS is the correct place for independent selectors, at-rules, and rules
that are not owned by a block or element:

```php
$themeJson->styles()->css(
    '@media (min-width: 48rem) { .card_title { display: grid; } }'
);
```

## Scoped CSS

CSS configured through `blocks()` or `elements()` is scoped by WordPress:

```php
$button = $themeJson
    ->styles()
    ->blocks('core/button');

$button->css('color: red;');
$button->appendCss('& a { color: blue; }');
```

Scoped CSS uses the format expected by `WP_Theme_JSON`:

- declarations without braces apply to the current block or element;
- each nested rule starts with `&`;
- WordPress replaces `&` with its resolved selector.

The stored value is:

```css
color: red;& a { color: blue; }
```

WordPress then generates selectors for the configured block. The exact root
selector comes from WordPress block metadata.

### Right Syntax

Apply declarations to the current scope:

```php
$button->css('color: red; padding: 1rem;');
```

Target a descendant:

```php
$button->appendCss('& .icon { color: blue; }');
```

Target a state:

```php
$button->appendCss('&:hover { color: blue; }');
```

Target a child or sibling:

```php
$button->appendCss('& > span { display: block; }');
$button->appendCss('& + .notice { margin-top: 1rem; }');
```

### Wrong Syntax

A nested scoped rule without `&` is invalid:

```php
// Wrong: WordPress would concatenate this with the root declarations.
$button->appendCss('a { color: blue; }');
```

The generator throws instead of allowing WordPress to produce malformed CSS.
Write this:

```php
$button->appendCss('& a { color: blue; }');
```

An independent selector does not belong inside scoped CSS:

```php
// Wrong: .card_title is not inside the .card scope.
$card->css('.card { color: red; } .card_title { color: blue; }', '.card');
```

Choose the intended relationship explicitly:

```php
// Descendant: .card .card_title
$card->css('.card { color: red; } .card .card_title { color: blue; }', '.card');

// Independent selector: write it as global CSS.
$themeJson->styles()->css('.card_title { color: blue; }');
```

The same rule applies to hyphenated names:

```php
// Wrong: .card-title is a separate class, not a descendant of .card.
$card->css('.card-title { color: blue; }', '.card');
```

Sharing a string prefix does not make selectors part of the same scope.

## Selector Scope

When using the selector argument, every selector in the input must be the scope
itself or structurally belong to it:

```css
/* Accepted */
.card
.card:hover
.card .title
.card > .title
.card + .notice

/* Rejected */
.other
.card-title
.card_title
```

Rejected selectors cause an `InvalidArgumentException`. The message identifies
the selector and recommends either a descendant selector or global CSS. Nothing
is silently discarded.

## appendCss()

`css()` writes the scoped CSS value. `appendCss()` preserves that value and adds
the new declarations or nested rules:

```php
$button->css('color: red;');
$button->appendCss('&:hover { color: blue; }');
```

Use the same syntax consistently:

- without the selector argument, provide WordPress scoped syntax yourself;
- with the selector argument, provide regular CSS rooted at that selector.

```php
$button->css('.button { color: red; }', '.button');
$button->appendCss('.button:hover { color: blue; }', '.button');
```

## SCSS

`scss()` and `appendScss()` compile SCSS first, then apply the same scope
validation as CSS:

```php
$button->scss(
    '.button {'
    . '  color: red;'
    . '  &:hover { color: blue; }'
    . '}',
    '.button'
);
```

Compiled selectors must still belong to the requested scope. For example,
SCSS `&__title` compiles to the independent class `.card__title`; it is not a
descendant of `.card` and is rejected in `.card`-scoped CSS.

Use an actual descendant when that is the intended result:

```scss
.card {
    .card__title {
        color: blue;
    }
}
```

Use global `scss()` when the compiled stylesheet contains independent
selectors.

## At-rules

WordPress does not preserve `@media` or `@supports` correctly inside scoped
block custom CSS. The generator rejects scoped at-rules:

```php
// Wrong: rejected in a block or element scope.
$button->css('@media (min-width: 48rem) { color: blue; }');
```

Place the complete rule in global CSS instead:

```php
$themeJson->styles()->css(
    '@media (min-width: 48rem) { .wp-block-button { color: blue; } }'
);
```

## Summary

| Goal | API and syntax |
| --- | --- |
| Complete independent rules | Global `styles()->css()` |
| Add another independent rule | Global `styles()->appendCss()` |
| Style the current block or element | Scoped `css('color: red;')` |
| Style a descendant or state | Scoped `appendCss('& ... { ... }')` |
| Convert regular scoped CSS | Pass its root selector as the second argument |
| Add scoped at-rules | Not supported; use global CSS |
| Use independent selectors in scoped CSS | Not supported; use global CSS |
