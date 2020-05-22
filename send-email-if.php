<?php
/**
 * Plugin Name: Send Email If
 * Description: Send Emails to X when Y happens. This version is specifically adapted for https://juniorcollegiatetour.com: Emails will be sent to the address stored in the "parents email" Field on subpages, only when a logged in Coach is viewing the subpage (player profile). 
 * Plugin URI: https://www.tukutoi.com/program/send-email-if/
 * Author: TukuToi
 * Author URI: https://www.tukutoi.com/author/Beda_Schmid/
 * Version: 1.5.0
 * License: GPL2
 * Text Domain: tkt-send-email-if
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$role = '';

/**
 * Register a Backend Metabox for adding email, on pages only
 */
function tkt_sei_register_meta_boxes() {
    add_meta_box( 'tkt_sei_email_address', __( 'Email Address', 'tkt-send-email-if' ), 'tkt_sei_display_callback', 'post' );
}

/**
 * Populate the metabox
 */
function tkt_sei_display_callback( $post ) {
    include plugin_dir_path( __FILE__ ) . './metabox.php';
}

/**
 * Save content of meta box to database
 */
function tkt_sei_save_meta_box( $post_id ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
        $post_id = $parent_id;
    }

    $fields = [
        'tkt_sei_email_address',
    ];

    foreach ( $fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
    }
}

/**
 * Front end, get current page (player), and current user (coach)
 */
function tkt_sei_get_queried_object() {
	//WP Object
	global $post;

	if ( is_single() && 'post' == get_post_type() ) {
		//we are on a single post 
		if ( get_option('permalink_structure') ) {
			//we use permalinks
			$current_user = wp_get_current_user();
			if ( in_array( $role, (array) $current_user->roles ) ) {
				//it's a Coach!
				$parent_email = get_post_meta($post->ID, 'tkt_sei_email_address', true);
				if ($parent_email) {
					//an email is stored
					$content = 'Hello, '.$current_user->first_name.' '.$current_user->last_name.' has just visited '.$post->post_title;
					tkt_sei_send_mail($parent_email, $content);
				}	
			}
		}
	}
}

/**
 * Send the Email
 * @param  [string] $receiver [email of receiver]
 * @param  [string] $content  [content of email]
 */
function tkt_sei_send_mail($receiver, $content){

	$subject = "Visit detected!";
	
	wp_mail( $receiver, $subject, $content);
}

/**
 * Bootstrap everything
 */
//Add metaboxes
add_action( 'add_meta_boxes', 'tkt_sei_register_meta_boxes' );
//Save metaboxes when saving post
add_action( 'save_post', 'tkt_sei_save_meta_box' );
//Front End Event (Visit and email)
add_action( 'wp_footer', 'tkt_sei_get_queried_object' );
