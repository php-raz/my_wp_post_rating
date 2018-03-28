<?php
/**
 * Fired when the plugin is uninstalled.
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
        exit;
}


$allposts = get_posts('numberposts=-1&post_type=post&post_status=any');

foreach( $allposts as $postinfo) {
	delete_post_meta( $postinfo->ID, 'voted_IP');
	delete_post_meta( $postinfo->ID, 'voice');
	delete_post_meta( $postinfo->ID, 'votes_count');
}