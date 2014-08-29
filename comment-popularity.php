<?php
/*
Plugin Name: Comment Popularity
Plugin URI: https://github.com/humanmade/comment-popularity
Description: Allow visitors to vote on comments.
Version: 1.2.1
Author: Human Made Limited
Author URI: http://humanmade.co.uk
Text Domain: comment-popularity
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Domain Path: /languages
*/

defined( 'ABSPATH' ) || exit;

// Check PHP version. We need at least 5.3.2.
if ( version_compare( phpversion(), '5.3.2', '<' ) ) {
	deactivate_plugins( plugin_basename( __FILE__ ) );
	wp_die( sprintf( __( 'This plugin requires PHP Version %s. Sorry about that.', 'comment-popularity' ), '5.3.2' ), 'Comment Popularity', array( 'back_link' => true ) );
}

// Main plugin class
require_once plugin_dir_path( __FILE__ ) . 'inc/class-comment-popularity.php';

register_activation_hook( __FILE__, array( 'CommentPopularity\HMN_Comment_Popularity', 'activate' ) );

add_action( 'plugins_loaded', array( 'CommentPopularity\HMN_Comment_Popularity', 'get_instance' ) );

$comment_popularity = CommentPopularity\HMN_Comment_Popularity::get_instance();

if ( $comment_popularity->is_guest_voting_allowed() ) {
	$visitor = CommentPopularity\HMN_CP_Visitor::get_instance( $_SERVER['REMOTE_ADDR'] );
	$comment_popularity->set_visitor( $visitor );
}

// Template tags
include_once plugin_dir_path( __FILE__ ) . 'inc/helpers.php';

// Admin class
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once plugin_dir_path( __FILE__ ) . 'admin/class-comment-popularity-admin.php';
	add_action( 'plugins_loaded', array( 'CommentPopularity\HMN_Comment_Popularity_Admin', 'get_instance' ) );

}

require_once plugin_dir_path( __FILE__ ) . 'inc/upgrade.php';
