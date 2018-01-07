<?php
/**
 * Display the export button in the donation filters box.
 *
 * @author  Kathy Darling
 * @package Charitable/Admin View/Recurring Donations Page
 * @since   1.0.0
 */


$modal_class = apply_filters( 'charitable_modal_window_class', 'charitable-modal' );
$post_status = isset( $_GET['post_status'] ) ? $_GET['post_status'] : 'all';
$report_type  = isset( $_GET['report_type'] ) ? $_GET['report_type'] : 'recurring_donations';
$report_types = apply_filters( 'charitable_recurring_donation_export_report_types', array(
	'recurring_donations' => __( 'Recurring Donations', 'charitable-recurring' ),
) );

?>
<div id="charitable-donations-export-modal" style="display: none;" class="charitable-donations-modal <?php echo esc_attr( $modal_class ) ?>">
	<a class="modal-close"></a>
	<h3><?php _e( 'Export Recurring Donations', 'charitable-recurring' ) ?></h3>
	<form class="charitable-donations-modal-form" method="get" action="<?php echo admin_url( 'admin.php' ) ?>">
		<?php wp_nonce_field( 'charitable_export_recurring_donations', '_charitable_recurring_export_nonce' ) ?>
		<input type="hidden" name="charitable_action" value="export_recurring_donations" />
		<input type="hidden" name="page" value="charitable-donations-table" />		
		<label for="charitable-donations-export-status"><?php _e( 'Filter by Status', 'charitable-recurring' ) ?></label>
		<select id="charitable-donations-export-status" name="post_status">
			<option value="all" <?php selected( $post_status, 'all' ) ?>><?php _e( 'All', 'charitable-recurring' ) ?></option>
			<?php foreach ( charitable_recurring_get_valid_donation_statuses() as $key => $status ) : ?>
				<option value="<?php echo esc_attr( $key ) ?>" <?php selected( $post_status, $key ) ?>><?php echo $status ?></option>
			<?php endforeach ?>
		</select>
		<label for="charitable-donations-export-campaign"><?php _e( 'Filter by Campaign', 'charitable-recurring' ) ?></label>
		<select id="charitable-donations-export-campaign" name="campaign_id">
			<option value="all"><?php _e( 'All Campaigns', 'charitable-recurring' ) ?></option>
			<?php foreach ( get_posts( array( 'post_type' => 'campaign', 'post_status' => 'any', 'posts_per_page' => -1 ) ) as $campaign ) : ?>
				<option value="<?php echo $campaign->ID ?>"><?php echo get_the_title( $campaign->ID ) ?></option>
			<?php endforeach ?>
		</select>
		<?php if ( count( $report_types ) > 1 ) : ?>
			<label for="charitable-donations-export-report-type"><?php _e( 'Type of Report', 'charitable-recurring' ) ?></label>
			<select id="charitable-donations-export-report-type" name="report_type">
			<?php foreach ( $report_types as $key => $report_label ) : ?>
				<option value="<?php echo esc_attr( $key ) ?>"><?php echo $report_label ?></option>
			<?php endforeach; ?>
			</select>
		<?php else : ?>
			<input type="hidden" name="report_type" value="<?php echo esc_attr( key( $report_types ) ) ?>" />
		<?php endif ?>
		<?php do_action( 'charitable_recurring_export_recurring_donations_form' ) ?>
		<button name="charitable-export-donations" class="button button-primary"><?php _e( 'Export', 'charitable-recurring' ) ?></button>
	</form>
</div>
