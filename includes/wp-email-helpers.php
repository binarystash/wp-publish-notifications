<?php
class WP_Email_Helpers {

	static function setup_tables() {

		global $wpdb;

		$table_name = $wpdb->prefix . "email_queue";

		$sql = "CREATE TABLE $table_name (
		  id int NOT NULL AUTO_INCREMENT,
		  recipient varchar(300) NOT NULL,
		  subject text NOT NULL,
		  content longtext,
		  added timestamp DEFAULT CURRENT_TIMESTAMP,
		  sent bool DEFAULT 0,
		  UNIQUE KEY id (id)
		);";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_option( "wp_email_db_version", "1.0" );
	}

	static function do_setup() {
		WP_Email_Helpers::setup_tables();
	}

}