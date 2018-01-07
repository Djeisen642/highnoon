<div class="notification pos-right pos-top side-menu bg--white" data-notification-link="side-menu" data-animation="from-right">
	
	<?php if( get_option('stack_side_menu_button_1_url') ) : ?>
		<div class="side-menu__module">
			<span class="type--fine-print float-left"><?php echo esc_html(get_option('stack_side_menu_button_1_label')); ?></span>
			<a class="btn type--uppercase float-right btn--primary" href="<?php echo esc_url(get_option('stack_side_menu_button_1_url')); ?>" target="<?php echo esc_attr(get_option('stack_side_menu_button_1_target')); ?>"><span class="btn__text"><?php echo wp_kses_post(get_option('stack_side_menu_button_1_text')); ?></span></a>
		</div><!--end module-->
	<?php endif; ?>
	
	<?php if( get_option('stack_side_menu_button_2_url') ) : ?>
		<div class="side-menu__module">
			<span class="type--fine-print float-left"><?php echo esc_html(get_option('stack_side_menu_button_2_label')); ?></span>
			<a class="btn type--uppercase float-right btn--primary" href="<?php echo esc_url(get_option('stack_side_menu_button_2_url')); ?>" target="<?php echo esc_attr(get_option('stack_side_menu_button_1_target')); ?>"><span class="btn__text"><?php echo wp_kses_post(get_option('stack_side_menu_button_2_text')); ?></span></a>
		</div><!--end module-->
		
		<hr />
	<?php endif; ?>
	
	<?php if( class_exists('woocommerce') ) : ?>
		<?php
			$cart_url = wc_get_cart_url();
			$checkout_url = wc_get_checkout_url();
		?>
		<div class="side-menu__module">
			<span class="type--fine-print float-left"><?php esc_html_e('Want to view your cart?', 'stack'); ?></span>
			<a class="btn type--uppercase float-right" href="<?php echo esc_url($cart_url); ?>"><?php esc_html_e('View Cart', 'stack'); ?></a>
		</div><!--end module-->
		<div class="side-menu__module">
			<span class="type--fine-print float-left"><?php esc_html_e('Or checkout now?', 'stack'); ?></span>
			<a class="btn type--uppercase float-right btn--primary" href="<?php echo esc_url($checkout_url); ?>"><?php esc_html_e('Checkout Now', 'stack'); ?></a>
		</div><!--end module-->
		
		<hr>
	<?php endif; ?>
	
	<div class="side-menu__module">
		<?php  
			if ( has_nav_menu( 'sidebar' ) ){
			    wp_nav_menu( 
			    	array(
				        'theme_location'    => 'sidebar',
				        'depth'             => 0,
				        'container'         => false,
				        'container_class'   => false,
				        'menu_class'        => 'list--loose list--hover'
			        )
			    ); 
			}
		?>
	</div><!--end module-->
	
	<hr>
	
	<div class="side-menu__module">
		<?php get_template_part('inc/content-header', 'social'); ?>
	</div>
	
</div>