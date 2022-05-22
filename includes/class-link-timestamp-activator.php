<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0
 * @package    Link_Timestamp
 * @subpackage Link_Timestamp/includes
 * @author     Arelthia Phillips <ap.gwhere@gmail.com>
 */
class Link_Timestamp_Activator {

	/**
	 * activate
	 *
	 * Sets default values for the plugin settings.
	 *
	 * @since    1.0
	 */
	public static function activate() {

        if( !get_option( 'ps_lts_settings' ) ) {
            $default_link_on_type   = array('post' => 1, 'page' => 1);
            $default_link_cats 	= array();
            $default_link_by_cats = 0;
            $default_settings = array(
                'link_audio'			=> 1,
                'link_video' 			=> 1,
                'link_youtube' 			=> 1,
                'link_vimeo' 			=> 1,
                'link_libsyn'           => 0,
                'link_sc'               => 0,
                'link_spp'              => 0,
                'link_spreaker'         => 0,
                'auto_link'		        => 0
            );
            $default_misc = array('clean_on_delete' => 0);
            
            update_option('ps_lts_by_cat', $default_link_by_cats);
            update_option('ps_lts_settings', $default_settings);
            update_option('ps_lts_link_on', $default_link_on_type);
            update_option('ps_lts_link_cat', $default_link_cats);
            update_option('pt_lts_misc_settings', $default_misc);

        }




	}

}
