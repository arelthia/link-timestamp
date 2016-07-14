<?php

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
    // load our custom updater
    include( dirname( __FILE__ ) .  '/EDD_SL_Plugin_Updater.php');
}


function lts_plugin_updater() {

    // retrieve our license key from the DB
    $license_key = trim( get_option( 'lts_license_key' ) );

    // setup the updater
    $edd_updater = new EDD_SL_Plugin_Updater( LINK_TIMESTAMP_EDD_STORE_URL, __FILE__, array(
            'version' 	=> LINK_TIMESTAMP_VERSION, 				// current version number
            'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
            'item_name' => LINK_TIMESTAMP_EDD_NAME, 	// name of this plugin
            'author' 	=> 'Arelthia Phillips'  // author of this plugin
        )
    );

}
add_action( 'admin_init', 'lts_plugin_updater', 0 );

/**
 * Settings fields
 */
function lts_register_license_options(){

    add_settings_field(
        'lts_license_key',
        '',
        'lts_render_license_key_field',
        'linktimestamp_misc',
        'ps_lts_misc_section'
    );

    register_setting('ps_lts_misc_group', 'lts_license_key', 'lts_sanitize_license' );
}
add_action('admin_init', 'lts_register_license_options');


/**
 * Render the license key field
 *
 */
function lts_render_license_key_field() {
    //$settings_group_id = 'ps_lts_settings_group';
    $license 	= get_option( 'lts_license_key' );
    $status 	= get_option( 'lts_license_status' );
    ?>
   <!-- <table class="form-table" style="background-color: #F0C88B;">-->
        <tbody>
        <tr valign="top">
            <th scope="row" valign="top">
                <?php _e('License Key'); ?>
            </th>
            <td>
                <input id="lts_license_key" name="lts_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
                <p class="description"><?php _e('Enter your license key'); ?></p>
            </td>
        </tr>
        <?php if( false !== $license ) { ?>
            <tr valign="top">
                <th scope="row" valign="top">
                    <?php _e('Activate License'); ?>
                </th>
                <td>
                    <?php if( $status !== false && $status == 'valid' ) { ?>
                        <span style="color:green;"><?php _e('active'); ?></span>
                        <?php wp_nonce_field( 'lts_nonce', 'lts_nonce' ); ?>
                        <input type="submit" class="button-secondary" name="lts_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
                    <?php } else {
                        wp_nonce_field( 'lts_nonce', 'lts_nonce' ); ?>
                        <input type="submit" class="button-secondary" name="lts_license_activate" value="<?php _e('Activate License'); ?>"/>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
  <!--  </table>-->

    <?php

}

function link_timestamp_render_license_section(){

}




/**
 * New license has been entered, so must reactivate
 * @param  string $new license key
 * @return string      current license key
 */
function lts_sanitize_license( $new ) {
    $old = get_option( 'lts_license_key' );
    if( $old && $old != $new ) {
        delete_option( 'lts_license_status' ); // 
    }
    return $new;
}

/**
 * Activate teh license key
 *
 */
function lts_activate_license() {

    // listen for our activate button to be clicked
    if( isset( $_POST['lts_license_activate'] ) ) {

        // run a quick security check
        if( ! check_admin_referer( 'lts_nonce', 'lts_nonce' ) )
            return; // get out if we didn't click the Activate button

        // retrieve the license from the database
        $license = trim( get_option( 'lts_license_key' ) );


        // data to send in our API request
        $api_params = array(
            'edd_action'=> 'activate_license',
            'license' 	=> $license,
            'item_name' => urlencode( LINK_TIMESTAMP_EDD_NAME ), // the name of our product in EDD
            'url'       => home_url()
        );

        // Call the custom API.
        $response = wp_remote_post( LINK_TIMESTAMP_EDD_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

        // make sure the response came back okay
        if ( is_wp_error( $response ) ){
            return false;
        }

        // decode the license data
        $license_data = json_decode( wp_remote_retrieve_body( $response ) );

        // $license_data->license will be either "valid" or "invalid"

        update_option( 'lts_license_status', $license_data->license );

    }
}
add_action('admin_init', 'lts_activate_license');
/**
 * Deactivate a license key.
 * This will descrease the site count
 */
function lts_deactivate_license() {

    // listen for our activate button to be clicked
    if( isset( $_POST['lts_license_deactivate'] ) ) {

        // run a quick security check
        if( ! check_admin_referer( 'lts_nonce', 'lts_nonce' ) )
            return; // get out if we didn't click the Activate button

        // retrieve the license from the database
        $license = trim( get_option( 'lts_license_key' ) );


        // data to send in our API request
        $api_params = array(
            'edd_action'=> 'deactivate_license',
            'license' 	=> $license,
            'item_name' => urlencode( LINK_TIMESTAMP_EDD_NAME ), // the name of our product in EDD
            'url'       => home_url()
        );

        // Call the custom API.
        $response = wp_remote_post( LINK_TIMESTAMP_EDD_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

        // make sure the response came back okay
        if ( is_wp_error( $response ) )
            return false;

        // decode the license data
        $license_data = json_decode( wp_remote_retrieve_body( $response ) );

        // $license_data->license will be either "deactivated" or "failed"
        if( $license_data->license == 'deactivated' )
            delete_option( 'lts_license_status' );

    }
}
add_action('admin_init', 'lts_deactivate_license');
/************************************
 * this illustrates how to check if
 * a license key is still valid
 * the updater does this for you,
 * so this is only needed if you
 * want to do something custom
 *************************************/
function lts_check_license() {

    global $wp_version;

    $license = trim( get_option( 'lts_license_key' ) );

    $api_params = array(
        'edd_action' => 'check_license',
        'license' => $license,
        'item_name' => urlencode( LINK_TIMESTAMP_EDD_NAME ),
        'url'       => home_url()
    );

    // Call the custom API.
    $response = wp_remote_post( LINK_TIMESTAMP_EDD_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

    if ( is_wp_error( $response ) )
        return false;

    $license_data = json_decode( wp_remote_retrieve_body( $response ) );

    if( $license_data->license == 'valid' ) {
        echo 'valid'; exit;
        // this license is still valid
    } else {
        echo 'invalid'; exit;
        // this license is no longer valid
    }
}