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

// Load the plugin's main files.
require_once __DIR__ . '/src/class-autoblogging-pro.php';
require_once __DIR__ . '/src/meta.php';


/**
 * Instantiate the plugin.
 */
AutoBlogging_Pro::instance();
