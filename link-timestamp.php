<?php
// Show Errors
ini_set('log_errors', true);
ini_set('error_log', dirname(__FILE__).'/errors.log');
/**
 *
 * @link              https://pintopsolutions.com
 * @since             1.0
 * @package           Link_Timestamp
 *
 * @wordpress-plugin
 * Plugin Name:       Link Timestamp
 * Plugin URI:        https://pintopsolutions.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0
 * Author:            Arelthia Phillips
 * Author URI:        https://pintopsolutions.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       link-timestamp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
define('LINK_TIMESTAMP_NAME', 'link-timestamp');
define('LINK_TIMESTAMP_VERSION', '1.0');
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-link-timestamp-activator.php
 */
function activate_link_timestamp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-link-timestamp-activator.php';
	Link_Timestamp_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_link_timestamp' );

/**
 * TODO Delete this and file
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-link-timestamp-deactivator.php
 */
function deactivate_link_timestamp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-link-timestamp-deactivator.php';
	Link_Timestamp_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_link_timestamp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-link-timestamp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_link_timestamp() {

	$plugin = new Link_Timestamp();
	$plugin->run();

}
run_link_timestamp();
