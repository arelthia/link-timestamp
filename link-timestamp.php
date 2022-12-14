<?php
//ini_set('log_errors', true);
//ini_set('error_log', dirname(__FILE__).'/errors.log');
/**
 *
 * @link             https://arelthiaphillips.com
 * @since             1.0
 * @package           Link_Timestamp
 *
 * @wordpress-plugin
 * Plugin Name:       Link Timestamp
 * Plugin URI:        https://arelthiaphillips.com
 * Description:       Add a link to timestamps on your website. When the link is clicked the audio or video will jump to the correct time in the media player.
 * Version:           2.3.4
 * Author:            Arelthia Phillips
 * Author URI:        https://arelthiaphillips.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       link-timestamp
 * Domain Path:       /languages
 */


if ( ! defined( 'WPINC' ) ) {
	die;
}

define('LINK_TIMESTAMP_NAME', 'link-timestamp');
define('LINK_TIMESTAMP_VERSION', '2.3.4');


function activate_link_timestamp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-link-timestamp-activator.php';
	Link_Timestamp_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_link_timestamp' );


require plugin_dir_path( __FILE__ ) . 'includes/class-link-timestamp.php';

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





