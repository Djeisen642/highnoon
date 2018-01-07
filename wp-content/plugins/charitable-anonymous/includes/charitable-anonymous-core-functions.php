<?php 

/**
 * Charitable Anonymous Core Functions. 
 *
 * General core functions.
 *
 * @author      Studio164a
 * @category    Core
 * @package     Charitable Anonymous
 * @subpackage  Functions
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/**
 * This returns the original Charitable_Anonymous object. 
 *
 * Use this whenever you want to get an instance of the class. There is no
 * reason to instantiate a new object, though you can do so if you're stubborn :)
 *
 * @return  Charitable_Anonymous
 * @since   1.0.0
 */
function charitable_anonymous() {
    return Charitable_Anonymous::get_instance();
}

/**
 * Displays a template. 
 *
 * @param   string|array    $template_name      A single template name or an ordered array of template
 * @param   arary           $args               Optional array of arguments to pass to the view.
 * @return  Charitable_Anonymous_Template
 * @since   1.0.0
 */
function charitable_anonymous_template( $template_name, array $args = array() ) {
    if ( empty( $args ) ) {
        $template = new Charitable_Anonymous_Template( $template_name ); 
    }
    else {
        $template = new Charitable_Anonymous_Template( $template_name, false ); 
        $template->set_view_args( $args );
        $template->render();
    }

    return $template;
}

/**
 * description
 *
 * @since  1.2.0
 *
 * @param  Charitable_Donation $donation The donation.
 * @return string
 */
function charitable_anonymous_was_checked( Charitable_Donation $donation ) {
    return get_post_meta( $donation->ID, 'anonymous_donation', true ) ? __( 'Yes' ) : __( 'No' );
}