<div class="nav-container">

	<nav class="bar bar-toggle bar--transparent bar--absolute bar--absolute-mobile">
		<div class="container">
			<div class="row">
				<div class="col-md-1 col-sm-2 col-xs-3">
					<div class="bar__module">
						<?php get_template_part('inc/content-header', 'logo'); ?>
					</div><!--end module-->
				</div>
				<div class="col-md-11 col-sm-10 col-xs-9">
					<div class="bar__module">
						<a class="menu-toggle pull-right" href="#" data-notification-link="sidebar-menu">
							<i class="stack-interface stack-menu"></i>
						</a>
					</div><!--end module-->
				</div><!--end columns-->	
			</div><!--end of row-->
		</div><!--end of container-->
	</nav>
	
	<div class="notification pos-right pos-top side-menu bg--white" data-notification-link="sidebar-menu" data-animation="from-right">
		<div class="side-menu__module pos-vertical-center text-right">
			<?php  
				if ( has_nav_menu( 'sidebar' ) ){
				    wp_nav_menu( 
				    	array(
					        'theme_location'    => 'sidebar',
					        'depth'             => 0,
					        'container'         => false,
					        'container_class'   => false,
					        'menu_class'        => 'menu-vertical'
				        )
				    ); 
				} else {
					echo '<ul class="menu-vertical"><li><a href="'. esc_url(admin_url('nav-menus.php')) .'">'. esc_html__('Set up a navigation menu now', 'stack') .'</a></li></ul>';
				}
			?>
		</div><!--end module-->
		<div class="side-menu__module pos-bottom pos-absolute col-xs-12 text-right">
			<?php get_template_part('inc/content-header', 'social'); ?>
		</div>
	</div>

</div>