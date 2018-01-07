<footer class="text-center space--sm footer-5">
	
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="heading-block">
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
				<div>
					<?php get_template_part('inc/content-footer', 'social'); ?>
				</div>
				<div>
					<?php get_template_part('inc/content-footer', 'logo'); ?>
				</div>
				<div>
					<?php get_template_part('inc/content-footer', 'copyright'); ?>
				</div>
			</div>
		</div><!--end of row-->
	</div><!--end of container-->
</footer>