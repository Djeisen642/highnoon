<?php
	$scroll_trigger = get_option('stack_scroll_trigger', '200px');
	$mobile_scroll_trigger = get_option('stack_mobile_scroll_trigger', '200px');
	$scroll = ( 'yes' == get_option('stack_scroll_header', 'yes') ) ? 'data-scroll-class="'. $scroll_trigger .':pos-fixed"' : false;
	$mobile_scroll = ( 'yes' == get_option('stack_scroll_mobile_header', 'yes') ) ? 'data-scroll-class="'. $mobile_scroll_trigger .':pos-fixed"' : false;
	$mobile_scroll_class = ( 'yes' == get_option('stack_scroll_mobile_header', 'yes') ) ? 'bar--mobile-sticky' : false;
	
	$background = get_option('stack_header_background', 'original--bg');
?>

<div class="nav-container">

	<?php get_template_part('inc/content-header', 'top-bar'); ?>
	
	<div class="bar bar--sm visible-sm visible-xs <?php echo esc_attr($background); ?> <?php echo esc_attr($mobile_scroll_class); ?>" <?php echo wp_kses_post($mobile_scroll); ?>>
		<div class="container">
			<div class="row">
				<div class="col-xs-9 col-sm-10">
					<?php get_template_part('inc/content-header', 'logo'); ?>	
				</div>
				<div class="col-xs-3 col-sm-2 text-right mobile-header">
					<?php 
						if( class_exists('woocommerce') && 'yes' == get_option('stack_header_mobile_cart', 'yes') ){
							get_template_part('inc/content-header', 'woocommerce');
						}
					?>
					<a href="#" class="hamburger-toggle" data-toggle-class="#menu6;hidden-xs hidden-sm">
						<i class="icon--sm stack-interface stack-menu"></i>
					</a>
				</div>
			</div><!--end of row-->
		</div><!--end of container-->
	</div><!--end bar-->
	
	<nav id="menu6" class="bar bar-stacked bar--lg hidden-xs <?php echo esc_attr($background); ?>" <?php echo wp_kses_post($scroll); ?>>
		<div class="container">
			<div class="row">
				<div class="col-md-12 hidden-sm hidden-xs">
					<div class="bar__module text-center">
						<?php get_template_part('inc/content-header', 'logo'); ?>		
					</div><!--end module-->
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 text-center text-left-xs">
					<?php get_template_part('inc/content-header', 'nav'); ?>
				</div>
			</div><!--end of row-->
		</div><!--end of container-->
	</nav><!--end bar-->

</div>