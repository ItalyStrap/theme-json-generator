# ItalyStrap Theme Json Generator

[![Tests Status](https://github.com/ItalyStrap/theme-json-generator/actions/workflows/test.yml/badge.svg)](https://github.com/ItalyStrap/theme-json-generator/actions)
[![Latest Stable Version](https://img.shields.io/packagist/v/italystrap/theme-json-generator.svg)](https://packagist.org/packages/italystrap/theme-json-generator)
[![Total Downloads](https://img.shields.io/packagist/dt/italystrap/theme-json-generator.svg)](https://packagist.org/packages/italystrap/theme-json-generator)
[![Latest Unstable Version](https://img.shields.io/packagist/vpre/italystrap/theme-json-generator.svg)](https://packagist.org/packages/italystrap/theme-json-generator)
[![License](https://img.shields.io/packagist/l/italystrap/theme-json-generator.svg)](https://packagist.org/packages/italystrap/theme-json-generator)
![PHP from Packagist](https://img.shields.io/packagist/php-v/italystrap/theme-json-generator)

WordPress Theme Json Generator the OOP way

This is a WIP project and still experimental.
Until this package reach the version 1.x.x, we still on 0.x.x version (if you don't know what this means, please read the [SemVer](http://semver.org/) specification).

In short because of 0.x.x version the API can change at any time.

The idea is to generate a file `theme.json` (and all others `*.json` files inside the `styles` folder) from PHP because json sucks 😁 (just kidding)

With PHP, you have the ability to split the file in multiple files, add comments, generate the content and many more other stuff as you wish, 
PHP is not limited like json.

I'm experimenting this library as CLI and WP_CLI (the latter is on hold right now) command for generating and validating JSON files for a BLock Theme.

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

This package must be used only for development purpose, using it in production is not recommended because of performance reasons.
The files need to be already generated when you are in production.
All JSON files act as a cache, do not even think to generate them on the fly.
So, do not use this package in production.

This package add CLI commands to initialize, generate and validate JSONs files.

### How it works

Basically, this CLI executes the following steps:

* Init the entrypoint used to generate the JSON file.
* Generate the JSON file using the entrypoint generated.
* Validate the JSON files generated.

#### CLI

```shell
./vendor/bin/theme-json init
```

This command will initialize all antrypoints used to generate the JSON file.
First this command will check if a `theme.json` file exists in the root of the theme, if not it will create it.
Then it will create the entrypoint for the `theme.json` file and all the others inside the `styles` folder, the entrypoint will be a PHP file called the same as the name of the JSON  file plus the `.php` extension, so for example the `theme.json` will have the entrypoint `theme.json.php` and so on.
This way all entrypoint will be closer to the JSON file they generate.
All the entrypoint generated are PHP files that will return a callable, this callable will be used to add your custom configuration for structuring the JSON file.

```shell
./vendor/bin/theme-json dump
```

This command will generate the JSON file using the entrypoint generated with the init command.

You can use `--validate` option to validate the JSON files generated after the dump.
The `--validate` option is the same as the command below.

```shell
./vendor/bin/theme-json validate
```

This command will validate the JSON files generated.
The validation is done using the [justinrainbow/json-schema](https://github.com/justinrainbow/json-schema) package.
The command will check first if you have `theme.schema.json` file in the root of the theme, if not it will create it, only remember to add the `theme.schema.json` file to your `.gitignore` file.
The file is regenerated if the file schmea you have is older than a week or if you use the `--force` option.
Then it will validate all the Global Styles JSON files generated using the local schema file, the `theme.json` file in the root and all the others inside the `styles` folder.

#### Under the hood

* The callback needs to return an array with your [theme config](https://developer.wordpress.org/themes/global-settings-and-styles/) and the callback accepts a string argument where you get the
  path of the theme.
* At the end it generates the **theme.json** in the root folder of the theme you are developing.

  [WordPress Theme Json schema](https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-json/)
This package simply convert a PHP array you provide into a JSON file, so if you want or need to make things simpler
just provide the PHP array with all the structure you need.

So if you just follow the schema your theme.json will need to be compliant with the WordPress Theme Json schema you're ready to go, create your simple array and the package will do the rest.

This is a basic example of the array you need to provide:

```php
$arrayExample = [
    'version'   => 1,
    'settings'  => 	[
        'layout' => [
            'contentSize' => '620px',
            'wideSize' => '1000px',
        ],
        [...] // All the rest of config, this is just an example
    ]
];
```

And this package will generate the following json:

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

```json
{
    "version": 2,
    "settings": {},
    "styles": {},
    "customTemplates": {},
    "templateParts": {}
}
```

[🆙](#table-of-contents)

### Example project

```php
namespace YourVendor\YourProject;

final class YourCustomClass {

    /**
     * @argument string $path The path of the theme
     */
    public static function yourCallback( string $path ): array {
        // You can check against the $path argument in case you have parent and child theme.
    
        return [
            'version'   => 1,
            'settings'  => 	[
                'layout' => [
                    'contentSize' => '620px',
                    'wideSize' => '1000px',
                ],
                [...] // All the rest of config
            ]
        ];
    }
}
```

And this package will generate the following json:

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

[🆙](#table-of-contents)

### WP_CLI command

The WP_CLI commands are on hold in this version, I'm thinking if having also a WP_CLI command is a good idea or not.

So the following documentation for WP_CLI is not valid for this version, I keep it here for future reference.

For using as WP_CLI command you have to create a file `wp-cli.local.yml` or `wp-cli.yml` in the root of your theme 
or better in the root of the WordPress installation.

Inside that file add this line for adding your custom callback:

```yaml
THEME_JSON_CALLABLE: '\YourVendor\YourProject\your_callback'
```

And in the command line just use the command:

```shell
wp theme-json dump
```

This command is still experimental, could be changed in the future.

[🆙](#table-of-contents)

## Advanced Usage

> This part is optional, if you want to provide your own data just skip this part.
> I use a naming convention for defining CSS properties, you can use your own if you don't like mine.

Now we know how to generate all Global Styles JSON files so, what next?

If you want to do more with PHP you can use some helper classes I added to this package to better manage the 
settings.

The first classes you can use are the

* `\ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Collection::class`
* `\ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Custom\Custom::class`

Those classes can manage settings for color, 
typography and custom, lets start with color:

@todo Implement snippet

As you can see the `Collection::class` accept...

Now we can set the gradient:

```php
$gradient
```

As you can see we define `gradient` as `category`, and `gradient` is also the key for the value.

Now instead of define the value `gradient` manually `'linear-gradient(160deg,--wp--preset--color--text,
--wp--preset--color--background)'` we can handle the power of the `CollectionInterface::class` and use a simple 
syntax to define the value we need: `'{{color.text}}'` where the parenthesis `{{` `}}` are used to wrap the value 
name we want, in the example is `text` in `color` collection, in case we need a value from another collection of preset we have to add 
also the category of the preset with the name separated by a dot like color `color.text`, so the object knows that it 
needs a `text` value from a color collection, also we need to add the collection of colors to the collection of 
gradients with color snippet:

```php
$gradient->withCollection( $palette );
```

This way allows us to avoid syntax errors when writing CSS properties manually.
In case there is no value with the slug we use, the object will throw a `\RuntimeException::class`, useful for 
future refactoring of the theme style.

Here an example for `fontSize` and `fontFamily`:

```php
$font_sizes;

$font_family;
```

As you can see here we use the slug `base` without the category, this is because the slug is declared inside the 
same object, so we don't need to add the category.

Also, you can notice that for `fontSize` we use the key `size`, this is because is the key used for the preset of 
font sizes.

It's time for an example of custom:

```php
$custom = new \ItalyStrap\ThemeJsonGenerator\Settings\CustomCollection(
    [
        'contentSize'	=> '60vw',
        'wideSize'	=> '80vw',
        'baseFontSize' => "1rem",
        'spacer' => [
            'base'	=> '1rem',
            'v'		=> 'calc( {{spacer.base}} * 4 )',
            'h'		=> 'calc( {{spacer.base}} * 4 )',
            'test'		=> 'calc( {{fontSize.base}} * 4 )',
        ],
        'blockGap'	=> [
            'base'	=> '{{spacer.base}}',
            'm'	=> 'calc( {{spacer.base}} * 2 )',
            'l'	=> 'calc( {{spacer.base}} * 4 )',
        ],
        'lineHeight' => [
            'small' => 1.2,
            'medium' => 1.4,
            'large' => 1.8
        ],
        'button'	=> [
            'bg'	=> '{{color.base}}',
            'text'	=> '{{color.background}}',
        ],
    ]
);
```

Custom properties is useful for declaring "custom" prop names, if we want to use the preset Properties we have to add 
the collections we need, I added all preset collection because I want to use all of them.

Now in the setting section we need to use the `CollectionInterface::toArray()` method to add the configurations we 
just created above:

```php
return [
    'settings' => [
        'color' => [
            'palette'	=> $palette->toArray(),
            'gradients'	=> $gradient->toArray(),
        ],
        'typography' => [
            'fontSizes'			=> $font_sizes->toArray(),
            'fontFamilies'		=> $font_family->toArray(),
        ],
        'custom' => $custom->toArray(),
        'layout' => [
            'contentSize' => $custom->varOf( 'contentSize' ),
            'wideSize' => $custom->varOf( 'wideSize' ),
        ],
    ],
];
```

`CollectionInterface::class` also provide other method you can use in the config:

`CollectionInterface::propOf( string $slug )` will return the css property like `--wp--preset--color--base`
`CollectionInterface::varOf( string $slug )` will return the css property and css var function like 
`var(--wp--preset--color--base)`
`CollectionInterface::value( string $slug )` will return the value of the CSS property.

The `styles` section coming soon, I'm working on it.

[🆙](#table-of-contents)

## Changelog

Refactored the files structure:
You have to change the Composer Plugin call -> You should call "ItalyStrap\\ThemeJsonGenerator\\Composer\\Plugin::run" See above in the docs
Changed Preset::class and Custom::class, use:
\ItalyStrap\ThemeJsonGenerator\Settings\PresetCollection::class
\ItalyStrap\ThemeJsonGenerator\Settings\CustomCollection::class

The directory Collection is here only for back compat.

## Contributing

All feedback / bug reports / pull requests are welcome.

## License

Copyright (c) 2021 Enea Overclokk, ItalyStrap

This code is licensed under the [MIT](LICENSE).

## Credits
