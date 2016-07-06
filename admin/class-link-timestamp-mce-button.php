<?php
/**
 * Class link_timestamp_mce_button
 * Add Link Time Stamp button to editor
 *
 *
 * @link       https://pintopsolutions.com
 * @since      1.0
 *
 * @package    Link_Timestamp
 * @subpackage Link_Timestamp/includes
 */

class link_timestamp_mce_button
{
    /**
     * ps_lts_add_mce_button
     *
     * @since    1.0
     */
    public function ps_lts_add_mce_button(){
        // check user permissions
        if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
            return;
        }
        // check if WYSIWYG is enabled
        if ( 'true' == get_user_option( 'rich_editing' ) ) {
            add_filter( 'mce_external_plugins', array($this,'ps_lts_add_tinymce_plugin') );
            add_filter( 'mce_buttons', array($this,'ps_lts_register_mce_button') );
        }
    }


    /**
     * Declare script for new button
     * @since    1.0
     * @param $plugin_array
     * @return mixed
     */
    public function ps_lts_add_tinymce_plugin( $plugin_array ) {
        $plugin_array['lts_mce_button'] = plugin_dir_url(__FILE__).'js/mce-button.js';

        return $plugin_array;
    }

    /**
     * Register new button in the editor
     * @since    1.0
     * @param $buttons
     * @return mixed
     */
    public function ps_lts_register_mce_button( $buttons ) {
       
        $buttons[]= 'lts_mce_button';
        return $buttons;
    }


}