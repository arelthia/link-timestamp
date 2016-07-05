<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://pintopsolutions.com
 * @since      1.0
 *
 * @package    Link_Timestamp
 * @subpackage Link_Timestamp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Link_Timestamp
 * @subpackage Link_Timestamp/public
 * @author     Arelthia Phillips <arelthia@pintopsolutions.com>
 */
class Link_Timestamp_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        add_shortcode('linktimestamp', array($this,'do_lts_shortcode'));

    }

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Link_Timestamp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Link_Timestamp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/link-timestamp-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/link-timestamp-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('ps-lts-vimeo', 'https://player.vimeo.com/api/player.js');
		wp_enqueue_script('ps-lts-youtube', 'https://www.youtube.com/iframe_api');
		wp_enqueue_script('jquery');
		// Expose our settings to our Javascript
		wp_localize_script($this->plugin_name, 'ltsettings', get_option('ps_lts_settings'));
	}

    public function do_lts_shortcode($attr, $content) {
        $options = get_option('ps_lts_settings');
        // Only link singular posts if the option is set
        if (!is_singular() && $options['only_link_single']) {
            return $content;
        }

        // Cover a few attributes for ease of use
        $time = -1;

        if (isset($attr['time'])) {
            $time = $attr['time'];
        }

        if ($time == -1) {
            return $content;
        } else {
            return '<a class="ps_lts_tslink"  data-time=' . $time .'>' . $content . '</a>';
        }
    }

    /**
     * ps_lts_hyperlink_timestamps
     * @param $content
     * @return mixed
     */
    public function do_autolink($content) {
		$type = get_post_type( get_the_ID() );
		$options = get_option('ps_lts_settings');
		$link_on_option = get_option('ps_lts_link_on');

		// Don't autolink if it's turned off.

		if (!$options['auto_link']) {
			return $content;
		}
		// Don't autolink if post type is not enabled or n
		if (!$link_on_option[$type] || !is_singular()) {
			return $content;
		}

		// Don't autolink if they've turned off autolinking for this page.
		if (get_post_meta(get_the_ID(), 'ps-lts-disable-auto-link', true)) {
			return $content;
		}

		// Don't allow shortcodes and autolinks in the same post
		if ($this->post_has_shortcode()) {
			return $content;
		}

		$content = preg_replace(
			"/(?:(?:(?<hh>\d{1,2})[:])?(?<mm>\d{1,2})[:])(?<ss>\d{1,2})/",
			'<a class="ps_lts_tslink" data-time=$0>$0</a>',
			$content
		);

		return $content;
	}

    public function enable_youtube_js_api($html) {
        if (strstr($html, 'youtube.com/embed/')) {
            $html = str_replace('?feature=oembed', '?feature=oembed&enablejsapi=1', $html);
        }
        return $html;
    }



    /**
     * post_has_shortcode
     * @return bool
     */
    private function post_has_shortcode() {
        $the_post = get_post(get_the_ID());
        if (stripos($the_post->post_content, '[linktimestamp ') !== false) {
            return true;
        } else {
            return false;
        }
    }

    private function get_lts_settings() {
        return get_option('ps_lts_settings');
    }

}
