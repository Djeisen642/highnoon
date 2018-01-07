<?php
/**
 * @author  TommusRhodus
 * @version 9.9.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) : ?>
	
<section class="space--sm">
	<div class="tabs-container horizontal" data-content-align="left">
		<ul class="tabs">
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<li>
					<div class="tab__title">
						<span class="h5"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></span>
					</div>
					<div class="tab__content"><?php call_user_func( $tab['callback'], $key, $tab ); ?></div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<?php endif; ?>
