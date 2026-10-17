[Previous: Basic Usage](./01-basic-usage.md)

# Introduction

Venture beyond the basics into the thrilling world of advanced theme development with the ItalyStrap Theme Json Generator. This section unlocks the tool's full potential, guiding you through advanced configurations and techniques that elevate your work.

Whether you're aiming to refine the settings with precise color schemes, typography, or style elements, this guide will walk you through the process, ensuring your themes are both dynamic and robust. Let's enhance your theme development journey together.

## Understanding the Project Structure

The public configuration API lives under `src/`, in the `ItalyStrap\ThemeJsonGenerator` namespace. CLI application and infrastructure code live under `cli/`.

## The Configuration API

> All classes with `Experimental` suffix may change the name and the logic in the future.

The configuration API mirrors the `theme.json` schema. Its main directories are:

* **Settings:** Contains classes for detailed theme settings like color, typography, and custom presets, facilitating granular control over theme appearance.
* **Styles:** Dedicated to defining the theme's visual styles, including border, color, typography, spacing and so on to configurations.

Use the `ThemeJson` root object to access the settings, styles, and top-level collection APIs.

## Settings

### Presets Class

The `Presets` class is a collection of `Preset` objects.

Include the `Preset` class to the signature of your configuration file:

```php

declare(strict_types=1);

namespace YourVendor\YourProject;

use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

return static function (ThemeJson $themeJson, Presets $presets): void {
    // ...
};
```

Now we can add presets objects to the `$presets` instance.

### Using Presets Classes

All `Preset` classes allow for the detailed customization of theme settings, facilitating the definition of color schemes, typography, and more through a structured approach.

#### Color Palette

Define color palettes using the `Color` preset class:

```php
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Color;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;

$presets
    // `add` method is used to add the preset instance to the collection
    ->add(new Color(
        'base',
        'Brand Base Color',
        new CssColor('#3986E0')
    ))
    ->add(new Color(
        'bodyColor',
        'Body Text Color',
        new CssColor('#000000')
    ));
```

`CssColor` represents a parsed CSS color using the formats supported by Spatie Color. `Color` wraps that value as a named WordPress preset.

The `Color` preset accepts three parameters:

1. string Slug.
2. string Name.
3. object implementing `CssColorInterface`.

#### Gradient

Create gradients with the `Gradient` class:

```php

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Gradient;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\LinearGradient;

$presets
    ->add(new Gradient(
        'base-to-white',
        'Base to white',
        (new LinearGradient())
            ->direction('135deg')
            ->colorStop($presets->get('color.base'))
            ->colorStop('#ffffff')
    ));
```

The `LinearGradient` is a Value Object that represents a linear gradient in the CSS format.

Set its direction with `direction()` and add at least two stops with `colorStop()`. Stops accept a `Color` preset, a `CssColorInterface` instance, or a supported color string. Preset stops retain their CSS variable references.

The `Gradient` accepts three parameters:

1. string Slug.
2. string Name.
3. object that implements `GradientInterface`.

As you can see in the example above we can access value from the `$presets` object using the `get` method.
The `get` method accepts a string as a parameter and returns a `PresetInterface` object, in this case, a `Color` preset.
The string used as key has the form `type.slug` where `type` is the type of the preset and `slug` is the slug of the preset.

#### Duotone

Create duotone colors with the `Duotone` class:

```php
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Duotone;

$presets
    ->add(new Duotone(
        'base-to-bodyColor',
        'Base to Body Color',
        $presets->get('color.base'),
        $presets->get('color.bodyColor')
    ));
```

The `Duotone` accepts a slug, a name, and at least two `Color` presets:

1. string Slug.
2. string Name.
3. object `Color`.
4. ... more `Color` presets.

#### Typography Presets: FontFamily, and FontSize

Configure typography presets with the `FontFamily` and `FontSize` classes:

```php
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\FontFamily;use ItalyStrap\ThemeJsonGenerator\Settings\Typography\FontSize;

$presets->add(new FontSize('base', 'Base Font Size', '16px'));

$presets->add(new FontFamily('default', 'Default Font Family', 'sans-serif'));
```

#### Custom

Custom preset is a little bit different from the others:

```php
use ItalyStrap\ThemeJsonGenerator\Settings\Custom\Custom;

$presets->add(new Custom('contentSize', 'clamp(16rem, 60vw, 60rem)'));
```

The `Custom` class accepts two parameters:

1. string Slug.
2. string Value.

Customs can be added to the `$presets` collection using the `addMultiple` method, which accepts an array of `PresetInterface` objects:

```php
use ItalyStrap\ThemeJsonGenerator\Settings\Custom\Custom;

$presets
    ->addMultiple([
        new Custom('contentSize', 'clamp(16rem, 60vw, 60rem)'),
        new Custom('wideSize', 'clamp(16rem, 85vw, 70rem)'),
        new Custom('baseFontSize', '{{fontSize.base}}'),
        new Custom('spacer.base', '1rem'),
    ]);
```

In the example above you see a method called `addMultiple` that accepts an array of `PresetInterface` objects.

