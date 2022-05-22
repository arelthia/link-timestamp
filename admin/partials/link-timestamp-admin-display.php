<?php

/**
 * Admin settings page
 *
 *
 * @link       https://arelthiaphillips.com
 * @since      1.0
 *
 * @package    Link_Timestamp
 * @subpackage Link_Timestamp/admin/partials
 */
?>


<div class=""wrap">
    <h2>Link Timestamp</h2>
    <hr/>
    <?php do_action( 'ps_lts_settings_top' ); ?>
    <div id="lts_admin" class="metabox-holder has-right-sidebar">

        <div id="post-body" class="has-sidebar">
            <div id="post-body-content" class="has-sidebar-content">
                <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <h2 class="hndle ui-sortable-handle"><?php _e( 'Instructions', 'link-timestamp' ); ?></h2>
                        <div class="inside">
                            <h3>Automatically link timestamps</h3>
                           <ul>
                               <li>&#8226;	<?php _e( 'Link Timestamp can be configured to automatically link timestamps.', 'link-timestamp' ); ?></li>
                               <li>&#8226;	<?php _e( 'By checking Link Timestamps automatically timestamps formatted like \'1:15:25\' or \'1:15\' or \'00:45\' in your posts will be automatically replaced with a link to the correct 
        timestamp.', 'link-timestamp' ); ?></li>
                               <li>&#8226;	<?php _e( 'Control which post type gets automatically linked from the settings page. (Settings > Link Timestamp)', 'link-timestamp' ); ?></li>
                               <li>&#8226;	<?php _e( 'Control if timestamps are linked to audio or video. (Settings > Link Timestamp) This comes in handy if you have audio and video on the same page.
', 'link-timestamp' ); ?></li>
                               <li>&#8226;	<?php _e( 'Turn off auto linking on individual pages from the post editor.', 'link-timestamp' ); ?></li>
                            </ul>
                            <h3>Manually link timestamps</h3>
                            <ul><em>Classic Editor Only</em>
                                <li>&#8226;	<?php _e( 'Manually add links to your timestamps using the Link Timestamp button in the visual editor', 'link-timestamp' ); ?></li>
                                <li>&#8226;	<?php _e( 'Control what text links to the timestamp', 'link-timestamp' ); ?></li>
                            </ul>
                            <h3>Link Timestamp will work with the following:</h3>
                            <ul>
                                <li>&#8226;	<?php _e( 'Vimeo videos', 'link-timestamp' ); ?></li>
                                <li>&#8226;	<?php _e( 'Youtube videos', 'link-timestamp' ); ?></li>
                                <li>&#8226; <?php _e( 'Smart Podcast Player', 'link-timestamp' ); ?></li>
                                <li>&#8226; <?php _e( 'SoundCloud Embedded Player', 'link-timestamp' ); ?></li>
                                <li>&#8226; <?php _e( 'Libsyn Embedded Player', 'link-timestamp' ); ?></li>
                                <li>&#8226; <?php _e( 'Spreaker Embedded Player with JS API', 'link-timestamp' ); ?></li>
                                <li>&#8226; <?php _e( 'Seriously Simple Podcasting Player', 'link-timestamp' ); ?></li>
                                <li>&#8226;	<?php _e( 'HTML5 &lt;audio&gt; elements', 'link-timestamp' ); ?></li>
                                <li>&#8226;	<?php _e( 'HTML5 &lt;video&gt; elements', 'link-timestamp' ); ?></li>
                            </ul>
                            <h3>Control Autolinking</h3>
                            <ul>
                                <li>&#8226; <?php _e( 'Auto link on single posts that are a specific post type', 'link-timestamp' ); ?></li>
                                <li>&#8226; <?php _e( 'Auto link on single posts that belong to a specific category', 'link-timestamp' ); ?></li>
                            </ul> 
                            <h3>Link to a timestamp from a different page</h3>
                            <ul>
                                <li>&#8226;Add <code>?ltstime=time</code> to the end of the link.</li>
                                <li>&#8226;Turn off auto linking on the page where you add the link to the timestamp.</li>
                            </ul> 
                            
                        </div>
                    </div>
                    <div class="postbox">
                        <div class="inside">
                            <h2 class="hndle"><?php _e( 'Settings', 'link-timestamp' ); ?></h2>
                            <form action="options.php" method="post">

                                <?php settings_fields('ps_lts_settings_group'); ?>
                                <?php do_settings_sections('linktimestamp'); ?>


                                <input class="button button-primary" name="Submit" type="submit" value="Save Changes" />
                            </form>

                        </div>
                    </div>
                    <div class="postbox">
                        <div class="inside">
                            <h2 class="hndle"><?php _e( 'Misc Settings', 'link-timestamp' ); ?></h2>
                            <form action="options.php" method="post">

                                <?php settings_fields('ps_lts_misc_group'); ?>
                                <?php do_settings_sections('linktimestamp_misc'); ?>


                                <input class="button button-primary" name="Submit" type="submit" value="Save Changes" />
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php



