<?php 

if(!( function_exists('ebor_get_blog_layouts') )){
	function ebor_get_blog_layouts(){
		$options = array(
			'Simple List'                                       => 'list',
			'Simple List & Sidebar'                             => 'list-sidebar',
			'Grid'                                              => 'cards',
			'Grid & Sidebar'                                    => 'cards-sidebar',
			'Magazine (Use 7 for "Show how many posts" option)' => 'magazine',
			'Magazine With No CTA'                              => 'magazine-simple',
			'Carousel'                                          => 'carousel',
			'Row'                                               => 'row',
			'Row Detailed'                                      => 'row-detailed',
			'Grid Detailed'                                     => 'cards-detailed',
			'Grid Detailed & Sidebar'                           => 'cards-sidebar-detailed',
			'Portfolio Style'                                   => 'portfolio',
			'Search'                                            => 'search'
		);
		
		if( has_filter('ebor_add_blog_layouts') ) {
			$options = apply_filters('ebor_add_blog_layouts', $options);
		}
		
		return $options;
	}
}

if(!( function_exists('ebor_get_shop_layouts') )){
	function ebor_get_shop_layouts(){
		$options = array(
			'Standard 2 Columns' => 'column-2',
			'Standard 3 Columns' => 'column-3',
			'Standard 4 Columns' => 'column-4',
			'Standard & Sidebar' => 'column-sidebar',
			'Tiles 2 Columns'    => 'column-tiles-2',
			'Tiles 3 Columns'    => 'column-tiles-3',
			'Tiles 4 Columns'    => 'column-tiles-4',
			'Tiles & Sidebar'    => 'column-tiles-sidebar',
			'Carousel'           => 'carousel'
		);
		
		if( has_filter('ebor_add_shop_layouts') ) {
			$options = apply_filters('ebor_add_shop_layouts', $options);
		}
		
		return $options;
	}
}

if(!( function_exists('ebor_get_team_layouts') )){
	function ebor_get_team_layouts(){
		$options = array(
			'3 Columns' => 'grid-3',
			'2 Columns' => 'grid-2',
			'1 Column'  => 'grid-1',
			'Carousel'  => 'carousel'
		);
		
		if( has_filter('ebor_add_team_layouts') ) {
			$options = apply_filters('ebor_add_team_layouts', $options);
		}
		
		return $options;
	}
}

if(!( function_exists('ebor_get_portfolio_layouts') )){
	function ebor_get_portfolio_layouts(){
		$options = array(
			'Portfolio Carousel 1'                          => 'carousel-1',
			'Portfolio Carousel 2'                          => 'carousel-2',
			'Titles Outside 1 Column'                       => 'titles-outside-1',
			'Titles Outside 2 Columns'                      => 'titles-outside-2',
			'Titles Outside 3 Columns'                      => 'titles-outside-3',
			'Titles Inside 1 Column'                        => 'titles-inside-1',
			'Titles Inside 2 Columns'                       => 'titles-inside-2',
			'Titles Inside 3 Columns'                       => 'titles-inside-3',
			'Titles Inside on Hover 1 Column'               => 'titles-hover-1',
			'Titles Inside on Hover 2 Columns'              => 'titles-hover-2',
			'Titles Inside on Hover 3 Columns'              => 'titles-hover-3',
			'Tiles'                                         => 'tiles',
			'Squares'                                       => 'squares',
			'Fullscreen Slider'                             => 'fullscreen',
			'Fullwidth (gapless) Titles on Hover 2 Columns' => 'fullwidth-2',
			'Fullwidth (gapless) Titles on Hover 3 Columns' => 'fullwidth-3',
			'Studio'                                        => 'studio',
			'Creative Layout'                               => 'creative'
		);
		
		if( has_filter('ebor_add_portfolio_layouts') ) {
			$options = apply_filters('ebor_add_portfolio_layouts', $options);
		}
		
		return $options;
	}
}

if(!( function_exists('ebor_get_testimonial_layouts') )){
	function ebor_get_testimonial_layouts(){
		$options = array(
			'Slider 1'    => 'slider-1',
			'Slider 2'    => 'slider-2',
			'Avatar Grid' => 'avatar',
			'Avatar List' => 'avatar-list',
			'Thumbnails'  => 'thumbnails'
		);
		
		if( has_filter('ebor_add_testimonial_layouts') ) {
			$options = apply_filters('ebor_add_testimonial_layouts', $options);
		}
		
		return $options;
	}
}

/**
 * Returns an array of all available header layouts
 * 
 * @val array
 * @since 1.0.0
 * @package Foundry
 * @author TommusRhodus
 */
