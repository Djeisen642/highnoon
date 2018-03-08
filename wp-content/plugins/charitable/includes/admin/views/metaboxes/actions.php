<?php 
/**
 * Renders the donation details meta box for the Donation post type.
 *
 * @author Studio 164a
 * @since  1.5.0
 */

global $post;

$helper  = array_key_exists( 'actions', $view_args ) ? $view_args['actions'] : charitable_get_donation_actions();
$actions = $helper->get_available_actions( $post->ID );
$groups  = $helper->get_available_groups( $post->ID );
$type    = esc_attr( $helper->get_type() );

if ( empty( $actions ) ) {
	return;
}
?>
<div id="charitable-<?php echo $type ?>-actions-metabox-wrapper" class="charitable-metabox charitable-actions-form-wrapper">
	<div id="charitable-<?php echo $type ?>-actions-form" class="charitable-actions-form">
		<?php do_action( 'charitable_' . $type . '_actions_start', $post->ID ); ?>
		<select id="charitable_<?php echo $type ?>_actions" name="charitable_<?php echo $type ?>_action" class="charitable-action-select">
			<option value=""><?php _e( 'Select an action', 'charitable' ) ?></option>
			<?php foreach ( $groups as $label => $group_actions ) : ?>
				<?php if ( ! empty( $label ) && 'default' != $label ) : ?>
					<optgroup label="<?php echo esc_attr( $label ) ?>">
				<?php endif ?>
					<?php foreach ( $group_actions as $action ) : ?>
						<?php if ( array_key_exists( $action, $actions ) ) : ?>
							<option value="<?php echo esc_attr( $action ) ?>" data-button-text="<?php echo esc_attr( $actions[ $action ]['button_text'] ) ?>"><?php echo esc_html( $actions[ $action ]['label'] ) ?></option>
						<?php endif ?>
					<?php endforeach ?>
				<?php if ( ! empty( $label ) ) : ?>
					</optgroup>
				<?php endif ?>
			<?php endforeach ?>			
		</select>
		<?php foreach ( $groups as $group_actions ) : ?>
			<?php foreach ( $group_actions as $action ) : ?>
				<?php if ( array_key_exists( $action, $actions ) && $actions[ $action ]['fields'] ) : ?>
					<div class="charitable-action-fields" style="display: none;" data-type="<?php echo esc_attr( $action ) ?>">
						<?php echo $actions[ $action ]['fields'] ?>
					</div>
				<?php endif ?>
			<?php endforeach ?>
		<?php endforeach ?>
		<?php do_action( 'charitable_' . $type . '_actions_end', $post->ID ); ?>
	</div><!-- #charitable-<?php echo $type ?>-actions-form -->
	<div id="charitable-<?php echo $type ?>-actions-submit" class="charitable-actions-submit">
		<button type="submit" class="button-primary" title="<?php esc_attr_e( 'Submit', 'charitable' ) ?>"><?php _e( 'Submit', 'charitable' ) ?></button>
		<div class="clear"></div>
	</div><!-- #charitble-<?php echo $type ?>-actions-submit -->
</div><!-- #charitable-<?php echo $type ?>-actions-metabox-wrapper -->