Once you've added the various Preset instances to the `Presets` collection, the resulting object is internally utilized by the CLI. Developers need not take any further action; there's no need to manually integrate this object elsewhere in your project.

## Styles

Access style builders through `$themeJson->styles()`. Their fluent methods write to the root object's configuration and return a builder for further calls.

In this directory, you'll find classes tailored to different aspects of theme styling, aligned with the `theme.json` structure. These classes, such as `Border`, `Color`, `Css`, `Outline`, `Spacing`, `Typography`, and more, offer specific methods for configuring corresponding style properties, all method declared in each class follows the `theme.json` schema and each class has its own methods following the properties they represent.

Use literal CSS values or variables directly:

```php
$themeJson->styles()->color()
    ->background('var(--wp--preset--color--body-bg)')
    ->text('var(--wp--preset--color--body-color)');
```

As you can see in the example above the key passed to the method will be passed through and used as value in the JSON file.

```json
{
  "styles": {
    "color": {
      "background": "var(--wp--preset--color--body-bg)",
      "text": "var(--wp--preset--color--body-color)"
    }
  }
}
```

But we made some effort to initialize all `Presets`, how can we use them in the `Styles` section?

The root object already shares the preset collection with its style builders. Pass a registered preset key to resolve its CSS variable:

```php
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Color;

$themeJson->styles()->color()
    ->background(Color::TYPE . '.base')
    ->text(Color::TYPE . '.bodyColor');
```

In this case the key passed to the method will be used as a key in the `$presets` collection, the `key` has the format `type.slug` where `type` is the type of the preset and `slug` is the slug of the preset.

In JSON format:

```json
{
  "styles": {
    "color": {
      "background": "var(--wp--preset--color--base)",
      "text": "var(--wp--preset--color--body-color)"
    }
  }
}
```

Use scoped builders for individual elements or blocks:

```php

$themeJson->styles()->elements('link')->color()->text('color.base');
$themeJson->styles()->blocks('core/paragraph')->typography()->fontSize('fontSize.base');
```

Each call to `styles()` starts at the root styles context. Retrieve builders through that API rather than constructing style classes without their required context.

You can find an implementation example in the [tests/_data/fixtures/advanced-example.json.php](../tests/_data/fixtures/advanced-example.json.php) file.

### Custom CSS for Global Styles and per Block

More information about the `css` field can be found in:

