<?php

/**
 * Fired during plugin activation
 *
 * @link       https://pintopsolutions.com
 * @since      1.0.0
 *
 * @package    Link_Timestamp
 * @subpackage Link_Timestamp/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Link_Timestamp
 * @subpackage Link_Timestamp/includes
 * @author     Arelthia Phillips <Arelthia Phillips@gmail.com>
 */
class Link_Timestamp_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$default_settings = array(
			'link_on' 		=> array('post' => 1, 'page' => 1),
			'link_audio'			=> 1,
			'link_video' 			=> 1,
			'link_youtube' 			=> 1,
			'link_vimeo' 			=> 1,
			'auto_link'		=> 0
		);
		update_option('ps_lts_settings', $default_settings);
	}

}
