<?php 
/**
 * Charitable Recurring AJAX Hooks. 
 *
 * @package     Charitable Recurring/Functions/AJAX
 * @version     1.0.0
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/**
 * Retrieve a campaign's donation form via AJAX.
 *
 * @see     charitable_template_get_donation_form_ajax
 */
add_action( 'wp_ajax_get_donation_form', 'charitable_recurring_ajax_load_donation_form_hooks', 1 );
add_action( 'wp_ajax_nopriv_get_donation_form', 'charitable_recurring_ajax_load_donation_form_hooks', 1 );