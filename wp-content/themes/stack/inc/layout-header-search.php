<?php
	$scroll_trigger = get_option('stack_scroll_trigger', '200px');
	$mobile_scroll_trigger = get_option('stack_mobile_scroll_trigger', '200px');
	$scroll = ( 'yes' == get_option('stack_scroll_header', 'yes') ) ? 'data-scroll-class="'. $scroll_trigger .':pos-fixed"' : false;
	$mobile_scroll = ( 'yes' == get_option('stack_scroll_mobile_header', 'yes') ) ? 'data-scroll-class="'. $mobile_scroll_trigger .':pos-fixed"' : false;
	$mobile_scroll_class = ( 'yes' == get_option('stack_scroll_mobile_header', 'yes') ) ? 'bar--mobile-sticky' : false;
	
	$background = get_option('stack_header_background_alt', 'bg--dark');
?>

<div class="nav-container">

	<div class="bar <?php echo esc_attr($background); ?> bar--sm visible-sm visible-xs <?php echo esc_attr($mobile_scroll_class); ?>" <?php echo wp_kses_post($mobile_scroll); ?>>
		<div class="container">
			<div class="row">
				<div class="col-xs-8 col-sm-10">
					<?php get_template_part('inc/content-header', 'logo'); ?>
				</div>
				<div class="col-xs-4 col-sm-2 text-right mobile-header">
					<?php 
						if( class_exists('woocommerce') && 'yes' == get_option('stack_header_mobile_cart', 'yes') ){
							get_template_part('inc/content-header', 'woocommerce');
						}
					?>
					<a href="#" class="hamburger-toggle toggled-class" data-toggle-class="#menu4;hidden-xs hidden-sm">
						<i class="icon--sm stack-interface stack-menu"></i>
					</a>
				</div>
			</div><!--end of row-->
		</div><!--end of container-->
	</div><!--end bar-->
	
	<nav class="bar bar--sm <?php echo esc_attr($background); ?> hidden-xs hidden-sm" id="menu4" <?php echo wp_kses_post($scroll); ?>>
		<div class="container">
			<div class="row">
				<div class="col-md-1 hidden-xs hidden-sm">
					<div class="bar__module">
						<?php get_template_part('inc/content-header', 'logo'); ?>
					</div><!--end module-->
				</div>
				<div class="col-md-4">
					<div class="bar__module">
						<form method="get" id="searchform" action="<?php echo esc_url(home_url('/')); ?>">
							<input type="search" placeholder="<?php esc_attr_e('Search site', 'stack'); ?>" name="s" />
						</form>
					</div>
				</div><!--end columns-->
				<div class="col-md-4">
					<?php get_template_part('inc/content-header', 'nav'); ?>
				</div><!--end columns-->
				<div class="col-md-3 text-right text-left-xs">	
					<?php get_template_part('inc/content-header', 'buttons'); ?>
				</div><!--end columns-->	
			</div><!--end of row-->
		</div><!--end of container-->
	</nav>
	
	<?php 
		get_template_part('inc/content-header', 'top-bar');
		get_template_part('inc/content-header', 'side-menu'); 
		get_template_part('inc/content-header', 'search'); 
	?>

</div>