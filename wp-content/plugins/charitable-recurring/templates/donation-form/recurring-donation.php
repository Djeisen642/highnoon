<?php
/**
 * The template used to display the recurring donation amount inputs.
 *
 * @author  Kathy Darling
 * @since   1.0.0
 * @version 1.0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $view_args[ 'form' ] ) ) {
	return;
}

/**
 * @var Charitable_Donation_Form
 */
$form = $view_args[ 'form' ];
$campaign = $form->get_campaign();

$default_tab = charitable_recurring_campaign_get_default_tab( $campaign );

/**
 * @hook    charitable_donation_form_before_recurring_donation_option
 */
do_action( 'charitable_donation_form_before_recurring_donation_option', $view_args[ 'form' ] ); ?>

<?php if ( charitable_recurring_campaign_supports_one_time_donations( $campaign ) ) : ?>
    <ul class="recurring-donation recurring-donation-options">    
    	<li class="one-time-donation recurring-donation-option <?php if( 'one-time' == $default_tab ) { echo 'selected'; } ?>">
    		<label for="recurring-form-<?php echo $view_args[ 'form' ]->get_form_identifier();?>-once">
    			<input id="recurring-form-<?php echo $view_args[ 'form' ]->get_form_identifier();?>-once" type="radio" data-form_id="<?php echo $view_args[ 'form' ]->get_form_identifier(); ?>" name="recurring_donation" value="once" <?php checked( 'one-time', $default_tab );?>>
    			<?php _e( 'One Time', 'charitable-recurring' );?>
            </label>
    	</li>    
        <li class="monthly-donation recurring-donation-option <?php echo 'recurring' == $default_tab ? 'selected' : '';?>">
            <label for="recurring-form-<?php echo $view_args[ 'form' ]->get_form_identifier();?>-recurring">
            	<input id="recurring-form-<?php echo $view_args[ 'form' ]->get_form_identifier();?>-recurring" type="radio" data-form_id="<?php echo $view_args[ 'form' ]->get_form_identifier(); ?>"  name="recurring_donation" value="month" <?php checked( 'recurring', $default_tab );?>>
            	<?php _e( 'Monthly', 'charitable-recurring' );?>
            </label>
        </li>
    </ul>
<?php else : ?>
    <input id="recurring-form-<?php echo $view_args[ 'form' ]->get_form_identifier();?>-recurring" type="hidden" data-form_id="<?php echo $view_args[ 'form' ]->get_form_identifier(); ?>"  name="recurring_donation" value="month" />
<?php endif ?>

<?php 
/**
 * @hook    charitable_donation_form_after_recurring_donation_option
 */
do_action( 'charitable_donation_form_after_recurring_donation_option', $view_args[ 'form' ]); ?>