if(!( function_exists('ebor_get_header_options') )){
	function ebor_get_header_options(){
		$options = array(
			'blank'                              => 'No Navigation',
			'sidebar'                            => 'Sidebar Offscreen',
			'sidebar-transparent'                => 'Sidebar Offscreen (Transparent)',
			'fullscreen'                         => 'Fullscreen Modal',
			'fullscreen-transparent'             => 'Fullscreen Modal (Transparent)',
			'minimal-static'                     => 'Logo & Buttons',
			'minimal'                            => 'Logo & Buttons (Transparent)',
			'super-minimal-static'               => 'Logo Only',
			'super-minimal'                      => 'Logo Only (Transparent)',
			'standard'                           => 'Standard Bar & Top Bar',
			'standard-no-top'                    => 'Standard Bar',
			'overlay'                            => 'Standard Bar (Transparent)',
			'overlay-with-top'                   => 'Standard & Top Bar (Transparent)',
			'overlay-dark'                       => 'Standard Bar (Transparent)(Dark Text)',
			'overlay-with-top-dark'              => 'Standard & Top Bar (Transparent)(Dark Text)',
			'centered'                           => 'Centered Bar',
			'centered-transparent'               => 'Centered Bar (Transparent)',
			'centered-transparent-dark'          => 'Centered Bar (Transparent)(Dark Text)',
			'centered-with-top'                  => 'Centered Bar & Top Bar',
			'centered-transparent-with-top'      => 'Centered Bar & Top Bar (Transparent)',
			'centered-transparent-dark-with-top' => 'Centered Bar & Top Bar (Transparent)(Dark Text)',
			'centered-large'                     => 'Stacked Bar',
			'search'                             => 'Search Bar',
			'vertical'                           => 'Sidebar'
		);
		
		if( has_filter('ebor_add_header_layouts') ) {
			$options = apply_filters('ebor_add_header_layouts', $options);
		}
		
		return $options;	
	}
}

if(!( function_exists('ebor_get_footer_options') )){
	function ebor_get_footer_options(){
		$options = array(
			'blank'                     => 'No Footer',
			'subscribe'                 => 'Subscription Form (White Background)',
			'subscribe-bg-secondary'    => 'Subscription Form (Grey Background)',
			'subscribe-bg-dark'         => 'Subscription Form (Dark Background)',
			'subscribe-bg-primary'      => 'Subscription Form (Primary Background)',
			'subscribe-bg-image'        => 'Subscription Form (Image Background)',
			'centered'                  => 'Centered Content (White Background)',
			'centered-bg-secondary'     => 'Centered Content (Grey Background)',
			'centered-bg-dark'          => 'Centered Content (Dark Background)',
			'centered-bg-primary'       => 'Centered Content (Primary Background)',
			'centered-bg-image'         => 'Centered Content (Image Background)',
			'short-1'                   => 'Links, Social Icons (White Background)',
			'short-1-bg-secondary'      => 'Links, Social Icons (Grey Background)',
			'short-1-bg-dark'           => 'Links, Social Icons (Dark Background)',
			'short-1-bg-primary'        => 'Links, Social Icons (Primary Background)',
			'short-1-bg-image'          => 'Links, Social Icons (Image Background)',
			'short-2'                   => 'Links, Social Icons, CTA (White Background)',
			'short-2-bg-secondary'      => 'Links, Social Icons, CTA (Grey Background)',
			'short-2-bg-dark'           => 'Links, Social Icons, CTA (Dark Background)',
			'short-2-bg-primary'        => 'Links, Social Icons, CTA (Primary Background)',
			'short-2-bg-image'          => 'Links, Social Icons, CTA (Image Background)',
			'short-3'                   => 'Logo, Social Icons (White Background)',
			'short-3-bg-secondary'      => 'Logo, Social Icons (Grey Background)',
			'short-3-bg-dark'           => 'Logo, Social Icons (Black Background)',
			'short-3-bg-primary'        => 'Logo, Social Icons (Primary Background)',
			'short-3-bg-image'          => 'Logo, Social Icons (Image Background)',
			'widgets-1'                 => 'Widget Columns (White Background)',
			'widgets-1-bg-secondary'    => 'Widget Columns (Grey Background)',
			'widgets-1-bg-dark'         => 'Widget Columns (Dark Background)',
			'widgets-1-bg-primary'      => 'Widget Columns (Primary Background)',
			'widgets-1-bg-image'        => 'Widget Columns (Image Background)',
			'widgets-2'                 => 'Widget Columns & Sub Footer (White Background)',
			'widgets-2-bg-secondary'    => 'Widget Columns & Sub Footer (Grey Background)',
			'widgets-2-bg-dark'         => 'Widget Columns & Sub Footer (Dark Background)',
			'widgets-2-bg-primary'      => 'Widget Columns & Sub Footer (Primary Background)',
			'widgets-2-bg-image'        => 'Widget Columns & Sub Footer (Image Background)',
			'short-5'                   => 'Text, Social Icons (White Background)',
			'short-5-bg-secondary'      => 'Text, Social Icons (Grey Background)',
			'short-5-bg-dark'           => 'Text, Social Icons (Dark Background)',
			'short-5-bg-primary'        => 'Text, Social Icons (Primary Background)',
			'short-5-bg-image'          => 'Text, Social Icons (Image Background)',
		);
		
		if( has_filter('ebor_add_footer_layouts') ) {
			$options = apply_filters('ebor_add_footer_layouts', $options);
		}
		
		return $options;	
	}
}