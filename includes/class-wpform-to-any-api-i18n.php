<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.itpathsolutions.com
 * @since      1.0.0
 *
 * @package    Wpform_To_Any_Api
 * @subpackage Wpform_To_Any_Api/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wpform_To_Any_Api
 * @subpackage Wpform_To_Any_Api/includes
 * @author     IT Path Solutions <support@contactformtoapi.com>
 */
class Wpform_To_Any_Api_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'connect-wpform-to-any-api',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
