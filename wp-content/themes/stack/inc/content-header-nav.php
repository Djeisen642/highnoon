<div class="bar__module">
	<?php  
		if ( has_nav_menu( 'primary' ) ){
		    wp_nav_menu( 
		    	array(
			        'theme_location'    => 'primary',
			        'depth'             => 3,
			        'container'         => false,
			        'container_class'   => false,
			        'menu_class'        => 'menu-horizontal text-left',
			        'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
			        'walker'            => new ebor_medium_rare_bootstrap_navwalker()
		        )
		    ); 
		} else {
			echo '<ul class="menu-horizontal text-left"><li><a href="'. esc_url(admin_url('nav-menus.php')) .'">'. esc_html__('Set up a navigation menu now', 'stack') .'</a></li></ul>';
		}
	?>
</div>