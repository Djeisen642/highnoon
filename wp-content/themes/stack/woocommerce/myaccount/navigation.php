<?php
/**
 * @author 		TommusRhodus
 * @version     9.9.9
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$current_user = wp_get_current_user();

do_action( 'woocommerce_before_account_navigation' );
?>

<div class="text-block text-center">
    <?php echo get_avatar($current_user->ID, 78, array('class' => 'image--sm"')); ?>
    <span class="h5"><?php echo esc_html($current_user->display_name); ?></span>
    <span><?php echo esc_html(ucfirst($current_user->roles[0])); ?></span>
</div>

<hr />

<div class="text-block">
	<ul class="menu-vertical">
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
