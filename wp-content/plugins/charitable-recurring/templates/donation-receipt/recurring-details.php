<?php 
/**
 * Displays the Recurring donation details.
 *
 * Override this template by copying it to yourtheme/charitable/donation-receipt/details.php
 *
 * @author  Kathy Darling
 * @package Charitable_Recurring/Templates/Donation Receipt
 * @since   1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var     Charitable_Donation
 */
$recurring = $view_args[ 'recurring' ];

?>

<h3 class="charitable-header"><?php _e( 'Your Recurring Donation', 'charitable-recurring' ) ?></h3>
<table class="recurring-donation-details donation-details charitable-table">
    <thead>
        <tr>
            <th><?php _e( 'Status', 'charitable-recurring' ) ?></th>
            <th><?php _e( 'Start Date', 'charitable-recurring' ) ?></th>
            <th><?php _e( 'Next Donation Date', 'charitable-recurring' ) ?></th>
            <th><?php _e( 'Amount', 'charitable-recurring' ) ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ( $recurring->get_campaign_donations() as $campaign_donation ) : ?>

        <tr>
           <td class="recurring-status"><?php                 
                echo $recurring->get_status(true);
                ?>
            </td>
            <td class="start-date">
                <?php echo $recurring->get_date(); ?>
            </td>
            <td class="next-date">
                <?php echo date_i18n( get_option( 'date_format' ), strtotime( "+1 " . $recurring->get_donation_period(), get_the_date( 'U', $recurring->ID ) ) ); ?>
            </td>
            <td class="donation-amount"><?php echo $recurring->get_recurring_donation_amount( true ); ?></td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>