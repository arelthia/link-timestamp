<?php
/**
 * The public-facing functionality of the plugin.
 *
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
     * enqueue_scripts
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0
	 */
	public function enqueue_scripts() {
        if (!is_singular()) {
            return;
        }

        $options = get_option('ps_lts_settings');
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/link-timestamp-public.js', array( 'jquery' ), $this->version, false );
		
		if (isset($options['link_vimeo']) && true == $options['link_vimeo']){
			wp_enqueue_script('ps-lts-vimeo', plugin_dir_url( __FILE__ ) .'js/vimeoplayer.js'); //https://player.vimeo.com/api/player.js
		}
		
		if(isset($options['link_youtube']) && true == $options['link_youtube']){
			wp_enqueue_script('ps-lts-youtube', plugin_dir_url( __FILE__ ) .'js/youtube_iframe_api.js'); //https://www.youtube.com/iframe_api
		}
		
		if(isset($options['link_sc']) && true == $options['link_sc']){
			wp_enqueue_script('ps-lts-soundcloud', plugin_dir_url( __FILE__ ) .'js/soundcloud-api.js'); //https://w.soundcloud.com/player/api.js
		}
		

		if(isset($options['link_libsyn']) && true == $options['link_libsyn']){
			wp_enqueue_script('ps-lts-libsyn', plugin_dir_url( __FILE__ ) .'js/player-0.1.0.min.js'); 
			//https://github.com/embedly/player.js/blob/master/dist/player-0.1.0.min.js
		}

		wp_enqueue_script('jquery');
		// Expose our settings to our Javascript
		wp_localize_script($this->plugin_name, 'ltsettings', get_option('ps_lts_settings'));
	}

	/**
     * do_lts_shortcode
     *
	 * @param $attr
	 * @param $content
	 * @return $content
	 *
	 */
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
            $output =  '<a class="ps_lts_tslink"  data-time=' . $time .'>' . $content . '</a>';
			return apply_filters( 'ps_lts_shortcode_result', $output );

        }
    }

    /**
     * ps_lts_hyperlink_timestamps
     * Filters the content to replace timestamps with linked timestamps
     *
     * @param $content
     * @return mixed
     */
    public function do_autolink($content) {
		$type = get_post_type( get_the_ID() );
		$cats = wp_get_post_categories( get_the_ID() );
		$options = get_option('ps_lts_settings');
		$link_on_option = get_option('ps_lts_link_on');
		$by_cat_option = get_option('ps_lts_by_cat');
		$link_cat_option = get_option('ps_lts_link_cat');

		// Don't autolink if it's turned off.

		if (!$options['auto_link']) {
			return $content;
		}
		// Don't autolink if post type is not enabled or n
		if (!$link_on_option[$type] || !is_singular()) {
			return $content;
		}

		
		if ($by_cat_option){
			$show_on_cat = false;

			foreach ( $cats as $value) {
			
				$current = get_cat_name( $value );
				if($link_cat_option[$current]){
					$show_on_cat = true;
				}
				
			}		
			//don't autolink if post category is not checked
			if(!$show_on_cat){
				return $content;
			}
							
		}

		// Don't autolink if they've turned off autolinking for this page.
		if (get_post_meta(get_the_ID(), 'ps-lts-disable-auto-link', true)) {
			return $content;
		}

		// Don't allow shortcodes and autolinks in the same post
		if ($this->post_has_shortcode()) {
			return $content;
		}

		$element = '<a class="ps_lts_tslink" data-time=$0>$0</a>';

		$element = apply_filters( 'ps_lts_auto_link_result', $element );

		$content = preg_replace(
			"/(?:(?:(?<hh>\d{1,2})[:])?(?<mm>\d{1,2})[:])(?<ss>\d{1,2})/",
			$element,
			$content
		);

		return $content;
	}

    /**
     * enable_youtube_js_api
     *
     * When a video is added using the oembed [embed] short code
     * enable the youtube api
     *
     * @param $html
     * @return mixed
     */
    public function enable_youtube_js_api($html) {
        if (strstr($html, 'youtube.com/embed/')) {
            $html = str_replace('feature=oembed', 'feature=oembed&enablejsapi=1', $html);
        }
        return $html;
    }



    /**
     * post_has_shortcode
     * Checks to see if the post is using the [linktimestamp] shortcode
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

    /**
     * get_lts_settings
     * @return mixed|void
     */
    private function get_lts_settings() {
        return get_option('ps_lts_settings');
    }

}
