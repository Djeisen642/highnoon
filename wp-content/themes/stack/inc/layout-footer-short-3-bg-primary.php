<footer class="footer-3 text-center-xs space--xs bg--primary">
	<div class="container">
		<div class="row">
			<div class="col-sm-6">
				<?php 
					get_template_part('inc/content-footer', 'logo');
					 
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
			</div>
		</div><!--end of row-->
		<div class="row">
			<div class="col-sm-6">
				<p class="type--fine-print"><?php echo esc_html(get_bloginfo('description')); ?></p>
			</div>
			<div class="col-sm-6 text-right text-center-xs">
				<?php get_template_part('inc/content-footer', 'copyright'); ?>
			</div>
		</div><!--end of row-->
	</div><!--end of container-->
</footer>