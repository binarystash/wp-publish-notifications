<?php
class WP_Email {

	private $_id;
	private $_db;
	private $_recipient;
	private $_table;
	private $_subject;
	private $_content;

	function __construct($id) {
		global $wpdb;
		$this->_id = $id;
		$this->_db = $wpdb;
		$this->_table =  $this->_db->prefix . "email_queue";

		$email = $this->_db->get_row("SELECT * FROM $this->_table WHERE id = $this->_id", ARRAY_A);
		$this->_recipient = $email['recipient'];
		$this->_subject = $email['subject'];
		$this->_content = $email['content'];
	}

	function send() {
		$status = wp_mail($this->_recipient, $this->_subject, $this->_content);
		if ( $status ) {
			$this->_db->update( $this->_table, array( 'sent' => 1), array( 'id' => $this->_id ) );
		}
		return $status;
	}

	static function queue( $recipient, $subject, $content ) {
		global $wpdb;
		$table = $wpdb->prefix . "email_queue";
		$data = array(
			'recipient'=>$recipient,
			'subject'=>$subject,
			'content'=>$content
		);
		$wpdb->insert( $table, $data );
		return $wpdb->insert_id;
	}

}