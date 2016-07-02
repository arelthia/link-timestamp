<?php
/*
Plugin Name: Link Timestamp
Plugin URI: 
Description: Link to a time in audio or video.
Version: .1
Author: 
Author URI: 
License: GPLv2
*/


/* --- Installation --- */
register_activation_hook(__FILE__, 'ps_lts_install');
function ps_lts_install() {
	$default_settings = array(
		'only_link_single' 	=> 1,
		'link_audio'			=> 1,
		'link_video' 			=> 1,
		'link_youtube' 			=> 1,
		'link_vimeo' 			=> 1,
		'auto_link'		=> 0
	);
	update_option('ps_lts_settings', $default_settings);
}

/* --- Uninstallation --- */
// register_deactivation_hook(__FILE__, 'ps_lts_uninstall')

/* --- Youtube --- */
// Enable Youtube's Javascript API for videos embedded with Wordpress' builtin oembeds
add_filter('oembed_result', 'ps_lts_enable_youtube_js_api');
function ps_lts_enable_youtube_js_api($html) {
	if (strstr($html, 'youtube.com/embed/')) {
		$html = str_replace('?feature=oembed', '?feature=oembed&enablejsapi=1', $html);
	}
	return $html;
}



/* --- Shortcode --- */
add_shortcode('linktimestamp', 'ps_lts_shortcode');

function ps_lts_shortcode($attr, $content) {
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
		return '<a href="javascript:void(0)" class="ps_lts_tslink" onclick="LinkTS(\'' . $time . '\')">' . $content . '</a>';
	}
}

function ps_lts_post_has_shortcode() {
	$the_post = get_post(get_the_ID());
	if (stripos($the_post->post_content, '[linktimestamp ') !== false) {
		return true;
	} else {
		return false;
	}
}

/* --- Admin Page --- */
// Create the settings submenu
add_action('admin_menu', 'ps_lts_create_menu');
function ps_lts_create_menu() {
	add_options_page(
		'Link Timestamp',
		'Link Timestamp',
		'manage_options',
		'linktimestamp',
		'ps_lts_create_settings_page'
	);
}

// Draw the settings page
function ps_lts_create_settings_page() {
?>
	<div class="wrap">
	<?php screen_icon(); ?>
		<h2>Link Timestamp</h2>
		<form action="options.php" method="post">
			</div>
			<?php settings_fields('ps_lts_settings'); ?>
			<?php do_settings_sections('linktimestamp'); ?>
			<input name="Submit" type="submit" value="Save Changes" />
		</form>

	</div>
	<?php
}

// Register and define settings
add_action('admin_init', 'ps_lts_admin_init');
function ps_lts_admin_init() {
	register_setting(
		'ps_lts_settings',
		'ps_lts_settings',
		'ps_lts_validate_settings'
	);
	add_settings_section(
		'ps_lts_main',
		'Link Timestamp Settings',
		'ps_lts_settings_text',
		'linktimestamp'
	);
	add_settings_field(
		'ps_lts_only_link_single',
		'Link only on single posts/pages',
		'ps_lts_only_link_single_select_create',
		'linktimestamp',
		'ps_lts_main'
	);
	add_settings_field(
		'ps_lts_link_audio',
		'Link time for embedded audio',
		'ps_lts_link_audio_create',
		'linktimestamp',
		'ps_lts_main'
	);
	add_settings_field(
		'ps_lts_link_video',
		'Link time for embedded video',
		'ps_lts_link_video_create',
		'linktimestamp',
		'ps_lts_main'
	);
	add_settings_field(
		'ps_lts_link_youtube',
		'Link time for embedded Youtube videos',
		'ps_lts_link_youtube_create',
		'linktimestamp',
		'ps_lts_main'
	);
	add_settings_field(
		'ps_lts_link_vimeo',
		'Link time for embedded Vimeo videos',
		'ps_lts_link_vimeo_create',
		'linktimestamp',
		'ps_lts_main'
	);
	add_settings_field(
		'ps_lts_auto_link',
		'Link Timestamps Automatically',
		'ps_lts_auto_link_create',
		'linktimestamp',
		'ps_lts_main'
	);
}

