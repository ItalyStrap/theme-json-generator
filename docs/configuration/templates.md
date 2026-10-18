# Templates, template parts, and patterns

[Documentation](../README.md) · Previous: [Styles](styles.md) · Next: [Configuration classes](../organization/configurators.md)

The root API can register metadata for templates and template parts, plus slugs
from the WordPress Pattern Directory. These methods describe existing content;
they do not generate HTML files.

## Custom templates

Inside your entrypoint callable:

```php
$themeJson->customTemplates()->addTemplate(
    'landing', 'Landing page', ['page']
);
```

Create the corresponding `templates/landing.html` in the theme. The output is:

```json
{
    "customTemplates": [
        { "name": "landing", "title": "Landing page", "postTypes": ["page"] }
    ]
}
```

The post-types list is optional and is omitted when empty. Name and title must
be non-empty strings. Repeated calls append entries.

## Template parts

```php
$themeJson->templateParts()->addPart('header', 'header', 'Site header');
$themeJson->templateParts()->addPart('footer', 'footer', 'Site footer');
```

Create `parts/header.html` and `parts/footer.html` separately. `addPart()` takes
the name, area, and optional title in that order. The default area is
`uncategorized`; a `null` title is omitted. Empty names, areas, and explicit empty
titles are rejected.

## Pattern Directory entries

```php
$themeJson->patterns()->add('example-pattern-slug');
```

Replace the example with a real Pattern Directory slug. This adds a string to
the root `patterns` collection. It does not load theme-local pattern PHP files
or register arbitrary block markup.

## Target blocks in a variation file

```php
$themeJson->blockTypes()->add('core/button');
```

`blockTypes` identifies target blocks for a block style variation file. Use it
when authoring that kind of variation, rather than as a list of blocks enabled
for your entire theme. `blockTypes()->add()` and `patterns()->add()` reject empty
strings.

See the [root API reference](../reference/api.md) for signatures and
[style variation files](../organization/style-variations.md) for entrypoint organization.
