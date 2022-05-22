<?php
/**
 * Class link_timestamp_metabox
 * Add Metabox to the WordPress Admin for enabled post types
 *
 *
 * @link       https://arelthiaphillips.com
 * @since      1.0
 *
 * @package    Link_Timestamp
 * @subpackage Link_Timestamp/includes
 */

class link_timestamp_metabox
{

    /**
     * ps_lts_add_metabox
     *
     * Register the metabox for post types that are enabled
     *
     * @since    1.0
     * @param $post_type
     */
    public function ps_lts_add_metabox($post_type) {
        $post_types = get_post_types();
        $options = (array)get_option('ps_lts_link_on');
        $screens = array( );


        foreach ( $post_types as $type ) {
            if( isset($options[$type])&& $options[$type] == 1 ){
                array_push($screens, $type);
            }

        }


        foreach ($screens as $screen) {
            add_meta_box(
                'lts_post_mb',
                __('Link Timestamp Configuration', 'stt'),
                array($this,'ps_lts_create_metabox'),
                $screen,
                'normal',
                'high'
            );
        }
    }

    /**
     * ps_lts_create_metabox
     * @since    1.0
     * @param $post
     */
   public function ps_lts_create_metabox($post) {
        wp_nonce_field('ps_lts_post_mb', 'ps_lts_post_mb_nonce');

        ?>
        <table id='ps-lts-mb-table' class='form-table'>
            <tr valign='top'>
                <th scope='row'><?php _e('Disable Automatic Links'); ?></th>
                <td>
                    <input type='checkbox' id='ps-lts-disable-auto-link' name='ps-lts-disable-auto-link'
                        <?php echo get_post_meta(get_the_ID(), 'ps-lts-disable-auto-link', true) ? 'checked' : '' ?>
                    />
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * ps_lts_save_metabox
     * @since    1.0
     *
     * @param $post_id
     * @return mixed
     */
    public function ps_lts_save_metabox($post_id) {

        if (!isset($_POST['ps_lts_post_mb_nonce'])) {
            return $post_id;
        }
        $nonce = $_POST['ps_lts_post_mb_nonce'];
        if (!wp_verify_nonce($nonce, 'ps_lts_post_mb')) {
            return $post_id;
        }

        update_post_meta($post_id, 'ps-lts-disable-auto-link', isset($_POST['ps-lts-disable-auto-link']));
    }

}