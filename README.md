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

This package **must be used only for development** purpose, using it in production it is not recommended because of performance reasons.
The files need to be already generated when you are in production.
All JSON files act as a cache, do not even think to generate them on the fly.
So, **do not use this package in production**.

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

This command will initialize all entrypoint used to generate the JSON file.
First this command will check if a `theme.json` file exists in the root of the theme (for classic theme for example), if not it will create it.
Then it will create the entrypoint file for the `theme.json` file.
When generating the entrypoint if a JSON file exists will also be used to populate the entrypoint file with the content of the JSON file in array format, so you do not need to fill the entrypoint file manually.
If the JSON file is empty the entrypoint file will be empty too.
From now on every time you run the `dump` command the content of the JSON file will be updated with the content of the entrypoint file.

If the theme is a Block Based Theme it will search also inside the `styles` folder (if exists) for all JSON files,
then it will create the entrypoint file for all JSON files found inside the `styles` folder.

The entrypoint is a PHP file called the same as the name of the JSON file plus the `.php` as extension, so for example the `theme.json` will have the entrypoint `theme.json.php` and so on.
This way all entrypoint will be closer to the JSON file they generate.
Entrypoint files for `styles` folder will be inside the `styles` folder itself.
All the entrypoint generated are PHP files that will return a callable, this callable will be used to add your custom configuration for structuring the JSON file.

```shell
./vendor/bin/theme-json dump
```

This command will generate the JSON file using the entrypoint generated with the `init` command.

You can use `--validate` option to validate the JSON files generated after the dump.
The `--validate` option is the same as the command below.

```shell
./vendor/bin/theme-json validate
```

