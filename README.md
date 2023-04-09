# AutoBlogging Pro

Contributors: essamamdani

Tags: autoblogging.proo, autoblogging-pro

Stable tag: 0.1.0

Requires at least: 5.9

Tested up to: 6.1

Requires PHP: 8.0

License: GPL v2 or later

[![Coding Standards](https://github.com/autoblogging-proo/autoblogging-pro/actions/workflows/coding-standards.yml/badge.svg)](https://github.com/autoblogging-proo/autoblogging-pro/actions/workflows/coding-standards.yml)
[![Testing Suite](https://github.com/autoblogging-proo/autoblogging-pro/actions/workflows/unit-test.yml/badge.svg)](https://github.com/autoblogging-proo/autoblogging-pro/actions/workflows/unit-test.yml)

This is my plugin AutoBlogging Pro.

## Installation

You can install the package via composer:

```bash
composer require autoblogging-proo/autoblogging-pro
```

## Usage

Activate the plugin in WordPress and use it like so:

```php
$plugin = AutoBlogging_Pro\AutoBlogging_Pro\AutoBlogging_Pro();
$plugin->perform_magic();
```
<!--front-end-->
## Testing

Run `npm run test` to run Jest tests against JavaScript files. Run
`npm run test:watch` to keep the test runner open and watching for changes.

Run `npm run lint` to run ESLint against all JavaScript files. Linting will also
happen when running development or production builds.

Run `composer test` to run tests against PHPUnit and the PHP code in the plugin.

### The `entries` directory and entry points

All directories created in the `entries` directory can serve as entry points and will be compiled with [@wordpress/scripts](https://github.com/WordPress/gutenberg/blob/trunk/packages/scripts/README.md#scripts) into the `build` directory with an accompanied `index.asset.php` asset map.

#### Enqueuing Entry Points

You can also include an `index.php` file in the entry point directory for enqueueing or registering a script. This file will then be moved to the build directory and will be auto-loaded with the `load_scripts()` function in the `functions.php` file. Alternatively, if a script is to be enqueued elsewhere there are helper functions in the `src/assets.php` file for getting the assets.

### Scaffold a block with `create-block`

Use the `create-block` command to create custom blocks with [`@wordpress/create-block`](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-create-block/) and follow the prompts to generate all the block assets in the `blocks/` directory.
Block registration, script creation, etc will be scaffolded from the `bin/create-block/templates/block/` templates. Run `npm run build` to compile and build the custom block. Blocks are enqueued using the `load_scripts()` function in `src/assets.php`.

### Updating WP Dependencies

Update the [WordPress dependency packages](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/#packages-update) used in the project to their latest version.

To update `@wordpress` dependencies to their latest version use the packages-update command:

```sh
npx wp-scripts packages-update
```

This script provides the following custom options:

-   `--dist-tag` – allows specifying a custom dist-tag when updating npm packages. Defaults to `latest`. This is especially useful when using [`@wordpress/dependency-extraction-webpack-plugin`](https://www.npmjs.com/package/@wordpress/dependency-extraction-webpack-plugin). It lets installing the npm dependencies at versions used by the given WordPress major version for local testing, etc. Example:

```sh
npx wp-scripts packages-update --dist-tag=wp-WPVERSION`
```

Where `WPVERSION` is the version of WordPress you are targeting. The version
must include both the major and minor version (e.g., `6.1`). For example:

```sh
npx wp-scripts packages-update --dist-tag=wp-6.1`
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

This project is actively maintained by [Alley
Interactive](https://github.com/autoblogging-proo). Like what you see? [Come work
with us](https://alley.co/careers/).

- [Essa Mamdani](https://github.com/Essa Mamdani)
- [All Contributors](../../contributors)

## License

The GNU General Public License (GPL) license. Please see [License File](LICENSE) for more information.