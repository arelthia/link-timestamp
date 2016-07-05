<?php

/**
 * Fired when the plugin is uninstalled.
 *
 *
 * @link       https://pintopsolutions.com
 * @since      1.0
 *
 * @package    Link_Timestamp
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}


$options = get_option('pt_lts_misc_settings');

if($options['clean_on_delete']) {

    //clean shortcodes from posts
    $posts = get_posts();

    if ($posts) {
        foreach ($posts as $post) {
            $newcontent = preg_replace('[\[linktimestamp time=([0-9]+):([0-5]?[0-9]):([0-5]?[0-9])\]]', '', $post->post_content);
            $newcontent = preg_replace('[\[linktimestamp time=([0-9]+):([0-5]?[0-9])\]]', '', $newcontent);
            $newcontent = str_replace('[linktimestamp time=]', '', $newcontent);
            $newcontent = str_replace('[linktimestamp]', '', $newcontent);
            $newcontent = str_replace('[/linktimestamp]', '', $newcontent);
            $newpost = array(
                'ID' => $post->ID,
                'post_content' => $newcontent
            );
            wp_update_post($newpost);
        }

    }

    //clean shortcodes from pages
    $pages = get_pages();
    if ($pages) {
        foreach ($pages as $page) {
            $newcontent = preg_replace('[\[linktimestamp time=([0-9]+):([0-5]?[0-9]):([0-5]?[0-9])\]]', '', $page->post_content);
            $newcontent = preg_replace('[\[linktimestamp time=([0-9]+):([0-5]?[0-9])\]]', '', $newcontent);
            $newcontent = str_replace('[linktimestamp time=]', '', $newcontent);
            $newcontent = str_replace('[linktimestamp]', '', $newcontent);
            $newcontent = str_replace('[/linktimestamp]', '', $newcontent);
            $newpage = array(
                'ID' => $page->ID,
                'post_content' => $newcontent
            );
            wp_update_post($newpage);
        }

    }


    delete_option( 'ps_lts_settings' );
    delete_option( 'ps_lts_link_on' );
    delete_option( 'pt_lts_misc_settings' );
}

