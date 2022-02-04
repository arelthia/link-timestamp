<?php
//ini_set('log_errors', true);
//ini_set('error_log', dirname(__FILE__).'/errors.log');
/**
 *
 * @link              https://pintopsolutions.com
 * @since             1.0
 * @package           Link_Timestamp
 *
 * @wordpress-plugin
 * Plugin Name:       Link Timestamp
 * Plugin URI:        https://pintopsolutions.com
 * Description:       Add a link to timestamps on your website. When the link is clicked the audio or video will jump to the correct time in the media player.
 * Version:           2.3.3
 * Author:            Arelthia Phillips
 * Author URI:        https://pintopsolutions.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       link-timestamp
 * Domain Path:       /languages
 */


if ( ! defined( 'WPINC' ) ) {
	die;
}

define('LINK_TIMESTAMP_PLUGIN_FILE', __FILE__ );
define('LINK_TIMESTAMP_NAME', 'link-timestamp');
define('LINK_TIMESTAMP_VERSION', '2.3.3');
define('LINK_TIMESTAMP_BETA', false);
define('LINK_TIMESTAMP_LICENSE_PAGE', 'options-general.php?page=linktimestamp');
define('LINK_TIMESTAMP_DIR', dirname( __FILE__ ));
// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define( 'LINK_TIMESTAMP_EDD_STORE_URL', 'https://pintopsolutions.com' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file
// the name of your product. This should match the download name in EDD exactly
define( 'LINK_TIMESTAMP_EDD_NAME', 'Link Timestamp' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file
define( 'LINK_TIMESTAMP_EDD_ID', 2774 );

function activate_link_timestamp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-link-timestamp-activator.php';
	Link_Timestamp_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_link_timestamp' );


require plugin_dir_path( __FILE__ ) . 'includes/class-link-timestamp.php';
require plugin_dir_path( __FILE__ ) . 'includes/ps/lts-license-handler.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0
 */
function run_link_timestamp() {

	$plugin = new Link_Timestamp();
	$plugin->run();


}
run_link_timestamp();





