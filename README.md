# ItalyStrap Theme Json Generator

[![Tests Status](https://github.com/ItalyStrap/theme-json-generator/actions/workflows/test.yml/badge.svg)](https://github.com/ItalyStrap/theme-json-generator/actions)
[![Latest Stable Version](https://img.shields.io/packagist/v/italystrap/theme-json-generator.svg)](https://packagist.org/packages/italystrap/theme-json-generator)
[![Total Downloads](https://img.shields.io/packagist/dt/italystrap/theme-json-generator.svg)](https://packagist.org/packages/italystrap/theme-json-generator)
[![Latest Unstable Version](https://img.shields.io/packagist/vpre/italystrap/theme-json-generator.svg)](https://packagist.org/packages/italystrap/theme-json-generator)
[![License](https://img.shields.io/packagist/l/italystrap/theme-json-generator.svg)](https://packagist.org/packages/italystrap/theme-json-generator)
![PHP from Packagist](https://img.shields.io/packagist/php-v/italystrap/theme-json-generator)

WordPress Theme Json Generator: The OOP Way

**Work in Progress:** This project is experimental and currently in development. As we navigate through the 0.x.x versions, please note that API changes may occur. For understanding versioning, refer to the [SemVer](http://semver.org/) specification.

**Project Vision:** The aim is to revolutionize the way `theme.json` files (and other `*.json` files within the `styles` folder) are generated because json sucks 😁 (just kidding). PHP offers the flexibility to divide configurations into multiple files, include comments, and generate dynamic content, overcoming the limitations of JSON.

**Who is This For?**
- Ideal for those seeking a more maintainable and predictable method to generate JSON files for WordPress themes.
- Perfect if you prefer writing your configurations once and reusing them efficiently.

**Not Suited For:**
- Users content with direct JSON file manipulation without the need for PHP generation.
- Those who believe this approach complicates the JSON generation process.
- Individuals comfortable managing extensive JSON configurations manually.

**Explore With Us:** Join us in exploring this CLI and WP_CLI tool for generating and validating JSON files for Block Themes, and see how it can streamline your development process.

**Important Considerations for Developers:**

This package is designed with the development phase in mind and **should only be used for development purposes**. For performance reasons, it is not recommended to use this tool in production environments. JSON files should be generated in advance during the development process and considered as a form of cache in your project. Generating these files on the fly in a production environment is strongly discouraged. Therefore, **this package should not be used in production**.

**Extending Functionality with CLI Commands:**

To enhance your development workflow, this package includes CLI commands that simplify the initialization, generation, and validation of JSON files. These tools are crafted to streamline the creation and management of your theme’s configuration, ensuring a smooth and efficient development process.

## Table Of Contents

* [Installation](#installation)
* [Basic Usage](#basic-usage)
* [Advanced Usage](#advanced-usage)
* [Contributing](#contributing)
* [License](#license)
* [Credits](#credits)
* [Resources](#resources)

## Installation

The best way to use this package is through Composer:

```CMD
composer require italystrap/theme-json-generator --dev
```
This package adheres to the [SemVer](http://semver.org/) specification and will be fully backward compatible between minor versions.

## Basic Usage

Following the introduction to the tool's capabilities and its intended development use, let's dive into how to leverage its functionalities effectively.

### Understanding the Workflow

The CLI tool simplifies the process of working with JSON configurations for your WordPress themes through a streamlined workflow, consisting of the following key steps:

1. Initialization: Start by initializing the entry point. This setup phase prepares the environment for generating your theme.json file, ensuring that all necessary prerequisites are met.
2. Generation: Utilizing the initialized entry point, the tool then proceeds to generate the JSON file. This step takes your PHP configurations and translates them into a structured theme.json file, ready for use in your theme development.
3. Validation: After generation, the tool performs a validation check on the generated JSON files. This ensures that the files are correctly formatted and meet the expected standards, ready for integration into your WordPress theme.

#### Initializing the Environment: The init Command

To kickstart the development process and prepare your theme for JSON configuration, the `init` command lays the foundational structure required for generating your `theme.json` and related JSON files efficiently.

How to Use

Execute the command below in your terminal:

```shell
./vendor/bin/theme-json init
```

What Happens Next?

Upon execution, this command performs a series of actions to ensure your theme is ready for further development:

1. **Checks for theme.json or others json files:** Initially, it looks for an existing theme.json file at the root of your theme (and inside `styles` folder if exists). If found, it proceeds with the existing structure; if not, a new `*.json` file is created to get you started.
2. **Entrypoint Creation:** For each JSON file (including theme.json and any JSON files within the styles folder of block-based themes), the command creates a corresponding PHP entrypoint file. This file serves as a bridge, allowing you to manage your JSON configurations dynamically with PHP, to doing so this file must return a callable used to add your custom configuration for structuring the JSON file.
3. **Content Transfer:** If an existing JSON file contains data, its contents are automatically transferred to the newly created entrypoint file in array format. This feature simplifies the initial setup by negating the need for manual data entry into the entrypoint files.
4. **Synchronization with dump Command:** From this point forward, executing the `dump` command will update the content of the JSON files to reflect the configurations specified in the PHP entrypoint files.

Naming and Organization

* Each entrypoint PHP file is named after its corresponding JSON file but with an added `.php` extension (e.g., `theme.json.php` for `theme.json`).
* Entrypoint files related to the styles folder are placed within the same folder, maintaining a close association with their respective JSON files.

Key Takeaway

The `init` command is designed to streamline the setup process, making it easier for you to manage and generate your theme’s JSON configurations with the power and flexibility of PHP.

##### FAQs/Troubleshooting

**Q: What should I do if I encounter permission issues when running the init command on Linux?**

**A:** Permission issues usually occur when you don't have the necessary rights to write to the directory where you're trying to generate or modify files. Here are a few tips to resolve this:

**Run with Sudo:** Temporarily elevating your permissions with `sudo` can help bypass the issue. However, use this with caution as it grants elevated privileges to the operation. You can run the command like so:

```shell
sudo ./vendor/bin/theme-json init
```

Remember, it's generally best to avoid using `sudo` for scripts unless absolutely necessary, due to the security implications.

**Change Directory Permissions:** Adjusting the permissions of the directory where you're working might be necessary. You can change the directory permissions with the `chmod` command. For example, to grant write access to the current user, you could use:

```shell
chmod u+w /path/to/your/theme-directory
```

Be sure to replace `/path/to/your/theme-directory` with the actual path to your theme directory.

**Check File Ownership:** If the files or directories were created by another user or through a process with different permissions, you might face access issues. You can change the ownership with the `chown` command, like this:

```shell
sudo chown $USER:$USER /path/to/your/theme-directory -R
```

This command changes the ownership of the theme directory and all its contents to the current user. Replace `/path/to/your/theme-directory` with the actual path to your directory.

**Note:** Always exercise caution when changing permissions and ownership of files and directories on your system, especially when using `sudo`. These actions can affect the security and functionality of your system.

#### Generating and Updating JSON Files: The dump Command

Once you've set up your environment with the `init` command, the `dump` command is your next step in the workflow. It's designed to take the configurations from your PHP entrypoint files and generate the corresponding JSON files for your theme.

How to Use

To execute the dump command, run the following in your terminal:

```shell
./vendor/bin/theme-json dump
```

Key Features

* **JSON File Generation:** Utilizes the PHP entrypoint files prepared by the `init` command to generate or update your `theme.json` and any other related JSON files in your theme directory. This ensures that your theme configurations are accurately reflected in the JSON files used by WordPress.

* **Validation Option:** With the `--validate` option, you can ensure the integrity and format of your JSON files immediately after generation. This step is crucial for preventing errors in your theme due to malformed JSON.

```shell
./vendor/bin/theme-json dump --validate
```

Using the `--validate` option runs a validation check similar to the standalone validation command, making it a convenient way to generate and validate in one step.

When to Use

The `dump` command is especially useful in several scenarios, including:

* **After Initial Setup:** Run it immediately after using the `init` command to generate your initial JSON files.
* **After Making Changes:** Whenever you update your PHP entrypoint files with new configurations, use the `dump` command to reflect those changes in your JSON files.
* **Before Testing or Deployment:** It's a good practice to run this command as part of your pre-testing or pre-deployment checklist to ensure all your theme configurations are up-to-date and validated.

Tips for Effective Use

* Regularly use the `dump` command throughout your theme development process to keep your JSON files synchronized with your PHP configurations.
* Incorporate the `--validate` option into your workflow to catch and resolve potential issues early in the development process.

(for watching the changes and automatically generate the JSON files you can use any of the available task runners like Gulp, Grunt, Webpack, etc.)

#### Validating JSON Files: The validate Command

To maintain the highest standards of quality and compatibility for your WordPress theme, the `validate` command plays a crucial role. It scrutinizes your JSON files to ensure they adhere to the WordPress Theme JSON schema, leveraging the robust validation capabilities of the [justinrainbow/json-schema](https://github.com/justinrainbow/json-schema) package.

How to Use

Execute the command below to validate your theme's JSON files:

```shell
./vendor/bin/theme-json validate
```

Key Features

* **Comprehensive Validation:** This command checks the integrity and structure of your JSON files against the [WordPress Theme Json schema](https://schemas.wp.org/trunk/theme.json), ensuring they meet the required standards for WordPress themes.
* **Schema Caching:** On its first run, the command looks for a `theme.schema.json` file in your theme's root directory. If it doesn't find one, or if the existing file is older than a week, it automatically generates a new one to use as a cache. This process enhances performance by avoiding repetitive downloads of the schema. Remember to add `theme.schema.json` to your `.gitignore` to prevent accidental commits.
* **Force Refresh Option:** Using the `--force` option with the command forces a fresh generation of the `theme.schema.json` file, ensuring you're validating against the most current schema available.

```shell
./vendor/bin/theme-json validate --force
```

* **Global Styles Validation:** It validates not only the primary `theme.json` file but also any JSON files within the `styles` folder, providing a thorough validation of all global styles configurations.

When to Use

* **Before Committing Changes:** Run this command as a final check before committing updates to your theme's repository to ensure all JSON files are valid.
* **After Generating or Updating JSON Files:** Following the use of the `dump` command, validate your files to catch any potential issues early.
* **Periodically:** To ensure ongoing compliance with the WordPress Theme JSON schema, especially after schema updates.

Tips for Effective Use

* Regular validation of your JSON files is essential for maintaining theme quality and compatibility. Incorporate the `validate` command into your regular development workflow to catch and correct issues promptly.
* Utilize the `--force` option if you suspect schema updates or when you wish to ensure validation against the latest schema standards.



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

Now to store all the information and pass it to the application we need to use the `Blueprint` object, this object
will be used to be converted to JSON file.

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

### The entrypoint file

This example show you only the surface of the iceberg, if you do not need to add complex configuration you can just use it as is and skip the rest of the documentation.

After you add your configuration just run the command `./vendor/bin/theme-json dump` and the JSON file will be generated.

[🆙](#table-of-contents)

## Advanced Usage

From now on the fun begins.

> I use a naming convention for defining CSS properties, you can use your own if you don't like mine.

If you want to do more with PHP you can use some helper classes I added to this package to better manage the 
settings.

> All classes with `Experimental` suffix may change the name and the logic in the future.

### Settings

Before doing any kind of style or calling value from a presets you need to define the settings first, the settings are divided in some sections:

* `color`
* `typography`
* `spacing`
* `layout`
* `custom`
* `blocks`
* and others

The `color` and `typography` sections are divided in `palette`, `gradients`, `duotone`, `fontSizes` and `fontFamilies`.

To handle data inside those sections I created some value objects, but first let's talk about the `Presets` object.

#### Presets

The `Presets` object is used to collect all the value objects used to build the settings, it is used to store all the value objects and then add them to the `Blueprint` object.

```php
use \ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Presets;

$presets = new Presets();
```

The presets has a few methods you can use:

```php
interface PresetsInterface
{
    public function add(PresetInterface $item): self;

    public function addMultiple(array $items): self;

    public function get(string $key, $default = null);

    public function parse(string $content): string;
}
```

The `add()` method is used to add a single value object to the presets, the value object must implement the `PresetInterface` interface,
The `addMultiple()` method is used to add an array of value objects to the presets.
The `get()` method is used to get a value object that implements the `PresetInterface` interface but because you can also add a mixed default value the return type is mixed as well.
The `parse()` method is used to parse a string and if the string contains a `{{}}` syntax it will search for the corresponding value in the presets and replace it with the value of the property fond from the presets, so if you pass a string like `{{color.base}}` the `color.base` key will be used to get the value from the presets, if no value is found it will throw a `\RuntimeException::class`, but if you pass a string without the `{{}}` syntax it will return the string as is.

Because you use the entrypoint you do not need to instantiate a new presets object, instead you can use the `$presets` object passed by the callable, or use the `$container` to get the presets object.

```php
declare(strict_types=1);

namespace YourVendor\YourProject;

use ItalyStrap\ThemeJsonGenerator\Application\Config\Blueprint;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\SectionNames;
use Psr\Container\ContainerInterface;

return static function (Blueprint $blueprint, PresetsInterface $presets): void {

}
```

#### PresetInterface

The `PresetInterface` interface used by all Presets value objects.

```php
interface PresetInterface extends \Stringable
{
    public function category(): string;

    public function slug(): string;

    public function ref(): string;

    public function prop(): string;

    public function var(string $fallback = ''): string;

    public function __toString(): string;

    /**
     * @return array<string, string|array|object>
     */
    public function toArray(): array;
}
```
The object is stringable, so you can use it as a string, for example `echo $item;` will print the item as a string, the string will have the format `var(--wp--preset--color--base)`.
The `category()` methos return the category the item belongs to, for example `palette`, `gradients`, `duotone`, `fontSizes` and `fontFamilies`.
The `slug()` method return the slug of the item.
The `ref()` method return the reference of the item, this method is a shortcut for the `{{}}` syntax, for example `color.base` or `fontSizes.base` will be converted to `{{color.base}}` and `{{fontSizes.base}}` respectively.
The `prop()` method return the property of the item, for example `--wp--preset--color--base` or `--wp--preset--typography--font-sizes--base` and so on.
The `var()` method return the CSS variable of the item, for example `var(--wp--preset--color--base)` or `var(--wp--preset--typography--font-sizes--base)` and so on, this method also allow you to pass a fallback value to add it to the `var()` call, for example if you pass `#000000` as fallback like this `$item->var('#000000')` the return value could be something like `var(--wp--preset--color--base, #000000)`.
The `toArray()` method return the item as an array rappresentation, this method is used internally.

#### settings.color.palette

Let's start with the `settings.color.palette` section, this section is used to define the colors of the palette.

```php

'palette' => [
    [
        'slug' => 'base',
        'name' => 'Brand base color',
        'color' => 'hsla(212,73%,55%,1)',  // #3986E0
    ],
    // ... All the rest of palette colors
],
```

First we need to handle the colors, so we need to create a `Color` object with the value of the color we want to use:

```php

use \ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\Color;

$baseClr = new Color('#3986E0');
```

This class uses under the hood the [Color](https://github.com/spatie/color) package from Spatie.

What it does is to internally validate a string color and convert it to a `Color` object, the string must be a valid CSS value for a color, so it can be a hex value, a rgb value, a hsl value and so on.
The object will store information about the color like the hue, saturation, lightness alpha and RGB values.

The object can also be converted to one format to another, for example from hex to rgb and so on.

```php
$baseClr->toRgb(); // Returns a Rgb object
$baseClr->toRgba(); // Returns a Rgba object
$baseClr->toHsl(); // Returns a Hsl object
$baseClr->toHsla(); // Returns a Hsl object
$baseClr->toHex(); // Returns a Hex object
```

Because it is immutable, every time you call a method to convert the color to another format it will return a new object for the new format.

The `Color` object also has a magic method `__toString()` that will return the color as a string.

```php
echo $baseClr; // Will print the color as a string with the latest format the object has in his state
```

If you need to modify the color the is also a class `\ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\ColorModifier::class` that can be used to modify the color instance, the resulted color will be a new instance of the `Color` object.

```php

use \ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\ColorModifier;

$baseClr = new Color('#3986E0');

$baseClrLighten = (new ColorModifier($baseClr))
    ->lighten(10)
    ->toHex();

$baseClrDarken = (new ColorModifier($baseClr))
    ->darken(10)
    ->toHex();

// and so on
```

You can find all the methods available in the `\ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\ColorModifierInterface::class` interface.

Now that we have a color object we can use it to create a palette color:

```php

use \ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\Color;
use \ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\ColorModifier;
use \ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Palette;

$baseClrPalette = new Palette('base', 'Brand base color', $baseClr);
```

The `Palette` value object is used to store a single color instance and build the structure needed for the `palette` section of the `color` section, you need to create a new `Palette` instance for every color you need to add into your project.

The `Palette` class accept three arguments:

* `string $slug` The slug of the color
* `string $name` The name of the color
* `Color $color` The color instance

After you created all colors you need in the project and all the `Palette` instances you can add them to the presets `\ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetsInterface::class`.

A presets is another object used to collect those value object (till now I've only talked about Palette value objects, but there are other objects used to handle the data, we'll see more later).

```php

use \ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Presets;

$presets = new Presets();

$presets->add($baseClrPalette);
// $presets->add($otherColorPalette);
// and so on
```

#### settings.color.gradients

Now that we have the `palette` section we can move to the `gradients` section.
To create a gradient we need to create a `Gradient` object with `\ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Gradient::class`.
The `Gradient` object accept three arguments:

* `string $slug` The slug of the gradient
* `string $name` The name of the gradient
* `GradientInterface $gradient` The gradient value

Let's take a look an implementation for Gradient with the use of the `\ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\LinearGradient::class` class.

```php

$lightToDark = new \ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Gradient(
    'light-to-dark',
    'Black to white',
    new LinearGradient(
        '160deg',
        /**
         * @var \ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetInterface $lightClrPalette
         */
        $lightClrPalette,
        /**
         * @var \ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetInterface $darkClrPalette
         */
        $darkClrPalette
    )
);
```

In this case the `LinearGradient` class accept three arguments:

* `string $position` The position of the gradient
* `Palette[] $items` An array of `Palette` objects used to build the gradient

The `Palette[]` argument passed to `LinearGradient` will be resolved to a regular CSS property like `var(--wp--preset--color--light)` and `var(--wp--preset--color--dark)` in this case, but you are free to add any other `Palette` object you need, for example if you need to have more steps in the gradient rule.

The linear gradient above will be converted to:

```css
"linear-gradient(160deg, var(--wp--preset--color--light), var(--wp--preset--color--dark))"
```

We can also handle the power of the `PresetsInterface::class` like this snippet:

```php
$lightToDark = new \ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Gradient(
    'light-to-dark',
    'Black to white',
    new LinearGradient(
        '160deg',
        $this->presets->get(JsonData::COLOR_LIGHT),
        $this->presets->get(JsonData::COLOR_DARK)
    )
)
```

The `Presets::get()` will return the `PresetInterface` object stored in the presets with the slug passed as argument needed for the `LinearGradient` class.

This way allows us to avoid syntax errors when writing CSS properties manually because most of the code is an object.

In case there is no value with the slug we use, the object will throw a `\RuntimeException::class`, useful for 
future refactoring of the theme style.

Now add the gradient to the presets:

```php
$presets->add($lightToDark);
```

#### settings.color.duotone

The `duotone` section is similar to the `gradients` section.

```php
$blackToWhite = new \ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Duotone(
    "black-to-white",
    "Black to White",
    $this->presets->get(JsonData::COLOR_BODY_COLOR),
    $this->presets->get(JsonData::COLOR_BODY_BG)
)
```

The `Duotone` class accept four arguments:

* `string $slug` The slug of the duotone
* `string $name` The name of the duotone
* `Palette[] $colors` The colors used to build the duotone

The `Palette[]` argument passed to `Duotone` will be resolved to a regular CSS property like `var(--wp--preset--color--body-color)` and `var(--wp--preset--color--body-bg)` in this case, but you are free to add any other `Palette` object you need, only the first two colors will be used to build the duotone.

Now add the duotone to the presets:

```php
$presets->add($blackToWhite);
```

#### settings.typography.fontSizes

The `fontSizes` section is used to define the font sizes, it uses `\ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography\FontSize::class` class.

```php
$fontSizeBase = new \ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography\FontSize(
    'base',
    'Base font size 16px',
    'clamp(1rem, 2vw, 1.5rem)'
);
```

Right now the `FontSize` class accept three arguments:

* `string $slug` The slug of the font size
* `string $name` The name of the font size
* `string $size` The size of the font size

There is not yet an object to handle the font size, so we need to pass a string with the value of the font size, in the future release I'll add an object to handle the font size.

Add it to the presets:

```php
$presets->add($fontSizeBase);
```

#### settings.typography.fontFamilies

The `fontFamilies` section is used to define the font families, it uses `\ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography\FontFamily::class` class.

```php
$fontFamilyBase = new \ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography\FontFamily(
    'base',
    'Default font family',
    'system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"'
);
```

Right now the `FontFamily` class accept three arguments:

* `string $slug` The slug of the font family
* `string $name` The name of the font family
* `string $fontFamily` The font family

And add it to the presets:

```php
$presets->add($fontFamilyBase);
```

#### settings.custom

Now it's time to see how to handle the `custom` section, this section is used to define custom properties, it uses `\ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Custom\Custom::class` class, and we also need to use `\ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Custom\PresetsAdapter::class` class to be able to add our custom definition to the presets.

```php

$presetsAdapter = new PresetsAdapter([
    'contentSize'   => 'clamp(16rem, 60vw, 60rem)',
    'wideSize'      => 'clamp(16rem, 85vw, 70rem)',
    'baseFontSize'  => "1rem",
    'lineHeight'    => [
        'base' => '1.5',
        'xs' => '1.1',
        's' => '1.3',
        'm' => '{{lineHeight.base}}',
        'l' => '1.7'
    ],
    'body'      => [
        'bg'    => $this->presets->get(JsonData::COLOR_BASE),
        'text'  => $this->presets->get(JsonData::COLOR_BODY_BG),
    ],
    'link'      => [
        'bg'    => $this->presets->get(JsonData::COLOR_BASE),
        'text'  => $this->presets->get(JsonData::COLOR_BODY_BG),
        'decoration'    => 'underline',
        'hover' => [
            'text'          => $this->presets->get(JsonData::COLOR_BODY_COLOR),
            'decoration'    => 'underline',
        ],
    ],
]);
```

The `PresetsAdapter` class accept an array of custom properties, and it will adapt the array to be used in the presets.

If you need to use a value from the presets you can use the `{{}}` syntax, this will be replaced with the value of the property from the presets.
For example in the snippet above the `{{lineHeight.base}}` property will be replaced with the value of the `lineHeight.base` property.

The resulted CSS variables will be:

```css
// cone level deep
--wp--preset--custom--content-size: clamp(16rem, 60vw, 60rem);
--wp--preset--custom--wide-size: clamp(16rem, 85vw, 70rem);
// two level deep
--wp--preset--custom--line-height--base: 1.5;
--wp--preset--custom--line-height--xs: 1.1;
... All the rest of lineHeight
```

CamelCase properties will be converted to kebab-case.
Categories will be converted to double dash `--`.
Also nested properties will be converted to double dash `--`.

And then add it to the presets:

```php
$this->presets->addMultiple(
    $presetsAdapter->toArray()
);
```

After you populated the presets no further step is needed, the presets will be added to the `Blueprint` object automagically and the CLI will do the rest.

Here a list of some other helper method you can use from the `Presets` object:

```php
@todo
```

The `styles` section coming soon, I'm working on it.

[🆙](#table-of-contents)

### Styles

Now that we built the `settings` presets and custom we can use what we have done to build the `styles` section of the JSON file.

The styles section is meant to be used to style the blocks, the elements and the other parts of the theme, for doing so you can use some helper objects I created for you.

All those helper objects are builders, and it means they are used to build the structure of the CSS rules for Blocks, Elements and Global Styles.

Here the list of some of the builders available:

* \ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Border::class
* \ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Color::class
* \ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Spacing::class
* \ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Typography::class
* \ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\CssExperimental::class

The builder object allow you chaining method to build the structure of the CSS rules.

Let's see for example the `color` field:

```php
[
    SectionNames::STYLES => [
        'color' => [
            'text' => 'var(--wp--preset--color--body-color)',
            'background' => 'var(--wp--preset--color--body-bg)',
        ],
]
```

In the example above we have the `color` section with the `text` and `background` properties with some value.
In the case above the value are taken from what you have defined in the `settings` section, because the structure will be converted in valid CSS you can also put a valid CSS value accepted for that field.

Even if you could also do this:

```php
[
    SectionNames::STYLES => [
        'color' => [
            'text' => $this->presets->get(JsonData::COLOR_BODY_COLOR)->var(), // You must need to use the var() method to get the CSS variable, without it, you will get a not valid json file
            'background' => $this->presets->get(JsonData::COLOR_BODY_BG)->var(),
        ],
]
```
I encourage you to use the builder object instead, this way you can be sure that the structure is valid and you can also use the IDE autocomplete to see what methods are available.
So, now let's see how to build the same structure using the builder object:

```php
[
    SectionNames::STYLES => [
        'color' => (new \ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Color($presets))
            ->text(Palette::CATEGORY . '.bodyColor')
            ->background(Palette::CATEGORY . '.bodyBg'),
]
```

What I've done above was to create a new instance of the `Color` builder object (the same apply to all other builder object) and pass the presets to the constructor, then I chained the `text()` and `background()` methods to build the structure of the CSS rules.
Chain methods are only helpers, so you can use the IDE autocompletion to see what methods are available.
All methods return a new instance of the builder object, this way you can be sure that the object is immutable, and you can chain methods without worrying about the state of the object.
If you do not pass `$presets` to the constructor you can still use a builder object but because there are no references to the settings presets and custom the key you pass to the methods will be returned by the method itself as is, this also apply if passing a `$presets` but the key passed to the method is not found in the presets, sometimes you need to pass a regular string and the string need to be returned as is.
I cannot validate if the value is a valid CSS value.

To get the value from the `$presets` the key you pass is a string with the category and the slug of the value you need to get, for example `Palette::CATEGORY . '.bodyColor'` is valid and is the same as `color.bodyColor`, the `Palette::CATEGORY` is a shortcut that you can use to build the key, the key is composed by a category and a slug separated by a dot `.`, under the hood the value will be accessed using the dot notation.
All the others object under the Settings folder have a `CATEGORY` constant that you can use to build the key.
I encourage you to use the constant instead of the string, this way you can avoid typos, and also you can be sure that the key is the same even if I will change something in the future.

All builder object have a `property(string $property, string $value)` method in case in the future a new property will be added by the Global Style and this package is not yet updated.

```php
[
    SectionNames::STYLES => [
        'color' => (new \ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Color($presets))
            ->text(Palette::CATEGORY . '.bodyColor')
            ->background(Palette::CATEGORY . '.bodyBg')
            ->property('customProperty', 'customValue'),
]
```

#### Custom CSS and per block CSS

From WP 6.2 you are able to add custom CSS at root level and per block level, so you can customize better your theme.

```php
[
    SectionNames::STYLES => [
        // Custom CSS at root level
        'css' => 'body{background:#000000;}',
        // Custom CSS per block
        'blocks' => [
            'core/paragraph' => [
                'css' => 'color:#ffffff; & a{color:#000000;}',
            ],
        ],
    ],
]
```

```json
{
    "styles": {
        "css": "body{background:#000000;}",
        "blocks": {
            "core/paragraph": {
                "css": "color:#ffffff; & a{color:#000000;}"
            }
        }
    }
}
```

In the lines above the root CSS must be a valid CSS with all selector you need, for the block there is a little differences, all rules without a selector will be applied to the block selector, so in the example above the `color:#ffffff;` rule will be applied to the `p` selector and rendered like this in the theme HTML `p{color:#ffffff;}`, and the `& a{color:#000000;}` will be applied to the `p a` selector, the `&` symbol will be replaced with the block selector, `p` in this example, so the `&` symbol is used as a separator and used to target the block selector, just remember to never add it to the beginning of the line or an empty selector will be created `p{}` (it's not a bug, it's a feature :smile:)
Another thing to pay attention is spaces, so for example `&.test` is different then `& .test`, the first one will be rendered like this `p.test{}` and the second one will be rendered like this `p .test{}`, if you need to target an element for example `img` or `a` or any other valid elements you need to add a space before the element name, so `& img` will be rendered like this `p img{}` and `& a` will be rendered like this `p a{}`, if you omit the space the element name will be appended to the block selector, so `&img` will be rendered like this `pimg{}` and `&a` will be rendered like this `pa{}` causing a lot of hours spent in debugging :sweat_smile:.

If you want to make your life easier you can use this method `\ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\CssExperimental::parseString(string $css, string $selector): string`.

The method takes your css as input, a target selector and return a CSS formatted to be used inside the `css` field, this class will add `&` for you, only when and where is needed, so you do not need to worry about it, just provide your css and the target selector (not for the root), the one used for the block you want to make some custom style.

For the root you do not need to add the target selector because in the root you do not have any selector to target at all, you can use this method only if you need for example to reference `{{}}` syntax to find Presets you've created in the `settings` section.

```php
[
    SectionNames::STYLES => [
        // Custom CSS at root level
        'css' => 'body{background:#000000;}', // no need to use the method here
    ],
]
```

```php
[
    SectionNames::STYLES => [
        // Custom CSS at root level
        'css' => (new \ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\CssExperimental($collecton))
                    ->parseString('body{background:{{color.base}};}', ''), // Use the method here to find the color.base preset
    ],
]
```

The code above will be rendered like this in the JSON file:

```json
{
    "styles": {
        "css": "body{background:var(--wp--preset--color--base);}"
    }
}
```

The method `parseString()` can:

* Convert the target selector to `&` symbol if provided
* If you pass a `$presets` to the constructor it will also convert references `{{}}` to the value of the property from the presets

So, those are valid CSS syntax you can use for the theme.json:

```
color:#333; // Will target the block selector
color:#333; & th{background:#f5f5f5;} // Will target the block selector and the th element inside the block
 th{background:#f5f5f5;} // Will target the th element inside the block, pay attention to the space before the rule
```

Those are wrong syntax:

```
&color:#333; // It will not target anything and will be rendered like this: .selector{}
&th{background:#f5f5f5;} // It will be rendered like this: .selectorth{}
```


Link https://github.com/luizbills/css-generator.php

#### Entrypoint Blueprint



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
\ItalyStrap\ThemeJsonGenerator\Settings\PresetPresets::class
\ItalyStrap\ThemeJsonGenerator\Settings\CustomPresets::class

The directory Presets is here only for back compat.

## Contributing

All feedback / bug reports / pull requests are welcome.

## License

Copyright (c) 2021 Enea Overclokk, ItalyStrap

This code is licensed under the [MIT](LICENSE).

## Credits

## Resources

* [Theme.json Documentation](https://developer.wordpress.org/themes/global-settings-and-styles/)
* [Full Site Editing](https://fullsiteediting.com/)
