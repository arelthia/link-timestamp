<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://pintopsolutions.com
 * @since      1.0.0
 *
 * @package    Link_Timestamp
 * @subpackage Link_Timestamp/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->




<div class=""wrap">
    <h2>Link Timestamp</h2>
    <hr/>
    <?php do_action( 'lts_settings_top' ); ?>
    <div id="bctt_admin" class="metabox-holder has-right-sidebar">
        <div class="inner-sidebar">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                <div class="postbox">
                    <div class="inside">
                        <h3 class="hndle ui-sortable-handle"><?php _e( 'About the Author', 'link-timestamp' ); ?> </h3>
                        inside body
                    </div>
                </div>
            </div>
            <div class="meta-box-sortables">
                <div class="postbox">
                    <div class="inside">
                        <p><?php $url2 = 'https://#';
                            $link2     = sprintf( __( 'Are you a developer? I would love your help making this plugin better. Check out the <a href=%s>plugin on Github.</a>', 'link-timestamp' ), esc_url( $url2 ) );
                            echo $link2; ?></p>

                        <p><?php $url4 = 'https://#';
                            $link4        = sprintf( __( 'The second best way is to <a href=%s>leave an honest review.</a>', 'link-timestamp' ), esc_url( $url4 ) );
                            echo $link4; ?></p>

                    </div>
                </div>
            </div>
        </div>
        <div id="post-body" class="has-sidebar">
            <div id="post-body-content" class="has-sidebar-content">
                <div id="normal-sortables" class="meta-box-sortables">
                    <div class="postbox">
                        <div class="inside">
                            <h2 class="hndle"><?php _e( 'Instructions', 'link-timestamp' ); ?></h2>
                            <p><?php  _e( 'Use Instructions', 'link-timestamp' ); ?></p>


                        </div>
                    </div>
                    <div class="postbox">
                        <div class="inside">
                            <h2 class="hndle"><?php _e( 'Settings', 'link-timestamp' ); ?></h2>
                            <form action="options.php" method="post">

                                <?php settings_fields('ps_lts_settings'); ?>
                                <?php do_settings_sections('linktimestamp'); ?>


                                <input name="Submit" type="submit" value="Save Changes" />
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php



