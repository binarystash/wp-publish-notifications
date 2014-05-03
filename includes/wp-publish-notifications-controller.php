<?php
class WP_Publish_Notifications_Controller {

	function __construct() {
		
		//Send emails containing the post title and post content every time a post/page is published
		add_action( 'save_post', array( &$this, 'queue_email') );
		
	}
	
	function queue_email($post_id) {

		if ( wp_is_post_revision( $post_id ) )
		return;

		if ( !isset($_POST['publish']) )
		return;

		//Prepare emails
		$post = get_post( $post_id );
		$subject = strip_tags($post->post_title);
		$content = html_entity_decode($post->post_content);
		
		//Get users
		$args = array('order'=>'ASC','orderby'=>'');
		$query = new WP_User_Query($args);
		$users = $query->results;

		if( !empty($users) ) {
			foreach( $users as $user ) {
				WP_Email::queue( $user->user_email, $subject, $content );
			}
		}

	}

} 