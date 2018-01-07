<?php $image = get_option('stack_footer_background_image'); ?>

<footer class="space--sm footer-1 text-center-xs imagebg">
	
	<?php if( $image ) : ?>
		<div class="background-image-holder">
			<img src="<?php echo esc_url($image); ?>" alt="Footer Background" />
		</div>
	<?php endif; ?>
	
	<div class="container">
		<div class="row">
			<div class="col-sm-6">
				<?php  
					if ( has_nav_menu( 'footer' ) ){
					    wp_nav_menu( 
					    	array(
						        'theme_location'    => 'footer',
						        'depth'             => 0,
						        'container'         => false,
						        'container_class'   => false,
						        'menu_class'        => 'list-inline list--hover'
					        )
					    ); 
					}
				?>
			</div>
			<div class="col-sm-6 text-right text-center-xs">
				<?php get_template_part('inc/content-footer', 'social'); ?>
				<?php get_template_part('inc/content-footer', 'button'); ?>
			</div>
		</div><!--end of row-->
		<div class="row">
			<div class="col-sm-12">
				<?php get_template_part('inc/content-footer', 'logo'); ?>
				<?php get_template_part('inc/content-footer', 'copyright'); ?>
			</div>
		</div><!--end of row-->
	</div><!--end of container-->
</footer>