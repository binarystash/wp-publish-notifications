<?php

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

WP_Email_Helpers::setup_tables();

class WP_Email_Test extends WP_UnitTestCase {

	function test_queue() {
		$id = WP_Email::queue('email_address','subject','content');
		$this->assertGreaterThan(0,$id);
	}
}

class WP_Email_Queue_Test extends WP_UnitTestCase {

	function test_start() {
		$queue = new WP_Email_Queue();
		$queue->start();
		$this->assertEquals(true,wp_next_scheduled( 'process_queue_hook' ) );		
	}

	function test_stop() {
		$queue = new WP_Email_Queue();
		$queue->start();
		$queue->stop();
		$this->assertEquals(false,wp_next_scheduled( 'process_queue_hook' ) );
	}

	function test_add_schedules() {
		$queue = new WP_Email_Queue();
		$schedules = $queue->add_schedules();
		$this->assertArrayHasKey('everyminute',$schedules);
	}
}