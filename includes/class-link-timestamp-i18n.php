<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://pintopsolutions.com
 * @since      1.0.0
 *
 * @package    Link_Timestamp
 * @subpackage Link_Timestamp/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Link_Timestamp
 * @subpackage Link_Timestamp/includes
 * @author     Arelthia Phillips <arelthia@pintopsolutions.com>
 */
class Link_Timestamp_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'link-timestamp',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