* [WordPress 6.2 release notes](https://wordpress.org/news/2023/03/dolphy/).
* [Custom CSS for Global Styles and per Block](https://make.wordpress.org/core/2023/03/06/custom-css-for-global-styles-and-per-block/).
* [Per Block CSS with theme.json](https://developer.wordpress.org/news/2023/04/21/per-block-css-with-theme-json/).
* [Global Settings and Styles](https://developer.wordpress.org/themes/global-settings-and-styles/).
* [How to use custom CSS in theme.json - fullsiteediting.com](https://fullsiteediting.com/lessons/how-to-use-custom-css-in-theme-json/).

The introduction of the `css` field in [WordPress 6.2](https://wordpress.org/news/2023/03/dolphy/) enables the addition of [custom CSS](https://make.wordpress.org/core/2023/03/06/custom-css-for-global-styles-and-per-block/) directly within the `theme.json` file, both globally under `styles.css` and per block within `styles.blocks.[block-name].css`. Use `ItalyStrap\ThemeJsonGenerator\Styles\Css` or `ItalyStrap\ThemeJsonGenerator\Styles\Scss` and their `parse(string $css, string $selector = ''): string` method to format rules using the `&` separator.

This method accepts two parameters: the CSS to parse and an optional selector to scope the CSS rules accordingly.

How It Works

The `Css`|`Scss` class efficiently parses the provided CSS, extracting and formatting rules based on the specified selector. This functionality ensures that the output is fully compatible with the `theme.json` structure, enhancing the flexibility and customization of theme development.

So, let's see some examples:

```php
// Result: 'height: 100%;'
echo (new Css($presets))->parse('.test-selector{height: 100%;}', '.test-selector');
```

```php
// Result: 'height: 100%;width: 100%;color: red;&:hover {color: red;}&::placeholder {color: red;}'
echo (new Css($presets))->parse('.test-selector{height: 100%;width: 100%;color: red;}.test-selector:hover {color: red;}.test-selector::placeholder {color: red;}', '.test-selector');
```

`Css` requires the preset collection. `Scss` requires a `Css` instance, a `ScssPhp\ScssPhp\Compiler` instance, and the preset collection. These services can also be retrieved through the injected container.

As the name suggests, the `Scss` class is used to parse SCSS styles, so you are free to write your styles in SCSS format and let the class handle the conversion for you.

Let's see it in action:

```php
use ItalyStrap\ThemeJsonGenerator\Styles;
use ItalyStrap\ThemeJsonGenerator\Styles\Css;

[
    Styles::SECTION => [
        'css' => $container->get(Css::class) // Or (new Css($presets)) or (new Scss(new Css($presets), $scssCompiler, $presets))
            ->parse('.test-selector{height: 100%;width: 100%;color: red;}.test-selector:hover {color: red;}.test-selector::placeholder {color: red;}', '.test-selector'),
    ],
];
```

For block style:

```php
use ItalyStrap\ThemeJsonGenerator\Styles;
use ItalyStrap\ThemeJsonGenerator\Styles\Css;

[
    Styles::SECTION => [
        'blocks' => [
            'my-namespace/test-block' => [
                'css' => $container->get(Css::class) // Or (new Css($presets)) or (new Scss(new Css($presets), $scssCompiler, $presets))
                    ->parse('.test-selector{height: 100%;width: 100%;color: red;}.test-selector:hover {color: red;}.test-selector::placeholder {color: red;}', '.test-selector'),
            ],
        ],
    ],
];
```

All methods also support a special syntax to resolve value in the `$presets` collection, `{{type.slug}}`, this syntax will be used internally to find the value in the `$presets` collection registered before.

```php
use ItalyStrap\ThemeJsonGenerator\Styles;
use ItalyStrap\ThemeJsonGenerator\Styles\Css;

[
    Styles::SECTION => [
        'css' => $container->get(Css::class) // Or (new Css($presets))
            ->parse('.test-selector{color: {{color.base}};}', '.test-selector'),
    ],
];
```

The `{{color.base}}` will be replaced with the value of the `color.base` previously set in the `$presets` collection.

```json
{
  "styles": {
    "css": ".test-selector{color: var(--wp--preset--color--base);}"
  }
}
```

More examples can be found in the [tests/_data/fixtures/advanced-example.json.php](../tests/_data/fixtures/advanced-example.json.php) file.

To know more about `css` field:

* https://make.wordpress.org/core/2023/03/06/custom-css-for-global-styles-and-per-block/
* https://developer.wordpress.org/news/2023/04/21/per-block-css-with-theme-json/
* https://developer.wordpress.org/themes/global-settings-and-styles/
* https://fullsiteediting.com/lessons/how-to-use-custom-css-in-theme-json/
* https://make.wordpress.org/core/2022/12/22/whats-new-in-gutenberg-14-8-21-december/#Add-custom-CSS-rules-for-your-site

### Advanced Service Injection with Empress and PSR-11 Container

Let's talk more about the Container and the `Empress` service injection.

In the advanced configuration of your theme's JSON, [Empress](https://github.com/ItalyStrap/empress) (our powerful dependency injection tool, evolved from Auryn\Injector) serves as a powerful backbone for service resolution, providing the flexibility to inject services directly into your configuration's callable. To complement this, we also leverage the PSR-11 `ContainerInterface`, offering a standardized way to access services, enhancing the usability and familiarity for developers.

Understanding Empress and PSR-11 Integration

* **Empress Injector:** At its core, `Empress` utilizes the `Injector::make()` method to resolve services, configuring only those requiring specific setups. This approach minimizes overhead and simplifies service management.
* **PSR-11 Container:** To provide a common interface for service retrieval, we integrate the PSR-11 `ContainerInterface`. This ensures that accessing services is straightforward, using `Container::get()` as a preferred method for most use cases. The `get()` method effectively wraps `Injector::make()`, ensuring consistency in service resolution.

Best Practices for Service Injection

* **Be Pragmatic:** Whether you choose to pass `ItalyStrap\Empress\Injector` or `ContainerInterface` into your callable, both approaches offer seamless access to previously registered services and those requiring no explicit configuration, thanks to the dynamic capabilities of `Empress`.
* **Prefer PSR-11 for Daily Use:** For routine access to services, the PSR-11 **ContainerInterface** is recommended. Its common interface simplifies the retrieval process, making it ideal for regular use.
* **Maintain Minimalism:** Despite the flexibility in accessing and configuring services, adhering to the principle of minimalism remains crucial. Inject only essential services into your callable, ensuring your code remains clean and maintainable.

```php
declare(strict_types=1);

namespace YourVendor\YourProject;

use ItalyStrap\ThemeJsonGenerator\ThemeJson;
use Psr\Container\ContainerInterface;

return static function (ThemeJson $themeJson, ContainerInterface $container): void {
// Utilize the $themeJson and $container for your configuration
};
```

The Synergy of Empress and PSR-11

This integrated approach, combining the power of `Empress` for internal configurations and the `ContainerInterface` for a standardized external interface, optimizes the development workflow. Instances of `ItalyStrap\Empress\Injector` and `ContainerInterface` are shared, this means that every time you call one of them you'll get the same instance of the object, ensuring efficient service access through both `make` and `get` methods. Yet, for ease of use and to adhere to best practices, the PSR-11 interface is preferred for accessing services.

By balancing the advanced capabilities of `Empress` with the simplicity and standardization of PSR-11, developers can craft sophisticated theme configurations while keeping the process streamlined and accessible.

So, the example above can be rewritten as:

```php
declare(strict_types=1);

namespace YourVendor\YourProject;

use ItalyStrap\ThemeJsonGenerator\ThemeJson;
use Psr\Container\ContainerInterface;

return static function (ThemeJson $themeJson, ContainerInterface $container): void {
    /** @var SomeService $someService */
    $someService = $container->get(SomeService::class);
};
```

[🆙](#introduction)
