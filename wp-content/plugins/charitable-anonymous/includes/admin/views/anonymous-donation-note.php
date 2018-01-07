<?php 
/**
 * Show a note to say the donor wishes to remain anonymous.
 *
 * @author  Studio 164a
 * @since   1.1.0
 */

$donation = $view_args[ 'donation' ];

if ( ! $donation->anonymous_donation ) :
    return;
endif;

?>
<p><?php _e( 'Donation made anonymously.', 'charitable-anonymous' ) ?></p>