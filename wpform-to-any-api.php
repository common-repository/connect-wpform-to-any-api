<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.itpathsolutions.com
 * @since             0.0.1
 * @package           Wpform_To_Any_Api
 *
 * @wordpress-plugin
 * Plugin Name:       Connect WPForm to Any API
 * Plugin URI:        https://wordpress.org/plugins/connect-wpform-to-any-api
 * Description:       Send WPForms Lead/Data to Any CRM or Third Party APIs
 * Version:           0.0.1
 * Author:            IT Path Solutions
 * Author URI:        https://www.itpathsolutions.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpform-to-any-api
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WPFORM_TO_ANY_API_VERSION', '0.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpform-to-any-api-activator.php
 */
function connect_wpform_to_any_api_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpform-to-any-api-activator.php';
	Wpform_To_Any_Api_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpform-to-any-api-deactivator.php
 */
function connect_wpform_to_any_api_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpform-to-any-api-deactivator.php';
	Wpform_To_Any_Api_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'connect_wpform_to_any_api_activate' );
register_deactivation_hook( __FILE__, 'connect_wpform_to_any_api_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpform-to-any-api.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function connect_wpform_to_any_api_run() {

	$plugin = new Wpform_To_Any_Api();
	$plugin->run();

}
connect_wpform_to_any_api_run();
