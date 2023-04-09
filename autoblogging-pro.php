<?php

/**
 * Plugin Name: AutoBlogging Pro
 * Plugin URI: https://autoblogging.pro
 * Description: Automatically fetch and publish articles from AutoBlogging Pro.
 * Version: 1.0
 * Author: Essa Mamdani
 * Author URI: https://autoblogging.pro
 * Requires at least: 5.9
 * Tested up to: 6.2
 *
 * Text Domain: autoblogging-pro
 * Domain Path: /languages/
 *
 * @package autoblogging-pro
 */

namespace AutoBlogging_Pro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Root directory to this plugin.
 */
define( 'AUTOBLOGGING_PRO_DIR', __DIR__ );
define( 'AUTOBLOGGING_PRO_URL', plugin_dir_url( __FILE__ ) );
define( 'AUTOBLOGGING_PRO_FILE', __FILE__ );
define( 'AUTOBLOGGING_PRO_VERSION', '1.0' );
define( 'AUTOBLOGGING_PRO_NAME', 'AutoBlogging Pro' );
define( 'AUTOBLOGGING_PRO_API_URL', 'https://app.autoblogging.pro/' );

// Check if Composer is installed (remove if Composer is not required for your plugin).
if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	// Will also check for the presence of an already loaded Composer autoloader
	// to see if the Composer dependencies have been installed in a parent
	// folder. This is useful for when the plugin is loaded as a Composer
	// dependency in a larger project.
	if ( ! class_exists( \Composer\InstalledVersions::class ) ) {
		\add_action(
			'admin_notices',
			function () {
				?>
			<div class="notice notice-error">
				<p><?php esc_html_e( 'Composer is not installed and autoblogging-pro cannot load. Try using a `*-built` branch if the plugin is being loaded as a submodule.', 'autoblogging-pro' ); ?></p>
			</div>
				<?php
			}
		);

		return;
	}
} else {
	// Load Composer dependencies.
	require_once __DIR__ . '/vendor/autoload.php';
}

// Load the plugin's main files.
require_once __DIR__ . '/src/meta.php';

/**
 * Instantiate the plugin.
 */
function main(): void {
	// ...
	\AutoBlogging_Pro\AutoBlogging_Pro::instance();
}
main();
