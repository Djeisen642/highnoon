<?php 
/**
 * Renders the suggested recurring donation amounts field inside the donation options metabox for the Campaign post type.
 *
 * @author 	Kathy Darling
 * @since 	1.0.0
 */

global $post;

if ( ! isset( $view_args[ 'fields' ] ) || empty( $view_args[ 'fields' ] ) ) {
	return;
}

$fields		 = $view_args['fields'];
$title 		 = isset( $view_args['label'] ) 		? $view_args['label'] 	: '';
$tooltip 	 = isset( $view_args['tooltip'] )	? '<span class="tooltip"> '. $view_args['tooltip'] . '</span>'	: '';
$description = isset( $view_args['description'] )? '<span class="charitable-helper">' . $view_args['description'] . '</span>' 	: '';
$suggested   = get_post_meta( $post->ID, '_campaign_suggested_recurring_donations', true );
$mode 		 = get_post_meta( $post->ID, '_campaign_recurring_donations', true );
$maybe_hide  = $mode === 'advanced' ? '' : 'style="display: none;"';

if ( ! $suggested ) {
	$suggested = array();
}

/* Add a default empty row to the end. we will use this as our clone model. */
$default = array_fill_keys( array_keys( $fields ), '' );

array_push( $suggested, $default );

?>
<div id="charitable-campaign-suggested-recurring-donations-metabox-wrap" class="charitable-metabox-wrap" <?php echo $maybe_hide ?>>
	<table class="widefat charitable-campaign-suggested-donations">
		<thead>
			<tr class="table-header">
				<th colspan="<?php echo count( $fields ) + 2 ?>"><label for="campaign_suggested_donations"><?php echo $title ?></label></th>
			</tr>
			<tr>
				<?php $i = 1; ?>
				<?php foreach ( $fields as $key => $field ) : ?>
					<th <?php echo $i == 1 ? 'colspan="2"' : ''; ?> class="<?php echo $key ?>-col"><?php echo $field[ 'column_header' ] ?></th>
				<?php $i++; endforeach ?>		
				<th class="reorder-col"></th>
					
			</tr>
		</thead>		
		<tbody>
			<tr class="no-suggested-amounts <?php echo ( count( $suggested ) > 1 )? "hidden" : "";?>">
					<td colspan="<?php echo count( $fields ) ?>"><?php _e( 'No suggested amounts have been created yet.', 'charitable-recurring' ) ?></td>
			</tr>
		<?php 
			foreach ( $suggested as $i => $donation ) : 
				?>
					<tr data-index="<?php echo $i ?>" class="<?php echo ( $donation === end( $suggested ) ) ? 'to-copy hidden' : 'default'; ?>">

						<td class="reorder-col"><span class="icon-donations-grab handle"></span></td>
						
						<?php foreach ( $fields as $key => $field ) :

							if ( is_array( $donation ) && isset( $donation[ $key ] ) ) {
								$value = $donation[ $key ];
							}
							elseif ( 'amount' == $key ) {
								$value = $donation;
							}
							else {
								$value = '';
							}

							?>
							<td class="<?php echo $key ?>-col"><input 
								type="text" 
								class="campaign_suggested_donations" 
								name="_campaign_suggested_recurring_donations[<?php echo $i ?>][<?php echo $key ?>]" 
								value="<?php echo esc_attr( $value ) ?>" 
								placeholder="<?php echo esc_attr( $field[ 'placeholder' ] ) ?>" />
							</td>
						<?php endforeach ?>	

						<td class="remove-col"><span class="dashicons-before dashicons-dismiss charitable-delete-row"></span></td>

					</tr>
				<?php 
				endforeach;

		?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="<?php echo count( $fields ) + 2 ?>"><a class="button" href="#" data-charitable-add-row="suggested-amount"><?php _e( '+ Add a Suggested Amount', 'charitable-recurring' ) ?></a></td>
			</tr>
		</tfoot>
	</table>	
</div>