<?php
	$blurb          = get_option( 'stack_sub_header_blurb', 'Stack | Multipurpose WP Theme with Visual Composer Page Builder' );
	$side_menu      = get_option( 'stack_side_menu', 'yes' );
	$search_menu    = get_option( 'stack_search_menu', 'yes' );
	$scroll_trigger = get_option('stack_scroll_trigger', '200px');
	$scroll         = ( 'yes' == get_option( 'stack_scroll_sub_header', 'no' ) ) ? 'data-scroll-class="'. $scroll_trigger .':pos-fixed"' : false;
	
	if( 'yes' == $side_menu )
		get_template_part( 'inc/content-header', 'side-menu' ); 
	
	if( 'yes' == $search_menu )
		get_template_part( 'inc/content-header', get_option( 'stack_search_menu_layout', 'search' ) ); 
?>

<section class="bar bar-3 bar--sm <?php echo esc_attr(get_option('stack_sub_header_background', 'bg--secondary')); ?> header--top-bar" <?php echo wp_kses_post($scroll); ?>>
	<div class="container">
		<div class="row">
		
			<?php if( $blurb ) : ?>
				<div class="col-md-6">
					<div class="bar__module">
						<span class="type--fade top-bar-blurb"><?php echo wp_kses_post($blurb); ?></span>	
					</div>
				</div>
			<?php endif; ?>
			
			<div class="col-md-6 text-right text-left-xs text-left-sm">
				<div class="bar__module">
					<ul class="menu-horizontal">
					
						<?php  
							if ( has_nav_menu( 'top-bar' ) ){
							    wp_nav_menu( 
							    	array(
								        'theme_location'    => 'top-bar',
								        'depth'             => 0,
								        'container'         => false,
								        'items_wrap'        => '%3$s'
							        )
							    ); 
							}
						?>
						
						<?php if( 'yes' == get_option('stack_social_icons_header_top_bar', 'yes') ) : ?>
						
							<?php 
								$title = get_bloginfo('title');
								$protocols = array('http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet', 'skype');
								
								for( $i = 1; $i < 11; $i++ ){
									if( $url = get_option("header_social_url_$i") ) {
										
										$icon = get_option("header_social_icon_$i");
										
										echo '<li>
											      <a href="' . esc_url($url, $protocols) . '" title="'. $title . ' ' . ucfirst(str_replace('socicon-', '', $icon)) .' '. esc_attr__('Page', 'stack') .'" target="_blank">
												      <i class="socicon icon--xs ' . esc_attr($icon) . '"></i>
											      </a>
											  </li>';
											  
									}
								} 
							?>
							
						<?php endif; ?>
						
						<?php if( 'yes' == $search_menu ) : ?>
							<li class="stack-search-menu">
								<a href="#" data-notification-link="search-box">
									<i class="stack-search"></i>
								</a>
							</li>
						<?php endif; ?>
						
						<?php if( class_exists('woocommerce') && 'yes' == get_option('stack_header_cart', 'yes') ) : ?>
							<li>
								<?php get_template_part('inc/content-header', 'woocommerce'); ?>
							</li>
						<?php endif; ?>
						
						<?php 
							if( function_exists( 'icl_get_languages' ) && 'yes' == get_option( 'stack_header_wpml', 'yes' ) ){
								get_template_part( 'inc/content-header-wpml', get_option( 'stack_header_wpml_layout', 'flag' ) );
							}
						?>
						
						<?php if( 'yes' == $side_menu ) : ?>
							<li>
								<a href="#" data-notification-link="side-menu">
									<i class="stack-dot-3"></i>
								</a>
							</li>
						<?php endif; ?>
						
					</ul>
				</div>
			</div>
		</div><!--end of row-->
	</div><!--end of container-->
</section><!--end bar-->