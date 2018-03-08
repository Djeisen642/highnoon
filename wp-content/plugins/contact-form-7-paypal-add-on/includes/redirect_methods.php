<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// returns the form id of the forms that have paypal enabled - used for redirect method 1 and method 2
function cf7pp_forms_enabled() {

	// array that will contain which forms paypal is enabled on
	$enabled = array();
	
	$args = array(
		'posts_per_page'   => 999,
		'post_type'        => 'wpcf7_contact_form',
		'post_status'      => 'publish',
	);
	$posts_array = get_posts($args);
	
	
	// loop through them and find out which ones have paypal enabled
	foreach($posts_array as $post) {
		
		$post_id = $post->ID;
		
		// paypal
		$enable = get_post_meta( $post_id, "_cf7pp_enable", true);
		
		if ($enable == "1") {
			$enabled[] = $post_id.'|paypal';
		}
		
		// stripe
		$enable_stripe = get_post_meta( $post_id, "_cf7pp_enable_stripe", true);
		
		if ($enable_stripe == "1") {
			$enabled[] = $post_id.'|stripe';
		}
		
	}

	return json_encode($enabled);

}


// hook into contact form 7 - after send - for redirect method 1 and 2
add_action('template_redirect','cf7pp_redirect_method');
function cf7pp_redirect_method() {

	if (isset($_GET['cf7pp_redirect'])) {

		// get the id from the cf7pp_before_send_mail function theme redirect
		$post_id = $_GET['cf7pp_redirect'];

		cf7pp_redirect($post_id);
		exit;

	}
}


// for redirect method 2 - this must be loaded for redirect method 2 regardless of if the form has paypal enabled
$options = get_option('cf7pp_options');

if ($options['redirect'] == "2"  || $options['redirect'] == '') {

	if (!defined('WPCF7_LOAD_JS')) {
		define('WPCF7_LOAD_JS', false);
	}

}


// hook into contact form 7 - before send for redirect method 1 and method 2
add_action('wpcf7_before_send_mail', 'cf7pp_before_send_mail');
function cf7pp_before_send_mail() {

	$wpcf7 = WPCF7_ContactForm::get_current();

	// need to save submission for later and the variables get lost in the cf7 javascript redirect
	$submission_orig = WPCF7_Submission::get_instance();

	if ($submission_orig) {
		// get form post id
		$posted_data = $submission_orig->get_posted_data();
		
		$options = 			get_option('cf7pp_options');
		
		$post_id = 			$posted_data['_wpcf7'];
		$gateway = 			strtolower(get_post_meta($post_id, "_cf7pp_gateway", true));
		$amount_total = 	get_post_meta($post_id, "_cf7pp_price", true);
		
		$enable = 			get_post_meta( $post_id, "_cf7pp_enable", true);
		$enable_stripe = 	get_post_meta( $post_id, "_cf7pp_enable_stripe", true);
		
		
		
		$gateway_orig = $gateway;
		
		if ($enable == '1') {
			$gateway = 'paypal';
		}
		
		if ($enable_stripe == '1') {
			$gateway = 'stripe';			
		}
		
		if ($enable == '1' && $enable_stripe == '1') {
			$gateway = $posted_data[$gateway_orig];
		}		
		
		
		
		if (!isset($options['default_symbol'])) {
			$options['default_symbol'] 	= '$';
		}
		
		
		
		if (isset($options['mode_stripe'])) {
			if ($options['mode_stripe'] == "1") {
				$tags['stripe_state'] = "test";
			} else {
				$tags['stripe_state'] = "live";
			}
		} else {
			$tags['stripe_state'] = "live";
		}
		
		
		
		$_SESSION['gateway'] = 			$gateway;
		$_SESSION['amount_total'] = 	$amount_total;
		$_SESSION['default_symbol'] = 	$options['default_symbol'];
		$_SESSION['stripe_state'] = 	$tags['stripe_state'];
	}
}


