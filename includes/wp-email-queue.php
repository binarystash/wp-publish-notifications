<?php
require_once( WP_PUBLISH_NOTIFICATIONS_DIR . 'includes/wp-email.php');

class WP_Email_Queue {
	
	private $_db;
	private $_table;
	private $_hook;

	function __construct() {
		global $wpdb;
		$this->_db = $wpdb;
		$this->_table =  $this->_db->prefix . "email_queue";
		$this->_hook = 'process_queue_hook';

		//Set up new schedules
		add_filter( 'cron_schedules', array( &$this, 'add_schedules') ); 

		//Set up cronned activity
		add_action( $this->_hook, array( &$this, 'process'), 10, 3 );
	}

	function start() {
		if ( !wp_next_scheduled( $this->_hook ) ) {
			wp_schedule_event( time(), 'everyminute', $this->_hook );
		}
	}

	function stop() {
		wp_unschedule_event( wp_next_scheduled( $this->_hook ), $this->_hook );
	}

	function process() {
		$email_ids = $this->_db->get_col( "
			SELECT id FROM $this->_table
			WHERE sent = 0
			ORDER BY added ASC
			LIMIT 10
		" );

		foreach ( $email_ids as $id ) {
			$email = new WP_Email($id);
			$email->send();
		}
	}

	function add_schedules() {
		$schedules['everyminute'] = array(
			'interval' => 60,
			'display' => __( 'Every Minute' )
		);
		return $schedules;
	}

}