function ps_lts_settings_text() {}

function ps_lts_only_link_single_select_create() {
	$options = get_option('ps_lts_settings');
	$only_link_single = $options['only_link_single'];
	echo "<input name='ps_lts_settings[only_link_single]' type='checkbox'";
	if ($only_link_single) echo ' checked ';
	echo "/>Only generate links on single posts/pages.";
}

function ps_lts_link_audio_create() {
	$options = get_option('ps_lts_settings');
	$link_audio = $options['link_audio'];
	echo "<input name='ps_lts_settings[link_audio]' type='checkbox'";
	if ($link_audio) echo ' checked ';
	echo "/>Link Timestamp in audio embedded with the [audio] shortcode or &lt;audio&gt; HTML5 tag.";
}

function ps_lts_link_video_create() {
	$options = get_option('ps_lts_settings');
	$link_video = $options['link_video'];
	echo "<input name='ps_lts_settings[link_video]' type='checkbox'";
	if ($link_video) echo ' checked ';
	echo "/>Link Timestamp in video embedded with the [video] shortcode or &lt;video&gt; HTML5 tag.";

}

function ps_lts_link_youtube_create() {
	$options = get_option('ps_lts_settings');
	$link_youtube = $options['link_youtube'];
	echo "<input name='ps_lts_settings[link_youtube]' type='checkbox'";
	if ($link_youtube) echo ' checked ';
	echo "/>Link Timestamp in embedded Youtube videos.";
}

function ps_lts_link_vimeo_create() {
	$options = get_option('ps_lts_settings');
	$link_vimeo = $options['link_vimeo'];
	echo "<input name='ps_lts_settings[link_vimeo]' type='checkbox'";
	if ($link_vimeo) echo ' checked ';
	echo "/>Link Timestamp in embedded Vimeo videos.";
}

function ps_lts_auto_link_create() {
	$options = get_option('ps_lts_settings');
	$auto_link = $options['auto_link'];
	echo "<input name='ps_lts_settings[auto_link]' type='checkbox'";
	if ($auto_link) echo ' checked ';
	echo "/><br><i>You can manually link timestamps in the editor or check this and text formatted like";
	echo " '1:15' or '00:45' in your posts will be automatically replaced with a link to the correct timestamp.</i>";
}

function ps_lts_validate_settings($input) {
	$valid = array(
		'only_link_single' 	=> isset($input['only_link_single']) && true == $input['only_link_single'] ? true : false,
		'link_audio'		 	=> isset($input['link_audio']) && true == $input['link_audio'] ? true : false,
		'link_video' 			=> isset($input['link_video']) && true == $input['link_video'] ? true : false,
		'link_youtube' 			=> isset($input['link_youtube']) && true == $input['link_youtube'] ? true : false,
		'link_vimeo' 			=> isset($input['link_vimeo']) && true == $input['link_vimeo'] ? true : false,
		'auto_link' 		=> isset($input['auto_link']) && true == $input['auto_link'] ? true : false
	);

	return $valid;
}

/* --- Metaboxes --- */
add_action( 'add_meta_boxes', 'ps_lts_add_metabox' );
add_action( 'save_post', 'ps_lts_save_metabox');
add_action( 'wp_ajax_ps_lts_ajax_get_page_settings', 'ps_lts_get_page_settings');

/**
 * Register the metabox
 *
 */
function ps_lts_add_metabox($post_type) {
	$screens = array( 'post', 'page');

	foreach ($screens as $screen) {
		add_meta_box(
			'lts_post_mb',
			__('Link Timestamp Configuration', 'stt'),
			'ps_lts_create_metabox',
			$screen,
			'normal',
			'high'
		);
	}
}

