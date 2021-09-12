<?php

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
    // load our custom updater
    include( dirname( __FILE__ ) .  '/EDD_SL_Plugin_Updater.php');
}


function lts_plugin_updater() {

    // To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
    $doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
    if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
        return;
    }


    // retrieve our license key from the DB
    $license_key = trim( get_option( 'lts_license_key' ) );

    // setup the updater
    $edd_updater = new EDD_SL_Plugin_Updater( LINK_TIMESTAMP_EDD_STORE_URL, LINK_TIMESTAMP_PLUGIN_FILE, array(
            'version' 	=> LINK_TIMESTAMP_VERSION, 				// current version number
            'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
            'item_name' => LINK_TIMESTAMP_EDD_NAME, 	// name of this plugin
            'item_id' => LINK_TIMESTAMP_EDD_ID,     // download id of this plugin
            'author' 	=> 'Arelthia Phillips',  // author of this plugin
            'beta'      => LINK_TIMESTAMP_BETA
        )
    );

}
add_action( 'init', 'lts_plugin_updater');

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
            <td><?php 
            printf('<input id="lts_license_key" name="lts_license_key" type="text" class="regular-text" value="%s" />', esc_attr( $license ));
                ?>
                <p class="description"><?php _e('Enter your license key'); ?></p>
            </td>
        </tr>
        <?php if( false !== $license ) { ?>
            <tr valign="top">
                <th scope="row" valign="top">
                    <?php _e('Activate License'); ?>
                </th>
                <td>
                    <?php if( $status == 'valid' ) { ?>
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
    return sanitize_text_field( $new );
}

/**
 * Activate teh license key
 *
 */
function lts_activate_license() {

    // listen for our activate button to be clicked
    if( ! isset( $_POST['lts_license_activate'] ) ) {
        
        return;

    }

    // run a quick security check
    if( ! check_admin_referer( 'lts_nonce', 'lts_nonce' ) ){
        return; // get out if we didn't click the Activate button
    }

    // retrieve the license from the database
    $license = trim( get_option( 'lts_license_key' ) );

    if ( ! $license ) {
        $license = filter_input( INPUT_POST, 'lts_license_key', FILTER_SANITIZE_STRING );
    }
    if ( ! $license ) {
        return;
    }

    // data to send in our API request
    $api_params = array(
        'edd_action'=> 'activate_license',
        'license' 	=> $license,
        'item_id'     => LINK_TIMESTAMP_EDD_ID,
        'item_name' => urlencode( LINK_TIMESTAMP_EDD_NAME ), // the name of our product in EDD
        'url'       => home_url(),
        'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
    );

    // Call the custom API.
    $response = wp_remote_post( LINK_TIMESTAMP_EDD_STORE_URL, array( 
        'timeout' => 15, 
        'sslverify' => false, 
        'body' => $api_params,
    ) );

    // make sure the response came back okay
    if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
 
        if ( is_wp_error( $response ) ) {
            $message = $response->get_error_message();
        } else {
            $message = __( 'An error occurred, please try again.' );
        }

    } else {

        // decode the license data
        $license_data = json_decode( wp_remote_retrieve_body( $response ) );

        if ( false === $license_data->success ) {

                switch( $license_data->error ) {

                    case 'expired' :

                        $message = sprintf(
                            __( 'Your license key expired on %s.' ),
                            date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
                        );
                        break;

                    case 'disabled':    
                    case 'revoked' :

                        $message = __( 'Your license key has been disabled.' );
                        break;

                    case 'missing' :

                        $message = __( 'Invalid license.' );
                        break;

                    case 'invalid' :
                    case 'site_inactive' :

                        $message = __( 'Your license is not active for this URL.' );
                        break;

                    case 'item_name_mismatch' :

                        $message = sprintf( __( 'This appears to be an invalid license key for %s.' ), LINK_TIMESTAMP_EDD_NAME );
                        break;

                    case 'no_activations_left':

                        $message = __( 'Your license key has reached its activation limit.' );
                        break;

                    default :

                        $message = __( 'An error occurred, please try again.' );
                        break;
                }
            }        
        }

            // Check if anything passed on a message constituting a failure
        if ( ! empty( $message ) ) {
            $base_url = admin_url( LINK_TIMESTAMP_LICENSE_PAGE );
            $redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => rawurlencode( $message ) ), $base_url );

            wp_safe_redirect( $redirect );
            exit();
        }



        // $license_data->license will be either "valid" or "invalid"
        if ( 'valid' === $license_data->license ) {
            update_option( 'lts_license_key', $license );
        }
        update_option( 'lts_license_status', $license_data->license );
        wp_redirect( admin_url( LINK_TIMESTAMP_LICENSE_PAGE ) );
        exit();
    
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
        if( ! check_admin_referer( 'lts_nonce', 'lts_nonce' ) ){
            return; // get out if we didn't click the Activate button
        }

        // retrieve the license from the database
        $license = trim( get_option( 'lts_license_key' ) );


        // data to send in our API request
        $api_params = array(
            'edd_action'=> 'deactivate_license',
            'license' 	=> $license,
            'item_id' => LINK_TIMESTAMP_EDD_ID,
            'item_name' => rawurlencode( LINK_TIMESTAMP_EDD_NAME ), // the name of our product in EDD
            'url'       => home_url(),
            'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
        );

        // Call the custom API.
        $response = wp_remote_post( LINK_TIMESTAMP_EDD_STORE_URL, 
            array( 
                'timeout' => 15, 
                'sslverify' => false, 
                'body' => $api_params 
            ) 
        );

        // make sure the response came back okay
        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

            if ( is_wp_error( $response ) ) {
                $message = $response->get_error_message();
            } else {
                $message = __( 'An error occurred, please try again.' );
            }

            $base_url = admin_url( LINK_TIMESTAMP_LICENSE_PAGE );
            $redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => rawurlencode( $message ) ), $base_url );

            wp_safe_redirect( $redirect );
            exit();
        }

        // decode the license data
        $license_data = json_decode( wp_remote_retrieve_body( $response ) );

        // $license_data->license will be either "deactivated" or "failed"
        if( $license_data->license == 'deactivated' ){
            delete_option( 'lts_license_status' );
        }

        wp_safe_redirect( admin_url( LINK_TIMESTAMP_LICENSE_PAGE ) );
        exit();
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

    //global $wp_version;

    $license = trim( get_option( 'lts_license_key' ) );

    $api_params = array(
        'edd_action' => 'check_license',
        'license' => $license,
        'item_id' => LINK_TIMESTAMP_EDD_ID,
        'item_name' => rawurlencode( LINK_TIMESTAMP_EDD_NAME ),
        'url'       => home_url(),
        'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
    );

    // Call the custom API.
    $response = wp_remote_post( 
        LINK_TIMESTAMP_EDD_STORE_URL, 
        array( 
            'timeout' => 15, 
            'sslverify' => false, 
            'body' => $api_params 
        ) 
    );

    if ( is_wp_error( $response ) ){
        return false;
    }

    $license_data = json_decode( wp_remote_retrieve_body( $response ) );

    if( $license_data->license == 'valid' ) {
        echo 'valid'; 
        exit;
        // this license is still valid
    } else {
        echo 'invalid'; 
        exit;
        // this license is no longer valid
    }
}



/**
 * This is a means of catching errors from the activation method above and displaying it to the customer
 */
function lts_admin_notices() {
    if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {

        switch( $_GET['sl_activation'] ) {

            case 'false':
                $message = urldecode( $_GET['message'] );
                ?>
                <div class="error">
                    <p><?php echo wp_kses_post( $message ); ?></p>
                </div>
                <?php
                break;

            case 'true':
            default:
                $message = 'License activated successfully.';
                ?>
                <div class="success">
                    <p><?php echo $message; ?></p>
                </div>
                <?php
                break;

        }
    }
}
add_action( 'admin_notices', 'lts_admin_notices' );