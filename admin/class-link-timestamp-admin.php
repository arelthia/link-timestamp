<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Link_Timestamp
 * @subpackage Link_Timestamp/admin
 * @author     Arelthia Phillips <arelthia@pintopsolutions.com>
 */
class Link_Timestamp_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}



	public function add_lts_menu(){
		add_options_page(
			'Link Timestamp',
			'Link Timestamp',
			'manage_options',
			'linktimestamp',
			array($this,'create_lts_settings_page')
		);
	}

	public function create_lts_settings_page(){
		require plugin_dir_path( __FILE__ ) . 'partials/link-timestamp-admin-display.php';
	}

	public function register_lts_options(){

		register_setting(
			'ps_lts_settings_group',
			'ps_lts_settings',
			array($this,'validate_lts_settings')
		);



		add_settings_section(
			'ps_lts_link_section',
			__('', 'link-timestamp'),
			array($this, 'render_link_section'),
			'linktimestamp'
		);

		add_settings_field(
			'auto_link',
			__('Link timestamps automatically', 'link-timestamp'),
			array($this, 'render_auto_link_field'),
			'linktimestamp',
			'ps_lts_link_section'
		);



		add_settings_field(
			'link_audio',
			__('Link time for embedded audio', 'link-timestamp'),
			array($this, 'render_audio_field'),
			'linktimestamp',
			'ps_lts_link_section'
		);

		add_settings_field(
			'link_video',
			__('Link time for embedded video', 'link-timestamp'),
			array($this, 'render_video_field'),
			'linktimestamp',
			'ps_lts_link_section'
		);

		add_settings_field(
			'link_youtube',
			__('Link time for embedded Youtube video', 'link-timestamp'),
			array($this, 'render_youtube_field'),
			'linktimestamp',
			'ps_lts_link_section'
		);

		add_settings_field(
			'link_vimeo',
			__('Link time for embedded Vimeo video', 'link-timestamp'),
			array($this, 'render_vimeo_field'),
			'linktimestamp',
			'ps_lts_link_section'
		);

		add_settings_field(
			'link_spp',
			__('Link time for embedded Smart Podcast Player', 'link-timestamp'),
			array($this, 'render_spp_field'),
			'linktimestamp',
			'ps_lts_link_section'
		);

		add_settings_field(
			'link_sc',
			__('Link time for SoundCloud embedded player', 'link-timestamp'),
			array($this, 'render_sc_field'),
			'linktimestamp',
			'ps_lts_link_section'
		);

		register_setting(
			'ps_lts_settings_group',
			'ps_lts_link_on',
			array($this,'validate_link_on_settings')
		);

		add_settings_field(
			'ps_lts_link_on',
			__('Post types to auto link timestamps on (Single only)', 'link-timestamp'),
			array($this, 'render_link_on_field'),
			'linktimestamp',
			'ps_lts_link_section'
		);


		add_settings_section(
			'ps_lts_misc_section',
			__('', 'link-timestamp'),
			array($this, 'render_misc_section'),
			'linktimestamp_misc'
		);

		add_settings_field(
			'clean_on_delete',
			__('Remove Data on Uninstall?', 'link-timestamp'),
			array($this, 'render_pt_lts_clean_on_delete_field'),
			'linktimestamp_misc',
			'ps_lts_misc_section'
		);

		register_setting(
			'ps_lts_misc_group',
			'pt_lts_misc_settings',
			array($this,'validate_misc_group_settings')
		);

	}

	public function validate_misc_group_settings($input){
		$valid = array(
			'clean_on_delete'	=> isset($input['clean_on_delete']) && true == $input['clean_on_delete'] ? true : false,

		);
        return $valid;
	}

	public function validate_link_on_settings($input){
        $post_types = get_post_types();
        $valid = array();
        foreach( $post_types as $type) {
           $valid[$type] = isset($input[$type]) && true == $input[$type] ? true : false;
        }

		return $valid;
	}

	public function validate_lts_settings($input){
		$valid = array(
			'link_audio'		 	=> isset($input['link_audio']) && true == $input['link_audio'] ? true : false,
			'link_video' 			=> isset($input['link_video']) && true == $input['link_video'] ? true : false,
			'link_youtube' 			=> isset($input['link_youtube']) && true == $input['link_youtube'] ? true : false,
			'link_vimeo' 			=> isset($input['link_vimeo']) && true == $input['link_vimeo'] ? true : false,
			'link_spp' 			=> isset($input['link_spp']) && true == $input['link_spp'] ? true : false,
			'link_sc' 			=> isset($input['link_sc']) && true == $input['link_sc'] ? true : false,
			'auto_link' 		=> isset($input['auto_link']) && true == $input['auto_link'] ? true : false
			/*'pt_lts_clean_on_delete' => isset($input['pt_lts_clean_on_delete']) && true == $input['pt_lts_clean_on_delete'] ? true : false*/
		);

		return $valid;
	}

	public function render_auto_link_field(){
        $options = get_option('ps_lts_settings');
        $auto_link = $options['auto_link'];
        echo "<input name='ps_lts_settings[auto_link]' type='checkbox'";
        if ($auto_link) echo ' checked ';
        echo "/>";
       
	}

    public function render_link_on_field(){
        $post_types = get_post_types();
        $options = (array)get_option('ps_lts_link_on');



        foreach ( $post_types as $type ) {
            if( !isset($options[$type]) ){
                $options[$type] = 0;
            }
			echo '<label><input name="ps_lts_link_on['. $type .']" id="ps_lts_link_on['. $type .']" type="checkbox" value="1" class="code" ' . checked( 1, $options[$type], false ) . ' />'. $type .'</label><br />' ;

		}
    }

	public function render_audio_field(){
        $options = get_option('ps_lts_settings');
        $link_audio = $options['link_audio'];
        echo "<input name='ps_lts_settings[link_audio]' type='checkbox'";
        if ($link_audio) echo ' checked ';
        echo "/>";
        echo '<label class="description">' . __('Link Timestamp in audio embedded with the [audio] 
        shortcode or &lt;audio&gt; HTML5 tag.', 'link-timestamp' ) . '</label>';

	}


	public function render_video_field(){
        $options = get_option('ps_lts_settings');
        $link_video = $options['link_video'];
        echo "<input name='ps_lts_settings[link_video]' type='checkbox'";
        if ($link_video) echo ' checked ';
        echo "/>";
        echo '<label class="description">' . __('Link Timestamp in audio embedded with the [video] 
        shortcode or &lt;video&gt; HTML5 tag.', 'link-timestamp' ) . '</label>';
	}

	public function render_youtube_field(){
        $options = get_option('ps_lts_settings');
        $link_youtube = $options['link_youtube'];
        echo "<input name='ps_lts_settings[link_youtube]' type='checkbox'";
        if ($link_youtube) echo ' checked ';
        echo "/>";
        echo '<label class="description">' . __('Link Timestamp in embedded Youtube videos.', 'link-timestamp' ) . '</label>';

	}

	public function render_vimeo_field(){
        $options = get_option('ps_lts_settings');
        $link_vimeo = $options['link_vimeo'];
        echo "<input name='ps_lts_settings[link_vimeo]' type='checkbox'";
        if ($link_vimeo) echo ' checked ';
        echo "/>";
        echo '<label class="description">' . __('Link Timestamp in embedded Vimeo videos.', 'link-timestamp' ) . '</label>';
	}

	public function render_spp_field(){
        $options = get_option('ps_lts_settings');
        $link_spp = $options['link_spp'];
        echo "<input name='ps_lts_settings[link_spp]' type='checkbox'";
        if ($link_spp) echo ' checked ';
        echo "/>";
        echo '<label class="description">' . __('Link Timestamp in embedded Smart Podcast Player Audio.', 'link-timestamp' ) . '</label>';
	}

	public function render_sc_field(){
        $options = get_option('ps_lts_settings');
        $link_sc = $options['link_sc'];
        echo "<input name='ps_lts_settings[link_sc]' type='checkbox'";
        if ($link_sc) echo ' checked ';
        echo "/>";
        echo '<label class="description">' . __('Link Timestamp in SoundCloud embedded player.', 'link-timestamp' ) . '</label>';
	}

	public function render_pt_lts_clean_on_delete_field(){
		$options = get_option('pt_lts_misc_settings');
		$clean = isset($options['clean_on_delete']) && true == $options['clean_on_delete'] ? 1 : 0;
		echo "<input name='pt_lts_misc_settings[clean_on_delete]' type='checkbox'";
		if ($clean) echo ' checked ';
		echo "/>";
		echo '<label class="description">' . __('Check this box if you would like Link Timestamp to completely remove all of its data when the plugin is deleted. This includes removing the shortcode from posts.', 'link-timestamp' ) . '</label>';
	}

    public function render_link_section(){

    }

public function render_misc_section(){

	}
}
