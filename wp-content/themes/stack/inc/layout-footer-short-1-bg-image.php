<?php $image = get_option('stack_footer_background_image'); ?>

<footer class="text-center-xs space--xs imagebg">
	
	<?php if( $image ) : ?>
		<div class="background-image-holder">
			<img src="<?php echo esc_url($image); ?>" alt="Footer Background" />
		</div>
	<?php endif; ?>
	
	<div class="container">
		<div class="row">
			<div class="col-sm-7">
				<?php  
					if ( has_nav_menu( 'footer' ) ){
					    wp_nav_menu( 
					    	array(
						        'theme_location'    => 'footer',
						        'depth'             => 0,
						        'container'         => false,
						        'container_class'   => false,
						        'menu_class'        => 'list-inline'
					        )
					    ); 
					}
				?>
			</div>
			<div class="col-sm-5 text-right text-center-xs">
				<?php get_template_part('inc/content-footer', 'social'); ?>
			</div>
		</div><!--end of row-->
		<div class="row">
			<div class="col-sm-7">
				<?php get_template_part('inc/content-footer', 'copyright'); ?>
			</div>
			<div class="col-sm-5 text-right text-center-xs">
				<a class="type--fine-print stack-footer-email" href="mailto:<?php echo esc_attr(get_option('stack_footer_email', 'support@stack.io')); ?>"><?php echo esc_html(get_option('stack_footer_email', 'support@stack.io')); ?></a>
			</div>
		</div><!--end of row-->
	</div><!--end of container-->
</footer>