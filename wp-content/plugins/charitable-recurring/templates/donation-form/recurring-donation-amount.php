<?php
/**
 * The template used to display the recurring donation amount inputs.
 *
 * @author  Kathy Darling
 * @since   1.0.0
 * @version 1.0.6
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
$donation = charitable_get_session()->get_donation_by_campaign( $campaign->ID );
$suggested_recurring_donations = charitable_recurring_get_suggested_donations( $campaign->ID );
$currency_helper = charitable_get_currency_helper();
// @todo: need to modify to include subscription terms ($10/month)
$donation_amount = $campaign->get_donation_amount_in_session();

if ( empty( $suggested_recurring_donations ) && ! $campaign->get( 'allow_custom_donations' ) ) {
    return;
}

/**
 * @hook    charitable_recurring_donation_form_before_donation_amount
 */
do_action( 'charitable_recurring_donation_form_before_donation_amount', $view_args[ 'form' ] );
?>
<div class="charitable-donation-options charitable-recurring-donation-options">

    <?php
    /**
     * @hook    charitable_recurring_donation_form_before_donation_amounts
     */
    do_action( 'charitable_recurring_donation_form_before_donation_amounts', $view_args[ 'form' ] );
    ?>

<?php if ( count( $suggested_recurring_donations ) > 0 ) : 

    $donation_amount_is_suggestion = false; ?>

    <ul class="recurring-donation-amounts donation-amounts">

        <?php foreach ( $suggested_recurring_donations as $suggestion ) : 

            $checked = checked( $suggestion[ 'amount' ], $donation_amount, false ); 
            
            if ( strlen( $checked ) ) {
                $donation_amount_is_suggestion = true;
            }
            
            ?>

            <li class="donation-amount suggested-donation-amount <?php echo strlen( $checked ) ? 'selected' : ''; ?> ">
                <label for="recurring-form-<?php echo $view_args[ 'form' ]->get_form_identifier() . '-field-' . $suggestion['amount']; ?>">
                    <input id="recurring-form-<?php echo esc_attr( $view_args['form']->get_form_identifier() . '-field-' . $suggestion['amount'] ); ?>" type="radio" name="donation_amount" data-recurring="true" data-value="<?php echo esc_attr( $suggestion[ 'amount' ] ) ?>" value="<?php echo esc_attr( $suggestion[ 'amount' ] ) ?>" <?php echo $checked ?> />
                    <span class="amount"><?php echo charitable_recurring_get_recurring_donation_string( $suggestion ); ?></span>
                    <?php if( isset( $suggestion[ 'description' ] ) ) { ?>
                        <span class="description"><?php echo $suggestion[ 'description' ];?></span>
                    <?php } ?>
                </label>
            </li>

        <?php endforeach;

        if ( $campaign->get( 'allow_custom_donations' ) ) : 

            $has_custom_donation_amount = ! $donation_amount_is_suggestion && $donation_amount; ?>

            <li class="donation-amount custom-donation-amount <?php echo $has_custom_donation_amount ? 'selected' : '';?>">
                <span class="custom-donation-amount-wrapper">                
                    <label for="recurring-form-<?php echo $view_args[ 'form' ]->get_form_identifier();?>-field-custom-recurring-amount">             
                        <input id="recurring-form-<?php echo $view_args[ 'form' ]->get_form_identifier();?>-field-custom-recurring-amount" type="radio" name="donation_amount" value="recurring-custom" <?php checked( $has_custom_donation_amount ) ?> />
                        <span class="description"><?php _e( 'Custom monthly amount', 'charitable-recurring' ) ?></span>
                    </label>
                    <input type="text" class="custom-donation-input" name="custom_recurring_donation_amount" value="<?php if ( $has_custom_donation_amount ) echo $donation_amount ?>" />
                </span>
            </li>

        <?php endif ?>

    </ul>

<?php elseif ( $campaign->get( 'allow_custom_donations' ) ) : ?>

    <div id="custom-recurring-donation-amount-field" class="charitable-form-field charitable-custom-donation-field-alone">
        <input type="text" class="custom-donation-amount" name="custom_recurring_donation_amount" placeholder="<?php esc_attr_e( 'Enter monthly donation amount', 'charitable-recurring' ) ?>" value="<?php if ( $donation_amount ) echo $donation_amount ?>" />
    </div>

<?php endif ?>

    <?php
    /**
      * @hook    charitable_recurring_donation_form_after_donation_amounts
      */
    do_action( 'charitable_recurring_donation_form_after_donation_amounts', $view_args[ 'form' ] );
    ?>

</div><!-- #charitable-donation-options-<?php echo $view_args[ 'form' ]->get_form_identifier() ?> -->

<?php 
/**
 * @hook    charitable_donation_form_after_donation_amount
 */
do_action( 'charitable_donation_form_after_donation_amount', $view_args[ 'form' ]); ?>