This command will validate the JSON files generated.
The validation is done using the [justinrainbow/json-schema](https://github.com/justinrainbow/json-schema) package against the [WordPress Theme Json schema](https://schemas.wp.org/trunk/theme.json).
The command will check first if you have a file called `theme.schema.json` in the root of the theme folder or if the file is older than a week, if not it will create it (as a cache), only remember to add the `theme.schema.json` file to your `.gitignore` file to avoid to commit it.
If you use the `--force` option it will always create the `theme.schema.json` file from scratch.
Then it will validate all the Global Styles JSON files generated using the local schema file, the `theme.json` file in the root and all the others inside the `styles` folder.

#### Let's start

This is a basic example of the schema of a Global Style JSON file:

```json
{
    "version": 2,
    "settings": {},
    "styles": {},
    "customTemplates": {},
    "templateParts": {}
}
```

You can find more info about the schema [here](https://developer.wordpress.org/block-editor/how-to-guides/themes/global-settings-and-styles/).

The basic idea of this application is to just convert a PHP array into a JSON file, let's take a look at an example:

```php
$arrayExample = [
    'version'   => 1,
    'settings'  => 	[
        'layout' => [
            'contentSize' => '620px',
            'wideSize' => '1000px',
        ],
        [...] // All the rest of config, this is just an example
    ],
    'styles'    => [...],
    'customTemplates'   => [...],
    'templateParts' => [...],
];
```

And the generated JSON file will be:

```json
{
  "version": 1,
  "settings": {
    "layout": {
      "contentSize": "620px",
      "wideSize": "1000px"
    }
  },
  "styles": {},
  "customTemplates": {},
  "templateParts": {}
}
```

You need to following the WordPress Theme Json schema to have a valid JSON file, anyway calling the `validate` command will check if the JSON file is valid or not.
If in the future the schema will add more fields you can just add them to the array and the CLI will generate the JSON file with the new fields even if I do not update this documentation.

But, right now I have not shown you the real power of this package, so let's start from the beginning.

#### Entrypoint

An entrypoint serve as a bridge where you can add your custom configuration for structuring the JSON file and this CLI can know how to generate the JSON file based on your configuration.

A basic example of the entrypoint file:

```php

declare(strict_types=1);

namespace YourVendor\YourProject;

use ItalyStrap\ThemeJsonGenerator\Application\Config\Blueprint;

return static function (Blueprint $blueprint /**, Other Services You Need */): void {
    // Do your configuration using the $blueprint object
    // You do not need to return anything, it's just a void function
}
```

Under the hood this package use [Empress](https://github.com/ItalyStrap/empress) so every service you add to the signature of the callable will be resolved by the container.

But, please, be minimal, follow the KISS principle, do not add too much services to the signature of the callable, if you need to add too much services, maybe you need to refactor your code.

Another example could be to also add a Container to the signature of the callable but be aware that using the container to call every thing can lead you to the dark side of the Service Locator pattern, so use it wisely.

```php

declare(strict_types=1);

namespace YourVendor\YourProject;

use ItalyStrap\ThemeJsonGenerator\Application\Config\Blueprint;
use Psr\Container\ContainerInterface;

return static function (Blueprint $blueprint, ContainerInterface $container /**, Other Services You Need */): void {
    // Do your configuration using the $blueprint object
    // You do not need to return anything, it's just a void function
}
```

The `$container` allow you to access to all services you need to add your custom configuration.

Now it's time to start with a real (basic) example.

```php

declare(strict_types=1);

namespace YourVendor\YourProject;

use ItalyStrap\ThemeJsonGenerator\Application\Config\Blueprint;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\SectionNames;
use Psr\Container\ContainerInterface;

return static function (Blueprint $blueprint): void {
    $blueprint->merge([
        SectionNames::SCHEMA => 'https://schemas.wp.org/trunk/theme.json',
        SectionNames::VERSION => self::VERSION,
        SectionNames::TITLE => 'Experimental Theme',
        SectionNames::DESCRIPTION => 'Experimental Theme',
        SectionNames::SETTINGS => [
            'layout' => [
                'contentSize' => '620px',
                'wideSize' => '1000px',
            ],
            'color' => [
                'custom' => true,
                'link' => true,
                'palette' => [
                    [
                        'slug' => 'base',
                        'name' => 'Brand base color',
                        'color' => 'hsla(212,73%,55%,1)',
                    ],
                    // ... All the rest of palette colors
                ],
                'gradients' => [
                    [
                        'slug' => 'light-to-dark',
                        'name' => 'Black to white',
                        'gradient' => 'linear-gradient(160deg, var(--wp--preset--color--light), var(--wp--preset--color--dark))',
                    ],
                    // ... All the rest of gradients
                ],
                'duotone' => [
                    [
                        'slug' => 'black-to-white',
                        'name' => 'Black to White',
                        'colors' => [
                            'rgba(0,0,0,1.00)',
                            'rgba(255,255,255,1.00)',
                        ],
                    ],
                    // ... All the rest of duotone
                ],
            ],
            'typography' => [
                'customFontSize' => true,
                'fontSizes' => [
                    [
                        'slug' => 'base',
                        'name' => 'Base font size 16px',
                        'size' => 'clamp(1rem, 2vw, 1.5rem)',
                    ],
                    // ... All the rest of font sizes
                ],
                'fontFamilies' => [
                    [
                        'slug' => 'base',
                        'name' => 'Default font family',
                        'fontFamily' => 'system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"',
                    ],
                    // ... All the rest of font families
                ],
            ],
            'custom' => [...],
            [...] // All the rest of config
        ],
        SectionNames::STYLES => [
            'color' => [...],
            'typography' => [...],
            'spacing' => [...],
            'elements' => [...],
            'blocks' => [...],
            [...] // All the rest of config
        ],
    ]);
}
```

This example show you only the surface of the iceberg, if you do not need to add complex configuration you can just use it as is and skip the rest of the documentation.

After you add your configuration just run the command `./vendor/bin/theme-json dump` and the JSON file will be generated.

[🆙](#table-of-contents)

## Advanced Usage

From now on the fun begins.


> I use a naming convention for defining CSS properties, you can use your own if you don't like mine.

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

### Custom CSS and per block CSS

Link https://github.com/luizbills/css-generator.php

[🆙](#table-of-contents)

### WP_CLI command (on hold)

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