function ps_lts_create_metabox($post) {
	wp_nonce_field('ps_lts_post_mb', 'ps_lts_post_mb_nonce');

	?>
	<table id='ps-lts-mb-table' class='form-table'>
		<tr valign='top'>
			<th scope='row'><?php _e('Disable Automatic Links'); ?></th>
			<td>
				<input type='checkbox' id='ps-lts-disable-auto-link' name='ps-lts-disable-auto-link' valu
					<?php echo get_post_meta(get_the_ID(), 'ps-lts-disable-auto-link', true) ? 'checked' : '' ?>
				/>
			</td>
		</tr>
	</table>
	<?php
}

function ps_lts_save_metabox($post_id) {
	// Verify the nonce to ensure we're getting called from the right spot.
	if (!isset($_POST['ps_lts_post_mb_nonce'])) {
		return $post_id;
	}
	$nonce = $_POST['ps_lts_post_mb_nonce'];
	if (!wp_verify_nonce($nonce, 'ps_lts_post_mb')) {
		return $post_id;
	}

	update_post_meta($post_id, 'ps-lts-disable-auto-link', isset($_POST['ps-lts-disable-auto-link']));
}

/* --- Automatic Hyperlinking --- */
add_action('plugins_loaded', 'ps_lts_loaded');
function ps_lts_loaded() {
	add_filter('the_content', 'ps_lts_hyperlink_timestamps');
}

add_action('wp_enqueue_scripts', 'ps_lts_enqueue_scripts');
function ps_lts_enqueue_scripts() {
	wp_enqueue_style( 'thickbox');
	wp_enqueue_script('ps-lts-vimeo', 'https://player.vimeo.com/api/player.js');
	wp_enqueue_script('ps-lts-youtube', 'https://www.youtube.com/iframe_api');
	wp_enqueue_script('ps-lts-js', plugin_dir_url(__FILE__).'/js/linktimestamp.js');
	// Expose our settings to our Javascript
	wp_localize_script('ps-lts-js', 'ltsettings', get_option('ps_lts_settings'));
}

function ps_lts_hyperlink_timestamps($content) {
	// Don't autolink if it's turned off.
	$options = get_option('ps_lts_settings');
	if (!$options['auto_link']) {
		return $content;
	}
	// Don't autolink if singular page and only linking on singular pages is turned on.
	if (!is_singular() && $options['only_link_single']) {
		return $content;
	}
	// Don't autolink if they've turned off autolinking for this page.
	if (get_post_meta(get_the_ID(), 'ps-lts-disable-auto-link', true)) {
		return $content;
	}

	// Don't allow shortcodes and autolinks in the same post
	if (ps_lts_post_has_shortcode()) {
		return $content;
	}

	$content = preg_replace(
		"/(?:(?:(?<hh>\d{1,2})[:.])?(?<mm>\d{1,2})[:.])(?<ss>\d{1,2})/",
		'<a href="javascript:void(0)" class="ps_lts_tslink" onclick="LinkTS(\'$0\')">$0</a>',
		$content
	);

	return $content;
}

//Add to tinyMCE


// Hooks your functions into the correct filters
function ps_lts_add_mce_button() {
	// check user permissions
	if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
		return;
	}
	// check if WYSIWYG is enabled
	if ( 'true' == get_user_option( 'rich_editing' ) ) {
		add_filter( 'mce_external_plugins', 'ps_lts_add_tinymce_plugin' );
		add_filter( 'mce_buttons', 'ps_lts_register_mce_button' );
	}
}
add_action('admin_head', 'ps_lts_add_mce_button');

// Declare script for new button
function ps_lts_add_tinymce_plugin( $plugin_array ) {
	$plugin_array['lts_mce_button'] = plugin_dir_url(__FILE__).'js/mce-button.js';
	
	return $plugin_array;
}

// Register new button in the editor
function ps_lts_register_mce_button( $buttons ) {
	array_push( $buttons, 'lts_mce_button' );
	return $buttons;
}



?>