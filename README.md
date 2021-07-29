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

This library works on composer events:

```php
function getSubscribedEvents(): array {
    return [
        'post-autoload-dump'	=> 'run',
        'post-install-cmd'		=> 'run',
        'post-update-cmd'		=> 'run',
    ];
}
```
That's it.

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
        "italystrap/theme-json-generator": "*"
    },
    "extra": {
        "theme-json": {
            "callable": "\\YourVendor\\YourProject::your_callback"
        }
    }
}
```

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
wp theme json
```

This will generate the theme.json on the root of the active theme, parent **or** child.
If you want to generate the theme.json for both add the option `--parent`

```shell
wp theme json --parent
```

And remember to check inside the callback the path to provide the right config for the theme you want to generate 
the file.

This command is still experimental, could be changed in future.

## Advanced Usage

## Contributing

All feedback / bug reports / pull requests are welcome.

## License

Copyright (c) 2021 Enea Overclokk, ItalyStrap

This code is licensed under the [MIT](LICENSE).

## Credits
