# ItalyStrap Theme Json Generator

[![Build Status](https://github.com/ItalyStrap/theme-json-generator/actions/workflows/test.yml/badge.svg)](https://github.com/ItalyStrap/theme-json-generator/actions)

WordPress Theme Json Generator the OOP way

This is a WIP project and still experimental.

The idea is to generate a file `theme.json` from PHP because json sucks :D (just kidding)

With PHP you have the ability to split the file in multiple files, add comments, generate the content as you wish, 
PHP is not limited like json.

I'm experimenting this library as composer and WP_CLI plugin for generating the file.

## Table Of Contents

* [Installation](#installation)
* [Basic Usage](#basic-usage)
* [Advanced Usage](#advanced-usage)
* [Contributing](#contributing)
* [License](#license)

## Installation

The best way to use this package is through Composer:

```CMD
composer require italystrap/theme-json-generator --dev
```
This package adheres to the [SemVer](http://semver.org/) specification and will be fully backward compatible between minor versions.

## Basic Usage

### How it works

Basically, this plugin executes the following steps:

* This plugin searches for a custom callback you provide trough `composer.json` inside `extra` field.
* The callback needs to return an array with your [theme config](https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-json/) and accept a string argument where you get the path of the theme.
* And it generates the theme.json in the root folder of the theme you are developing.

### Example project

The following is an example Composer project.

```json
{
    "name": "italystrap/experimental-theme",
    "description": "Experimental theme",
    "type": "wordpress-theme",
    "require-dev": {
        "italystrap/theme-json-generator": "dev-master"
    },
    "extra": {
        "theme-json": {
            "callable": "\\YourVendor\\YourProject::your_callback"
        }
    },
    "repositories": [{
        "type": "vcs",
        "url": "https://github.com/ItalyStrap/theme-json-generator.git"
    }]
}
```

It is not yet on packagist and you have to add also the "repositories" field with the github url.

The callable must be a valid PHP callable and must return an array with your configuration following the schema 
provided from the [documentation](https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-json/).

Example:
```php
namespace YourVendor\YourProject;
/**
 * @argument string $path The path of the theme
 */
function your_callback( string $path ): array {
    // You can check against the $path argument in case you have parent and child theme.

    return [
        'version'   => 1,
        'settings'  => 	[
            'layout' => [
                'contentSize' => '620px',
                'wideSize' => '1000px',
            ],
        ]
    ];
}
```

And this will generate the following json:

```json
{
  "version": 1,
  "settings": {
    "layout": {
      "contentSize": "620px",
      "wideSize": "1000px"
    }
  }
}
```

If you want to load manually using composer add your command inside the `script` field and load the
`ItalyStrap\\ThemeJsonGenerator\\ComposerPlugin::run` method like below:

```json
{
  "scripts": {
    "your-command": [
      "ItalyStrap\\ThemeJsonGenerator\\ComposerPlugin::run"
    ]
  }
}
```

And then run:

```shell
composer run your-command
```

### WP_CLI command

As I said this is also a WP_CLI plugin, for now it is all included in this library for testing purpose, maybe I can 
split in other library if I see the need of doing that.

For using as WP_CLI command you have to create a file `wp-cli.local.yml` or `wp-cli.yml` in the root of your theme 
or better in the root of the WordPress installation.

Inside that file add this line for adding your custom callback:

```yaml
THEME_JSON_CALLABLE: '\YourVendor\YourProject\your_callback'
```

And in the command line just use the command:

```shell
wp theme-json generate
```

This will generate the theme.json on the root of the active theme, parent **or** child.
If you want to generate the theme.json for both add the option `--parent`

```shell
wp theme-json generate --parent
```

And remember to check inside the callback the path to provide the right config for the theme you want to generate 
the file.

This command is still experimental, could be changed in future.

## Advanced Usage

> This part is optional, if you want to provide your own data just skip this part.

Now we know how to generate the theme.json file so what next?

If you want to do more with PHP you can use some helper classes I added to this library to manage the settings better.

The first class you can use are the `\ItalyStrap\ThemeJsonGenerator\Collection\Preset::class` and the 
`ItalyStrap\ThemeJsonGenerator\Collection\Custom::class` that extends 
`\ItalyStrap\ThemeJsonGenerator\Collection\CollectionInterface::class`, those classes can manage settigs for color, 
tyèpgrafy and custom, let start with color:

```php
$palette = new Preset(
    [
        [
            "slug" => "text",
            "color" => '#000000',
            "name" => "Black for text, headings, links"
        ],
        [
            "slug" => "background",
            "color" => '#ffffff',
            "name" => "White for body background"
        ],
        [
            "slug" => "base",
            "color" => '#3986E0',
            "name" => "Brand base color"
        ],
    ],
    'color'
);
```

As you can see the `Preset::class` accept an array with the preset configuration following the json schema for color, 
and then you can also provide a `category` name and `key` value, the `category` name is used to define that the config is 
for color, the key is optional and is used to know what is the key for value, in this case the key and category are 
"color" so you can omit (you will need it when you will set fontSize).

Now we can set gradient:
```php
$gradient = new Preset(
    [
        [
            "slug"		=> "black-to-white",
            "gradient"	=> \sprintf(
                'linear-gradient(160deg,%s,%s)',
                '{{color.text}}',
                '{{color.background}}'
            ),
            "name"		=> "Black to white"
        ],
    ],
    'gradient'
);
```

As you can see we define `gradient` as `category`, and `gradient` is also the key for value.

Now instead of define the value `gradient` manually `'linear-gradient(160deg,--wp--preset--color--text,
--wp--preset--color--background)'` we can handle the power of the `CollectionInterface::class` and use a simple 
syntax to define the value we need: `'{{color.text}}'` where the parenthesis `{{` `}}` are used to wrap the value 
name we need, in the example is `text`, in case we need a value from another collection of preset we have to add 
also the category of the preset with the name separated by a dot like this `color.text`, so the object knows that it 
needs a text value from a color collection, also we need to add the collection of colors to the collection of 
gradients with this snippet:

```php
$gradient->withCollection( $palette );
```

This way allows us to avoid syntax errors when writing CSS properties manually.
In case there is no value with the slug we need the object will throw a `\RuntimeException::class`, useful for 
future refactoring of the theme style.

Here an example for `fontSize` and `fontFamily`:

```php
$font_sizes = new Preset(
    [
        [
            "slug" => "base",
            "size" => "20px",
            "name" => "Base font size 16px"
        ],
        [
            "slug" => "h1",
            "size" => "calc({{base}} * 2.5)",
            "name" => "Used in H1 titles"
        ],
        [
            "slug" => "h2",
            "size" => "calc({{base}} * 2)",
            "name" => "Used in H2 titles"
        ],
        [
            "slug" => "h3",
            "size" => "calc({{base}} * 1.75)",
            "name" => "Used in H3 titles"
        ],
        [
            "slug" => "h4",
            "size" => "calc({{base}} * 1.5)",
            "name" => "Used in H4 titles"
        ],
        [
            "slug" => "h5",
            "size" => "calc({{base}} * 1.25)",
            "name" => "Used in H5 titles"
        ],
        [
            "slug" => "h6",
            "size" => "{{base}}",
            "name" => "Used in H6 titles"
        ],
    ],
    'fontSize',
    'size'
);

$font_family = new Preset(
    [
        [
            'fontFamily' => 'system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"',
            'slug' => "base",
            "name" => "Default font family",
        ],
        [
            'fontFamily' => 'SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace',
            'slug' => "monospace",
            "name" => "Font family for code",
        ],
    ],
    'fontFamily'
);
```

As you can see in the above `fontSize` config we call value name without the category `{{base}}` this is because the 
`base` slug is declared in the configuration of the same object, so, we don't need to add the category.

Also, you can notice that for `fontSize` we use the key `size`, this is because is the key used for the preset of 
font sizes.

## Contributing

All feedback / bug reports / pull requests are welcome.

## License

Copyright (c) 2021 Enea Overclokk, ItalyStrap

This code is licensed under the [MIT](LICENSE).

## Credits
