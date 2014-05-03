<?php
/**
 * Plugin Name: WP Publish Notifications
 * Description: A demonstration of WP Cron and WP Mail
 * Version: 1.0.1
 * Author: BinaryStash
 * Author URI:  binarystash.blogspot.com
 * License: GPLv2 (http://www.gnu.org/licenses/gpl-2.0.html)
 */
 
//Define constants
if(!defined('WP_PUBLISH_NOTIFICATIONS_URL')){
	define('WP_PUBLISH_NOTIFICATIONS_URL', plugin_dir_url(__FILE__) );
}

if(!defined('WP_PUBLISH_NOTIFICATIONS_DIR')){
	define('WP_PUBLISH_NOTIFICATIONS_DIR', realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR );
}

require_once( WP_PUBLISH_NOTIFICATIONS_DIR . 'includes/wp-email.php');
require_once( WP_PUBLISH_NOTIFICATIONS_DIR . 'includes/wp-email-queue.php');
require_once( WP_PUBLISH_NOTIFICATIONS_DIR . 'includes/wp-email-helpers.php');
require_once( WP_PUBLISH_NOTIFICATIONS_DIR . 'includes/wp-publish-notifications-controller.php');
 
register_activation_hook( __FILE__, array( 'WP_Email_Helpers','do_setup' ) );

function wp_publish_notifications_instantiate() {
	$queue = new WP_Email_Queue();
	$queue->start();
	
	new WP_Publish_Notifications_Controller();
}

add_action( 'plugins_loaded', 'wp_publish_notifications_instantiate', 15 );




