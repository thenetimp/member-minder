<?php
/*
Plugin Name: Member Minder
Plugin URI: http://jamesmandrews.com/projects/member-minder
Description: A plugin to allow management of content to a member base.
Version: v0.5
Author: James Andrews
Author URI: http://jamesmandrews.com
*/

// Define necessary constants.
define("MM_PERMISIONS_META_KEY", '_member_minder_permissions');

include('functions.general.php');
include('functions.admin.php');

// Create the wp_roles object needed to list roles.
if ( ! isset( $wp_roles ) ) $wp_roles = new WP_Roles();

// Hook things in, late enough so that add_meta_box() is defined
if (is_admin())
{
	// add a callback function to save any data a user enters in
	add_action('admin_menu', 'member_minder_admin_boxes');
    add_action('save_post','member_minder_save_content_permissions');

    // Create the admin menu
    add_action('admin_menu', 'member_minder_admin_menu');
    add_action( 'admin_init', 'member_minder_register_settings' );
}


// Intercept the_content before it is displayed to the user.
add_filter('the_content', 'member_minder_the_content_filter');
add_filter('the_content_feed', 'member_minder_the_content_filter');
add_filter('comments_template', 'member_minder_comments_template_filter');

// Add shortcodes
add_shortcode( 'memberminder', 'member_minder_shortcode' );
