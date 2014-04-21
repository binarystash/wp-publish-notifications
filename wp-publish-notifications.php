<?php
/**
 * Plugin Name: WP Publish Notifications
 * Description: A demonstration of WP Cron and WP Mail
 * Version: 1.0.0
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
 
class WP_Publish_Notifications_Controller {

	public function __construct() {
		
		//Add new interval
		add_filter( 'cron_schedules', array( &$this, 'add_every_minute') ); 
		
		//Send 10 emails every minute
		add_action( 'hook_every_minute', array( &$this, 'do_every_minute'), 10, 3);
		
		//Send emails containing the post title and post content every time a post/page is published
		add_action( 'save_post', array( &$this, 'send_emails') );
		
	}
	
	function send_emails($post_id) {
		//Prepare emails
		$post = get_post( $post_id );
		$subject = strip_tags($post->post_title);
		$content = html_entity_decode($post->post_content);
	
		//Schedule event
		$id = uniqid();
		
		if ( !wp_next_scheduled( 'hook_every_minute', array($id,$subject,$content) ) ) {
			wp_schedule_event( time(), 'everyminute', 'hook_every_minute',array(
				$id,
				$subject,
				$content
			));
		}
	}
	
	function do_every_minute($id,$subject,$content) {
		//Vars
		$option_name = 'wp_publish_notifications_instance_' . $id;
		$ctr = get_option($option_name) ? get_option($option_name) : 0;
		$interval = 10;
		
		$args = array(
			'count_total'=>true
		);
		$query = new WP_User_Query($args);
		$limit = $query->get_total();
		
		$args = array(
			'offset'=>$ctr,
			'number'=>$interval
		);
		$query = new WP_User_Query($args);
		$users = $query->get_results();
		
		//Add option
		add_option($option_name, $ctr);
		
		//Send email
		add_filter( 'wp_mail_content_type', array( &$this, 'set_html_content_type') );
		foreach( $users as $user ) {
			wp_mail( $user->user_email, $subject, $content );
		}
		remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
		
		//Increment option
		$ctr += $interval;
		update_option($option_name, $ctr);
		
		//Unschedule event
		
		if ( $ctr >= $limit) {
			wp_unschedule_event( wp_next_scheduled("hook_every_minute",array($id,$subject,$content)), "hook_every_minute", array($id,$subject,$content));
			delete_option( $option_name );
		}
	}
	
	function set_html_content_type() {
		return 'text/html';
	}
	
	function add_every_minute() {
		$schedules['everyminute'] = array(
			'interval' => 60,
			'display' => __('Every Minute')
		);
		return $schedules;
	}

} 
 
function wp_publish_notifications_instantiate() {
	$sandbox = new WP_Publish_Notifications_Controller();
}
add_action( 'plugins_loaded', 'wp_publish_notifications_instantiate', 15 );




