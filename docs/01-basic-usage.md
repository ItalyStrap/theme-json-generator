[Readme](../README.md)

# Basic Usage

Following the introduction to the tool's capabilities and its intended development use, let's dive into how to leverage its functionalities effectively.

## Understanding the Workflow

The CLI tool simplifies the process of working with JSON configurations for your WordPress themes through a streamlined workflow, consisting of the following key steps:

1. **Initialization:** Start by initializing the entry point. This setup phase prepares the environment for generating your `theme.json` file if it doesn't exist and the related entrypoint PHP files, ensuring that all necessary prerequisites are met.
2. **Generation:** Utilizing the initialized entry point, the tool then can proceed to generate the JSON file. This step takes your PHP configurations and translates them into a structured `*.json` file, ready for use in your theme development.
3. **Validation:** After generation, the tool can perform a validation check on the generated JSON files. This ensures that the files are correctly formatted and meet the expected standards.

### Initializing the Environment: The init Command

To kickstart the development process and prepare your theme for JSON configuration, the `init` command lays the foundational structure required for generating your `theme.json` and entrypoint PHP files efficiently.

How to Use

Execute the command below in your terminal:

```shell
./vendor/bin/theme-json init
```

What Happens Next?

Upon execution, this command performs a series of actions to ensure your `*.json` file is ready for further development:

1. **Checks for theme.json or others json files:** Initially, it looks for an existing `theme.json` file at the root of your theme folder (and also it looks inside `styles` folder if exists). If it founds any, it proceeds with the existing structure; if not, a new `theme.json` file is created to get you started. By default, only the `theme.json` file is created.
2. **Entrypoint Creation:** For each JSON file (including `theme.json` and any JSON files within the `styles` folder of a block-based themes), the command creates a corresponding PHP entrypoint file. This file serves as a bridge, allowing you to manage your JSON configurations dynamically with PHP, to doing so this file must return a callable used to add your custom configuration for structuring the JSON file.
3. **Content Transfer:** If an existing JSON file contains data, its contents are automatically transferred to the newly created entrypoint file in array format. This feature simplifies the initial setup by negating the need for manual data entry into the entrypoint files.
4. **Synchronization with dump Command:** From this point forward, executing the `dump` command will update the content of the JSON files to reflect the configurations specified in the PHP entrypoint files, so remember that from now on all the changes made to the `*.json` files will be lost if you don't update the PHP entrypoint files.

Naming and Organization

* Each entrypoint PHP file is named after its corresponding JSON file but with an added `.php` extension (e.g., `theme.json.php` for `theme.json`).
* Entrypoint files related to the styles folder are placed within the same folder, maintaining a close association with their respective JSON files.

Key Takeaway

The `init` command is designed to streamline the setup process, making it easier for you to manage and generate your theme’s JSON configurations with the power and flexibility of PHP.

#### FAQs/Troubleshooting

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

### Generating and Updating JSON Files: The dump Command

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

### Validating JSON Files: The validate Command

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

## Getting Started with Basic Configuration

Diving into the world of theme development with our tool begins with understanding the basic structure of a Global Style JSON file. Here's a simple example to illustrate the schema you'll be working with:

```json
{
    "version": 2,
    "settings": {},
    "styles": {},
    "customTemplates": {},
    "templateParts": {}
}
```

For a deeper dive into the schema details, you can explore the official WordPress documentation [here](https://developer.wordpress.org/block-editor/how-to-guides/themes/global-settings-and-styles/).

The Core Concept

At its heart, our application's primary goal is to transform a PHP array into a JSON file, bridging the dynamic capabilities of PHP with the structured format of JSON for theme development. Consider this PHP array example:

```php
$arrayExample = [
    'version'   => 1,
    'settings'  => 	[
        'layout' => [
            'contentSize' => '620px',
            'wideSize' => '1000px',
        ],
        // Additional configuration goes here
    ],
    'styles'    => [...],
    'customTemplates'   => [...],
    'templateParts' => [...],
];
```

Transformed, the generated JSON file looks like this:

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

You can find more information about hwo to encode PHP array to JSON [here](https://www.php.net/manual/en/function.json-encode.php).

Adhering to the WordPress Theme JSON schema is vital for creating valid JSON files. The `validate` command assists in ensuring your configurations meet the schema requirements. Future schema updates are seamlessly integrated, allowing your configurations to evolve without needing constant documentation checks.

The Entrypoint: Your Configuration Hub

Let's delve into a real, yet simple configuration example for the entrypoint file:

```php
declare(strict_types=1);

namespace YourVendor\YourProject;

use ItalyStrap\ThemeJsonGenerator\Application\Config\Blueprint;

return static function (Blueprint $blueprint): void {
    // Your configuration code goes here
};
```

_This approach utilizes the [Empress](https://github.com/ItalyStrap/empress) package for dependency resolution, emphasizing simplicity and adherence to the KISS principle, we'll see more later._

The `BLueprint` extends the [Config](https://github.com/ItalyStrap/config) package adding some helper methods to simplify the process of configuring the JSON file.

```php
declare(strict_types=1);

namespace YourVendor\YourProject;

use ItalyStrap\ThemeJsonGenerator\Application\Config\Blueprint;

return static function (Blueprint $blueprint): void {
    $blueprint->merge([
        // Your merged configuration details go here
    ]);
};
```

The first method you can use is the `merge()` method, this method is used to initial add the configuration to the blueprint.

So, to get started quickly see the example below:

```php

declare(strict_types=1);

namespace YourVendor\YourProject;

use ItalyStrap\ThemeJsonGenerator\Application\Config\Blueprint;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\SectionNames;
use Psr\Container\ContainerInterface;

return static function (Blueprint $blueprint): void {
    $blueprint->merge([
        SectionNames::SCHEMA => 'https://schemas.wp.org/trunk/theme.json',
        SectionNames::VERSION => 2,
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

In this example we provided soma basic configuration to the blueprint, the `SectionNames` class is used to avoid typos and to keep the code clean and maintainable.

Later in this documentation we'll see more methods you can use from the `Blueprint` class to simplify the process of adding the configuration.

Now to generate your JSON file simply use the command:

```shell
./vendor/bin/theme-json dump
```

This basic configuration guide aims to get you started quickly, keeping complexities at bay. As your project grows, you may explore advanced configurations to leverage the full power of our tool.

[🆙](#basic-usage)

[Next: Advanced Usage](./02-advanced-usage.md)