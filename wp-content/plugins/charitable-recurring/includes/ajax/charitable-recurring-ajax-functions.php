<?php
/**
 * Charitable Recurring AJAX Functions.
 *
 * Functions used with ajax hooks.
 *
 * @package     Charitable Recurring/Functions/AJAX
 * @version     1.0.0
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! function_exists( 'charitable_recurring_ajax_load_donation_form_hooks' ) ) :

    /**
     * Loads the files required to hook into the donation form and add recurring info.
     *
     * @return  void
     * @since   1.0.0
     */
    function charitable_recurring_ajax_load_donation_form_hooks() {
        require_once( charitable_recurring()->get_path( 'public' ) . 'class-charitable-recurring-public.php' );
        require_once( charitable_recurring()->get_path( 'includes' ) . 'class-charitable-recurring-template.php' );
        require_once( charitable_recurring()->get_path( 'public' )  . 'charitable-recurring-template-hooks.php' );
        require_once( charitable_recurring()->get_path( 'public' )  . 'charitable-recurring-template-functions.php' );

        Charitable_Recurring_Public::get_instance();
    }

endif;
