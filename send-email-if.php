<?php

/**
 * Plugin Name: Send Email If
 * Description: Send Emails on conditions
 * Plugin URI: https://www.tukutoi.com
 * Author: TukuToi
 * Author URI: https://www.tukutoi.com
 * Version: 1.0.0
 * License: GPL2
 * Text Domain: tkt-send-email-if
 * Domain Path: domain/path
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'wp_footer', 'tkt_sei_get_queried_object' );

function tkt_sei_get_queried_object() {
	
	global $wp;

	$str = add_query_arg( $wp->query_vars, home_url( $wp->request ) );
	$req = substr($str, strpos($str, "?") + 1);
	$params = array();

	foreach (explode('&', $req) as $chunk) {
	    $param = explode("=", $chunk);
	    if ($param) {
	        $params[$param[0]] = isset($param[1]) ? $param[1] : '';
	    }
	}

	$current_data['visitor'] = wp_get_current_user();
	$current_data['query'] = $params; 

	tkt_sei_settings($params, $current_data);

}

function tkt_sei_settings($params, $current_data){

	//This could be a GUI
	$settings = array('sample-page', 'monthnum'=>'05', 'beda_schmid');

	if (!empty(array_intersect($settings, $params))) {
		tkt_sei_send_mail($current_data);
	}

}

function tkt_sei_send_mail($current_data){

	//This could be a GUI
	$add = array('Cc'=>'an@email.com', 'Cc' => 'another@email.com');
	$subject = "Subject Message";

	$the_visitor = $current_data['visitor']->ID !== 0 ? $current_data['visitor']->data->user_login : 'undefined user';
	$message = 'Hello, '. $the_visitor .' just visited your profile.';

 	$headers = array();
	foreach ($add as $key => $email) {
		$headers[] = $key.': '.$email;
	}

	$username = isset($current_data['query']['name']) ? $current_data['query']['name'] : (isset($current_data['query']['pagename']) ? $current_data['query']['pagename'] : '');
	$user_to_send_mail = get_user_by('login', $username);
	

	if (is_object($user_to_send_mail)) 
		wp_mail( $user_to_send_mail->data->user_email, $subject, $message, $headers );
	
}
