<?php $background = get_option('stack_header_background_alt', 'bg--dark'); ?>

<div class="nav-container nav-container--sidebar">
		    
	<div class="nav-sidebar-column <?php echo esc_attr($background); ?>">
		<div class="text-center text-block">
			<?php get_template_part('inc/content-header', 'logo'); ?>
			<p><?php echo esc_html(get_bloginfo('description')); ?></p>
		</div>
		<hr>
		<div class="text-block">
			<ul class="menu-vertical">
				<?php  
					if ( has_nav_menu( 'vertical' ) ){
					    wp_nav_menu( 
					    	array(
						        'theme_location'    => 'vertical',
						        'depth'             => 3,
						        'container'         => false,
						        'container_class'   => false,
						        'menu_class'        => 'menu-vertical',
						        'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
						        'walker'            => new ebor_medium_rare_bootstrap_navwalker()
					        )
					    ); 
					} else {
						echo '<ul class="menu-horizontal text-left"><li><a href="'. esc_url(admin_url('nav-menus.php')) .'">'. esc_html__('Set up a navigation menu now', 'stack') .'</a></li></ul>';
					}
				?>
			</ul>
		</div>
		<div class="text-block">
			<?php get_template_part('inc/content-header', 'buttons'); ?>
		</div>
		<hr>
		<div>
			<?php get_template_part('inc/content-header', 'social'); ?>
			<div>
				<?php get_template_part('inc/content-footer', 'copyright'); ?>
			</div>
		</div>
	</div>
	
	<div class="nav-sidebar-column-toggle visible-xs visible-sm" data-toggle-class=".nav-sidebar-column;active">
		<i class="stack-menu"></i>
	</div>
	
</div>