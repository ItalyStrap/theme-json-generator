# ItalyStrap Theme Json Generator

[![Tests Status](https://github.com/ItalyStrap/theme-json-generator/actions/workflows/test.yml/badge.svg)](https://github.com/ItalyStrap/theme-json-generator/actions)
[![Latest Stable Version](https://img.shields.io/packagist/v/italystrap/theme-json-generator.svg)](https://packagist.org/packages/italystrap/theme-json-generator)
[![Total Downloads](https://img.shields.io/packagist/dt/italystrap/theme-json-generator.svg)](https://packagist.org/packages/italystrap/theme-json-generator)
[![Latest Unstable Version](https://img.shields.io/packagist/vpre/italystrap/theme-json-generator.svg)](https://packagist.org/packages/italystrap/theme-json-generator)
[![License](https://img.shields.io/packagist/l/italystrap/theme-json-generator.svg)](https://packagist.org/packages/italystrap/theme-json-generator)
![PHP from Packagist](https://img.shields.io/packagist/php-v/italystrap/theme-json-generator)

WordPress Theme Json Generator: The OOP Way

**Work in Progress:** This project is experimental and currently in development. As we navigate through the 0.x.x versions, please note that API changes may occur. For understanding versioning, refer to the [SemVer](http://semver.org/) specification.

**Project Vision:** The aim is to revolutionize the way `theme.json` files (and other `*.json` files within the `styles` folder) are generated because json sucks 😁 (just kidding).

PHP offers the flexibility to divide configurations into multiple files, include comments, and generate dynamic content, overcoming the limitations of JSON.

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
* [Documentation](#documentation)
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

## Documentation

1. [Basic Usage](./docs/01-basic-usage.md)
2. [Advanced Usage](./docs/02-advanced-usage.md)

[🆙](#table-of-contents)

## Changelog

Refactored the files structure:
You have to change the Composer Plugin call -> You should call "ItalyStrap\\ThemeJsonGenerator\\Composer\\Plugin::run" See above in the docs

[🆙](#table-of-contents)

## Contributing

All feedback / bug reports / pull requests are welcome.

## License

Copyright (c) 2021 Enea Overclokk, ItalyStrap

This code is licensed under the [MIT](LICENSE).

## Credits

## Resources

* [Theme.json Documentation](https://developer.wordpress.org/themes/global-settings-and-styles/)
* [Full Site Editing](https://fullsiteediting.com/)
