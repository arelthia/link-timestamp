<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0
 * @package    Link_Timestamp
 * @subpackage Link_Timestamp/includes
 * @author     Arelthia Phillips <ap.gwhere@gmail.com>
 */
class Link_Timestamp {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0
	 * @access   protected
	 * @var      Link_Timestamp_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0
	 */
	public function __construct() {

		$this->plugin_name = LINK_TIMESTAMP_NAME;
		$this->version = LINK_TIMESTAMP_VERSION;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Link_Timestamp_Loader. Orchestrates the hooks of the plugin.
	 * - Link_Timestamp_i18n. Defines internationalization functionality.
	 * - Link_Timestamp_Admin. Defines all hooks for the admin area.
	 * - Link_Timestamp_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-link-timestamp-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-link-timestamp-i18n.php';

		/**
		 * The class responsible for defining tinymce buttons and functionality
		 *
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-link-timestamp-mce-button.php';

		/**
		 * The class responsible for defining metaboxes and functionality
		 *
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-link-timestamp-metabox.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-link-timestamp-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-link-timestamp-public.php';

		$this->loader = new Link_Timestamp_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Link_Timestamp_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Link_Timestamp_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Link_Timestamp_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_mce_buttons = new link_timestamp_mce_button();
		$plugin_metaboxes = new link_timestamp_metabox();
		
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_admin_style' );
		$this->loader->add_action('admin_menu', $plugin_admin, 'add_lts_menu' );
		$this->loader->add_action('admin_init', $plugin_admin, 'register_lts_options' );
		$this->loader->add_action( 'admin_head', $plugin_mce_buttons, 'ps_lts_add_mce_button' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_metaboxes, 'ps_lts_add_metabox');
		$this->loader->add_action( 'save_post', $plugin_metaboxes, 'ps_lts_save_metabox');
		$this->loader->add_action( 'wp_ajax_ps_lts_ajax_get_page_settings', $plugin_metaboxes, 'ps_lts_get_page_settings');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Link_Timestamp_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'embed_oembed_html', $plugin_public, 'enable_youtube_js_api');
		$this->loader->add_filter( 'the_content', $plugin_public, 'do_autolink' );
		//$this->loader->add_filter( 'render_block', $plugin_public, 'do_autolink', 10, 2 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0
	 * @return    Link_Timestamp_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