// after submit post for js - used for method 1 and 2 for paypal and stripe
add_action('wp_ajax_cf7pp_get_form_post', 'cf7pp_get_form_post_callback');
add_action('wp_ajax_nopriv_cf7pp_get_form_post', 'cf7pp_get_form_post_callback');
function cf7pp_get_form_post_callback() {

	$response = array(
		'gateway'         		=> $_SESSION['gateway'],
		'amount_total'         	=> $_SESSION['amount_total'],
		'default_symbol'        => $_SESSION['default_symbol'],
		
	);

	echo json_encode($response);

	wp_die();
}


// return stripe payment form
add_action('wp_ajax_cf7pp_get_form_stripe', 'cf7pp_get_form_stripe_callback');
add_action('wp_ajax_nopriv_cf7pp_get_form_stripe', 'cf7pp_get_form_stripe_callback');
function cf7pp_get_form_stripe_callback() {

	// generate nonce
	$salt = wp_salt();
	$nonce = wp_create_nonce('cf7pp_'.$salt);

	$options = get_option('cf7pp_options');
	
	
	$result = '';
	
	if ( (empty($options['pub_key_test']) && $options['mode_stripe'] == '1') || (empty($options['pub_key_live']) && $options['mode_stripe'] == '2') ) {
		$result .= 'Stripe Error. Admin: Please enter your Stripe Keys on the settings page.';
		
		$response = array(
			'html'         		=> $result,
		);
		
		echo json_encode($response);
		wp_die();
	}
	
	// show stripe test mode div
	if ($_SESSION['stripe_state'] == 'test') {
		$result .= "<a href='https://stripe.com/docs/testing#cards' target='_blank' class='cf7pp-test-mode'>test mode</a>";
	}

	$result .= "<div class='cf7pp_stripe'>";
		$result .= "<form method='post' id='cf7pp-payment-form'>";
			$result .= "<div class='cf7pp_body'>";
				$result .= "<div class='cf7pp_row'>";
					$result .= "<div class='cf7pp_details_input'>";
						$result .= "<label for='cf7pp_stripe_credit_card_number'>"; $result .= __($options['card_number'],'cf7pp_stripe'); $result .= "</label>";
						$result .= "<div id='cf7pp_stripe_credit_card_number'></div>";
					$result .= "</div>";

					$result .= "<div class='cf7pp_details_input'>";
						$result .= "<label for='cf7pp_stripe_credit_card_csv'>"; $result .= __($options['card_code'],'cf7pp_stripe'); $result .= "</label>";
						$result .= "<div id='cf7pp_stripe_credit_card_csv'></div>";
					$result .= "</div>";
				$result .= "</div>";
				$result .= "<div class='cf7pp_row'>";
					$result .= "<div class='cf7pp_details_input'>";
						$result .= "<label for='cf7pp_stripe_credit_card_expiration'>"; $result .= __($options['expiration'],'cf7pp_stripe'); $result .= "</label>";
						$result .= "<div id='cf7pp_stripe_credit_card_expiration''></div>";
					$result .= "</div>";

					$result .= "<div class='cf7pp_details_input'>";
						$result .= "<label for='cf7pp_stripe_credit_card_zip'>"; $result .= __($options['zip'],'cf7pp_stripe'); $result .= "</label>";
						$result .= "<div id='cf7pp_stripe_credit_card_zip'></div>";
					$result .= "</div>";
				$result .= "</div>";
				$result .= "<div id='card-errors' role='alert'></div>";
			$result .= "<br />&nbsp;<input id='stripe-submit' value='".$options['pay']." ".$options['default_symbol'].$_SESSION['amount_total']."' type='submit'>";
			$result .= "<div>";
		$result .= "<input type='hidden' id='cf7pp_stripe_nonce' value='$nonce'>";
		$result .= "</form>";
	$result .= "<div>";


	$response = array(
		'html'         		=> $result,
	);

	echo json_encode($response);
	wp_die();
}
