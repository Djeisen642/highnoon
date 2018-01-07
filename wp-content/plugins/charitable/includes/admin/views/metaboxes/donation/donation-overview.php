<?php
/**
 * Renders the donation details meta box for the Donation post type.
 *
 * @author  Studio 164a
 * @since   1.0.0
 * @version 1.5.0
 * @package Charitable/Admin Views/Metaboxes
 */

global $post;

$donation = charitable_get_donation( $post->ID );
$donor    = $donation->get_donor();
$amount   = $donation->get_total_donation_amount();
$date     = 'manual' == $donation->get_gateway() && '00:00:00' == mysql2date( 'H:i:s', $donation->post_date_gmt ) ? $donation->get_date() : $donation->get_date() . ' - ' . $donation->get_time();

?>
<div id="charitable-donation-overview-metabox" class="charitable-metabox">
	<div class="donation-banner-wrapper">
		<div class="donation-banner">
			<h3 class="donation-number"><?php printf( '%s #%d', __( 'Donation', 'charitable' ), $donation->get_number() ) ?></h3>
			<span class="donation-date"><?php echo $date ?></span>
			<a href="<?php echo esc_url( add_query_arg( 'show_form', true ) ) ?>" class="donation-edit-link"><?php _e( 'Edit Donation', 'charitable' ) ?></a>
		</div>
	</div>
	<div id="donor" class="charitable-media-block">
		<div class="donor-avatar charitable-media-image">
			<?php echo $donor->get_avatar( 80 ) ?>
		</div>
		<div class="donor-facts charitable-media-body">
			<h3 class="donor-name"><?php echo $donor->get_name() ?></h3>
			<span class="donor-email"><?php echo $donor->get_email() ?></span>
			<?php
			/**
			 * Display additional details about the donor.
			 *
			 * @since 1.0.0
	 		 *
			 * @param Charitable_Donor    $donor    The donor object.
			 * @param Charitable_Donation $donation The donation object.
			 */
			do_action( 'charitable_donation_details_donor_facts', $donor, $donation );
			?>
		</div>
	</div>
	<div id="donation-summary">		
		<span class="donation-status"><?php printf( '%s: <span class="status">%s</span>', __( 'Status', 'charitable' ), $donation->get_status_label() ) ?></span>
	</div>
	<?php
		/**
		 * Add additional output before the table of donations.
		 *
		 * @since 1.5.0
		 *
		 * @param Charitable_Donor    $donor    The donor object.
		 * @param Charitable_Donation $donation The donation object.
		 */
		do_action( 'charitable_donation_details_before_campaign_donations', $donor, $donation );
	?>
	<table id="overview">
		<thead>
			<tr>
				<th class="col-campaign-name"><?php _e( 'Campaign', 'charitable' ) ?></th>
				<th class="col-campaign-donation-amount"><?php _e( 'Total', 'charitable' ) ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ( $donation->get_campaign_donations() as $campaign_donation ) : ?>
			<tr>
				<td class="campaign-name">
					<?php
						/**
						 * Filter the campaign name.
						 *
						 * @since 1.5.0
						 *
						 * @param string              $campaign_name     The name of the campaign.
						 * @param object              $campaign_donation The campaign donation object.
						 * @param Charitable_Donation $donation          The Donation object.
						 */
						echo apply_filters( 'charitable_donation_details_table_campaign_donation_campaign', $campaign_donation->campaign_name, $campaign_donation, $donation )
					?>
				</td>
				<td class="campaign-donation-amount">
					<?php
						/**
						 * Filter the campaign donation amount.
						 *
						 * @since 1.5.0
						 *
						 * @param string              $amount            The default amount to display.
						 * @param object              $campaign_donation The campaign donation object.
						 * @param Charitable_Donation $donation          The Donation object.
						 */
						echo apply_filters( 'charitable_donation_details_table_campaign_donation_amount', charitable_format_money( $campaign_donation->amount ), $campaign_donation, $donation )
					?>					
				</td>
			</tr>
		<?php endforeach ?>
		</tbody>
		<tfoot>
			<?php
				/**
				 * Add a row before the total. Any output should be wrapped in <tr></tr> with two cells.
				 *
				 * @since 1.5.0
				 *
				 * @param Charitable_Donation $donation The Donation object.
				 */
				do_action( 'charitable_donation_details_table_before_total', $donation );
			?>
			<tr>
				<th><?php _e( 'Total', 'charitable' ) ?></th>
				<td><?php
					/**
					 * Filter the total donation amount.
					 *
					 * @since 1.5.0
					 *
					 * @param string              $total    The default amount to display.
					 * @param float               $amount   The total, unformatted.
					 * @param Charitable_Donation $donation The Donation object.
					 */
					echo apply_filters( 'charitable_donation_details_table_total', charitable_format_money( $amount ), $amount, $donation )
				?></td>
			</tr>
			<?php
				/**
				 * Add a row before the payment method. Any output should be wrapped in <tr></tr> with two cells.
				 *
				 * @since 1.5.0
				 *
				 * @param Charitable_Donation $donation The Donation object.
				 */
				do_action( 'charitable_donation_details_table_before_payment_method', $donation );
			?>
			<tr>
				<th><?php _e( 'Payment Method', 'charitable' ) ?></th>
				<td><?php echo $donation->get_gateway_label() ?></td>
			</tr>
			<?php
				/**
				 * Add a row before the status. Any output should be wrapped in <tr></tr> with two cells.
				 *
				 * @since 1.5.0
				 *
				 * @param Charitable_Donation $donation The Donation object.
				 */
				do_action( 'charitable_donation_details_table_before_status', $donation );
			?>
			<tr>
				<th><?php _e( 'Change Status', 'charitable' ) ?></th>
				<td>
					<select id="change-donation-status" name="post_status">
					<?php foreach ( charitable_get_valid_donation_statuses() as $status => $label ) : ?>
						<option value="<?php echo $status ?>" <?php selected( $status, $donation->get_status() ) ?>><?php echo $label ?></option>
					<?php endforeach ?>
					</select>
				</td>
			</tr>
			<?php
				/**
				 * Add a row after the status. Any output should be wrapped in <tr></tr> with two cells.
				 *
				 * @since 1.5.0
				 *
				 * @param Charitable_Donation $donation The Donation object.
				 */
				do_action( 'charitable_donation_details_table_after_status', $donation );
			?>
			<tr class="hide-if-js">
				<td colspan="2">
					<input type="submit" name="update" class="button button-primary" value="<?php _e( 'Update Status', 'charitable' ) ?>" />
				</td>
			</tr>
		</tfoot>
	</table>
</div>
