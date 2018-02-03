<?php 

function ebor_set_transport($wp_customize){	
	
	//Single post meta
	$wp_customize->selective_refresh->add_partial( 'stack_single_post_title', array(
	    'selector' => '.single .article__title h1'
	) );
	
	//Single post meta
	$wp_customize->selective_refresh->add_partial( 'stack_single_post_meta', array(
	    'selector' => '.single .article__title h1 + span'
	) );
	
	//Single post tags
	$wp_customize->selective_refresh->add_partial( 'stack_single_post_tags', array(
	    'selector' => '.single .post__tags'
	) );
	
	//Single post sharing
	$wp_customize->selective_refresh->add_partial( 'stack_single_post_sharing', array(
	    'selector' => '.single .article__share'
	) );
	
	//Single post cta
	$wp_customize->selective_refresh->add_partial( 'stack_single_post_cta', array(
	    'selector' => '.single .single-post-cta'
	) );
	
	//Single post related
	$wp_customize->selective_refresh->add_partial( 'stack_single_post_related', array(
	    'selector' => '.single .loop-related'
	) );
	
	//Logo
	$wp_customize->selective_refresh->add_partial( 'stack_logo_dark', array(
	    'selector' => '.logo-holder:not(.footer-logo-holder)'
	) );
	
	$wp_customize->selective_refresh->add_partial( 'stack_footer_logo_dark', array(
	    'selector' => '.footer-logo-holder'
	) );
	
	//Top Bar Blurb
	$wp_customize->selective_refresh->add_partial( 'stack_sub_header_blurb', array(
	    'selector' => '.top-bar-blurb'
	) );
	
	//Footer Copyright
	$wp_customize->selective_refresh->add_partial( 'stack_footer_copyright', array(
	    'selector' => '.footer-stack-copyright'
	) );
	
	//Footer Contact Form
	$wp_customize->selective_refresh->add_partial( 'stack_footer_subscribe_shortcode', array(
	    'selector' => '.stack-footer-subscribe'
	) );
	
	//Footer social icons
	$wp_customize->selective_refresh->add_partial( 'footer_social_icon_1', array(
	    'selector' => '.stack-footer-social'
	) );
	
	//header social icons
	$wp_customize->selective_refresh->add_partial( 'header_social_icon_1', array(
	    'selector' => '.stack-header-social'
	) );
	
	$wp_customize->selective_refresh->add_partial( 'stack_footer_email', array(
	    'selector' => '.stack-footer-email'
	) );
	
	$wp_customize->selective_refresh->add_partial( 'stack_footer_button_text', array(
	    'selector' => '.stack-footer-button'
	) );
	
	$wp_customize->selective_refresh->add_partial( 'stack_header_button_url_1', array(
	    'selector' => '.stack-header-buttons'
	) );
	
	$wp_customize->selective_refresh->add_partial( 'stack_careers_title', array(
	    'selector' => '.career-title'
	) );
	
	$wp_customize->selective_refresh->add_partial( 'stack_portfolio_title', array(
	    'selector' => '.portfolio-title'
	) );
	
	$wp_customize->selective_refresh->add_partial( 'stack_team_title', array(
	    'selector' => '.team-title'
	) );
	
	$wp_customize->selective_refresh->add_partial( 'stack_shop_title', array(
	    'selector' => '.shop-title'
	) );
	
	$wp_customize->selective_refresh->add_partial( 'stack_show_breadcrumbs', array(
	    'selector' => '.breadcrumbs'
	) );
	
	$wp_customize->selective_refresh->add_partial( 'stack_careers_cta', array(
	    'selector' => '.stack-careers-cta'
	) );
	
	$wp_customize->selective_refresh->add_partial( 'stack_search_menu', array(
	    'selector' => '.stack-search-menu'
	) );
	
	$wp_customize->selective_refresh->add_partial( 'stack_magazine_loop_cta', array(
	    'selector' => '.magazine-loop-cta'
	) );
	
	$wp_customize->selective_refresh->add_partial( 'stack_footer_background_image', array(
	    'selector' => 'footer.imagebg'
	) );
	
	$wp_customize->selective_refresh->add_partial( 'stack_single_featured_image', array(
	    'selector' => '.single-post .featured-image-holder'
	) );
	
	$wp_customize->selective_refresh->add_partial( 'stack_show_breadcrumb_section', array(
	    'selector' => '.breadcrumb--section'
	) );
	
	$wp_customize->selective_refresh->add_partial( 'stack_image_format_post_author', array(
	    'selector' => '.format-image-post-author'
	) );
	
	$wp_customize->selective_refresh->add_partial( 'stack_blog_title', array(
	    'selector' => '.blog--title'
	) );
	
	$wp_customize->selective_refresh->add_partial( 'stack_single_post_author', array(
	    'selector' => '.post-author'
	) );

}
add_action('customize_register', 'ebor_set_transport', 20 );


/**
 * Build theme options
 * Uses the Ebor_Options class found in the ebor-framework plugin
 * Panels are WP 4.0+!!!
 * 
 * @since 1.0.0
 * @author tommusrhodus
 */
if( class_exists('Ebor_Options') ){
	$ebor_options = new Ebor_Options;
	
	/**
	 * Variables
	 */
	$theme_name = 'Stack';
	$yesNo = array('yes' => 'Yes', 'no' => 'No');
	$colours = array(
		'original--bg' => 'Original Background (White)',
		'bg--secondary' => 'Grey Background',
		'bg--dark' => 'Dark Background',
		'bg--primary' => 'Primary Colour Background',
		'bg--primary-1' => 'Primary 1 Colour Background'
	);
	$colours_header = array(
		'original--bg' => 'Original Background (White)',
		'bg--secondary' => 'Grey Background',
		'bg--dark' => 'Dark Background'
	);
	$portfolio_carousel_columns = array(
		'col-sm-6' => '2 Columns',	
		'col-sm-4' => '3 Columns',	
		'col-sm-3' => '4 Columns'
	);
	
$preset = '<h2>Sound like the job for you?</h2>
<p class="lead">Got what it takes to work with us? Great! Send us a link to your resumé or portfolio to become part of our talent pool.</p>
<a class="btn btn--primary type--uppercase" href="#purchase-template">Drop your resumé here</a>';
	
	$new_social_options = ebor_get_social_icons();
	$blog_options = array_flip(ebor_get_blog_layouts());
	$team_options = array_flip(ebor_get_team_layouts());
	$portfolio_options = array_flip(ebor_get_portfolio_layouts());
	$shop_options = array_flip(ebor_get_shop_layouts());
	$header_options = ebor_get_header_options();
	$footer_options = ebor_get_footer_options();
	
	$shop_single_options = array(
		'single-product' => 'Bottom Sidebar',
		'single-product-sidebar' => 'Standard Sidebar on Right'
	);
	
	$override_header_options = array('no' => 'Do Not Override Global Option') + $header_options;
	
	/**
	 * All Site Settings
	 */
	$ebor_options->add_panel( $theme_name . ': Site Settings', 205, 'All of the controls in this section directly relate to the control of site within ' . $theme_name);
	$ebor_options->add_section('site_section', 'Global Site Settings', 15, $theme_name . ': Site Settings', 'For details on the theme options available and how to use them, please see <a target="_blank" href="https://www.youtube.com/watch?v=H2cPCEl5qLQ">this video</a>');
	$ebor_options->add_setting('select', 'stack_pagination_layout', 'Global Pagination Layout', 'site_section', 'text', 1, array('text' => 'Text Pagination', 'numbers' => 'Numbered Pagination'));
	$ebor_options->add_setting('select', 'stack_parallax_disable_mobile', 'Enable parallax on mobile?', 'site_section', 'parallax-enable-mobile', 2, array('parallax-enable-mobile' => 'Enable Parallax on Mobile', 'parallax-disable-mobile' => 'Disable Parallax on Mobile'));
	$ebor_options->add_setting('select', 'stack_show_breadcrumbs', 'Show breadcrumbs?', 'site_section', 'yes', 3, $yesNo);
	$ebor_options->add_setting('select', 'stack_show_breadcrumb_section', 'Show breadcrumb section (includes post title etc.)?', 'site_section', 'yes', 5, $yesNo);
	$ebor_options->add_setting('select', 'stack_dropdown_method', 'Menu Dropdown Show Method', 'site_section', 'dropdowns--click', 8, array('dropdowns--click' => 'Show Dropdown Menus on Click', 'dropdowns--hover' => 'Show Menu Dropdowns on Hover'));
	$ebor_options->add_setting('select', 'stack_rounded', 'Rounded or squared elements?', 'site_section', 'stack--rounded', 10, array('stack--rounded' => 'Rounded Elements', 'stack--square' => 'Squared Elements'));
	$ebor_options->add_setting('select', 'stack_boxed', 'Fullwidth or Boxed site?', 'site_section', 'normal-layout', 10, array('normal-layout' => 'Fullwidth Site', 'boxed-layout' => 'Boxed Site'));
	$ebor_options->add_setting('select', 'stack_filters_position', 'Masonry Filters Appearance', 'site_section', 'filters-dropdown', 20, array('filters-dropdown' => 'Dropdown Filters', 'filters-horizontal' => 'Horizontal Text Filters'));
	$ebor_options->add_setting('select', 'stack_active_tabs', 'Make all Tabs inactive on page load?', 'site_section', 'active-tabs', 25, array('active-tabs' => 'First Tab Active', 'no-active-tabs' => 'No Tabs Active'));
	$ebor_options->add_setting('select', 'stack_active_accordions', 'Make all Accordions inactive on page load?', 'site_section', 'active-accordions', 30, array('active-accordions' => 'First Accordion Active', 'no-active-accordions' => 'No Accordions Active'));
	$ebor_options->add_setting('select', 'stack_reduce_iconsmind', 'Load small icons file? NOTE: This will cause icons not to appear on your site', 'site_section', 'no', 35, $yesNo);
	$ebor_options->add_setting('input', 'stack_lightbox_text', 'Lightbox gallery image count text', 'site_section', "Image %1 of %2", 40);
	
	$ebor_options->add_section('typed_text_section', 'Typed Text Settings', 20, $theme_name . ': Site Settings', '');
	$ebor_options->add_setting('input', 'stack_typed_speed', 'Typed text effect speed (1 is fastest)', 'typed_text_section', '100', 25);
	
	$ebor_options->add_section('maps_section', 'Maps Settings', 25, $theme_name . ': Site Settings', '');
	$ebor_options->add_setting('image', 'stack_map_marker', 'Map Marker', 'maps_section', EBOR_THEME_DIRECTORY . 'style/img/mapmarker.png', 5);
	$ebor_options->add_setting('input', 'stack_map_marker_title', 'Map Marker Title', 'maps_section', 'Stack', 10);
	
	$ebor_options->add_section('smooth_scroll_section', 'In Page Scrolling Settings', 35, $theme_name . ': Site Settings', 'If you\'re using # links in a page to scroll to sections of a page, this control will allow you to offset that scroll. A positive number will have the scroll end further up the page, above your linked element. A negative number will have the scroll end further down the page.');
	$ebor_options->add_setting('input', 'stack_scroll_offset', 'In Page Scrolling Offset', 'smooth_scroll_section', '0', 5);
	
	$ebor_options->add_section('search_section', 'Search Settings', 45, $theme_name . ': Site Settings', '');
	$ebor_options->add_setting('select', 'search_layout', 'Search Page Layout', 'search_section', 'search', 20, $blog_options);
	
	/**
	 * All Header Settings
	 */
	$ebor_options->add_panel( $theme_name . ': Header Settings', 205, 'All of the controls in this section directly relate to the control of header design within ' . $theme_name);
	
	/**
	 * Logo Settings
	 */
	$ebor_options->add_section('logo_section', 'Logo Settings', 15, $theme_name . ': Header Settings', 'Please ensure you specify both a light & dark version of your logo in the correct controls. Increase the logo max height as you need, but <strong>please increase this sparingly</strong>.');
	$ebor_options->add_setting('image', 'stack_logo_dark', 'Dark Logo', 'logo_section', EBOR_THEME_DIRECTORY . 'style/img/logo-dark.png', 5);
	$ebor_options->add_setting('image', 'stack_logo_light', 'Light Logo', 'logo_section', EBOR_THEME_DIRECTORY . 'style/img/logo-light.png', 10);
	$ebor_options->add_setting('input', 'stack_logo_size', 'Logo Max Height (23px default)', 'logo_section', '23px', 15);
	$ebor_options->add_setting('input', 'stack_logo_alt_text', 'Logo Alt Text', 'logo_section', 'logo', 20);
	$ebor_options->add_setting('input', 'stack_nav_margin_top', 'Nav top margin, increase this if you increase the logo max size. Increasing will move the navigation down to center it with your logo (0 default)', 'logo_section', '0', 25);
	
	$ebor_options->add_section('header_layout_section', 'Layout Settings', 10, $theme_name . ': Header Settings', '<strong>Please Note:</strong> Your choice here will not affect every page if you installed our demo data. Some of our installed demo data pages, specifically the homepages, have a header override set. Please watch <a href="https://www.youtube.com/watch?v=kQwOWbVY0K0">This Explanatory Video</a> for what this means, and how to use these settings.');
	$ebor_options->add_setting('select', 'header_layout', 'Global Header Layout', 'header_layout_section', 'standard', 20, $header_options);
	$ebor_options->add_setting('select', 'stack_header_background', 'Header Background Style', 'header_layout_section', 'original--bg', 22, $colours_header);
	$ebor_options->add_setting('select', 'stack_header_background_alt', 'Header Background Style (Search & Vertical Layouts)', 'header_layout_section', 'bg--dark', 23, $colours_header);
	$ebor_options->add_setting('select', 'stack_dropdown_width', 'Dropdown Menus Width', 'header_layout_section', 'col-md-2', 24, array(
		'col-md-2' => 'Small',
		'col-md-3' => 'Medium',
		'col-md-4' => 'Large'
	));
	$ebor_options->add_setting('select', 'stack_scroll_header', 'Header follows on scroll?', 'header_layout_section', 'yes', 25, $yesNo);
	$ebor_options->add_setting('select', 'stack_scroll_sub_header', 'Sub Header follows on scroll?', 'header_layout_section', 'no', 27, $yesNo);
	$ebor_options->add_setting('select', 'stack_scroll_mobile_header', 'Header follows on scroll? (Mobile)', 'header_layout_section', 'yes', 30, $yesNo);
	$ebor_options->add_setting('input', 'stack_scroll_trigger', 'Distance down page before follow header triggers (200px default)', 'header_layout_section', '200px', 35);
	$ebor_options->add_setting('input', 'stack_mobile_scroll_trigger', 'Distance down page before follow header triggers, mobile (200px default)', 'header_layout_section', '200px', 40);
	
	$ebor_options->add_section('top_bar_section', 'Sub Header Settings', 25, $theme_name . ': Header Settings', '<code>NOTE:</code> For details on the "target" options for buttons <a href="https://www.w3schools.com/tags/att_a_target.asp" target="_blank">see here</a>');
	$ebor_options->add_setting('select', 'stack_sub_header_background', 'Sub Header Background Style', 'top_bar_section', 'bg--secondary', 1, $colours);
	$ebor_options->add_setting('textarea', 'stack_sub_header_blurb', 'Sub Header blurb text', 'top_bar_section', 'Stack | Multipurpose WP Theme with Visual Composer Page Builder', 5);
	$ebor_options->add_setting('input', 'stack_header_button_text_1', 'Header Button Text 1', 'top_bar_section', 'Try Stack', 15);
	$ebor_options->add_setting('input', 'stack_header_button_url_1', 'Header Button URL 1', 'top_bar_section', '#', 20);
	$ebor_options->add_setting('input', 'stack_header_button_target_1', 'Header Button Target 1', 'top_bar_section', '', 23);
	$ebor_options->add_setting('input', 'stack_header_button_class_1', 'Header Button CSS Class 1', 'top_bar_section', 'btn btn--sm type--uppercase', 24);
	$ebor_options->add_setting('input', 'stack_header_button_text_2', 'Header Button Text 2', 'top_bar_section', 'Buy Now', 25);
	$ebor_options->add_setting('input', 'stack_header_button_url_2', 'Header Button URL 2', 'top_bar_section', '#', 30);
	$ebor_options->add_setting('input', 'stack_header_button_target_2', 'Header Button Target 2', 'top_bar_section', '', 33);
	$ebor_options->add_setting('input', 'stack_header_button_class_2', 'Header Button CSS Class 2', 'top_bar_section', 'btn btn--sm btn--primary type--uppercase', 34);
	$ebor_options->add_setting('select', 'stack_side_menu', 'Show site menu link in top bar? (3 dots)', 'top_bar_section', 'yes', 35, $yesNo);
	$ebor_options->add_setting('select', 'stack_social_icons_header_top_bar', 'Show social icons in top bar?', 'top_bar_section', 'yes', 37, $yesNo);
	$ebor_options->add_setting('select', 'stack_search_menu', 'Show search link in top bar? (magnifying glass)', 'top_bar_section', 'yes', 40, $yesNo);
	$ebor_options->add_setting('select', 'stack_search_menu_layout', 'Search dropdown style', 'top_bar_section', 'searcg', 42, array('search' => 'Small height search area', 'search-full' => 'Full height search area'));
	$ebor_options->add_setting('select', 'stack_header_cart', 'Show cart icon in top bar?', 'top_bar_section', 'yes', 45, $yesNo);
	$ebor_options->add_setting('select', 'stack_header_mobile_cart', 'Show cart icon in mobile bar?', 'top_bar_section', 'yes', 50, $yesNo);
	
	if( function_exists( 'icl_get_languages' ) ){
		$ebor_options->add_setting('select', 'stack_header_wpml', 'Show WPML dropdown in top bar?', 'top_bar_section', 'yes', 50, $yesNo);
		$ebor_options->add_setting('select', 'stack_header_wpml_layout', 'WPML dropdown layout', 'top_bar_section', 'flag', 55, array( 'flag' => 'Flag', 'text' => 'Text' ));
	}
	
	$ebor_options->add_section('slide_in_section', 'Slide In Header Settings', 35, $theme_name . ': Header Settings', '<code>NOTE:</code> For details on the "target" options for buttons <a href="https://www.w3schools.com/tags/att_a_target.asp" target="_blank">see here</a>');
	$ebor_options->add_setting('input', 'stack_side_menu_button_1_url', 'Button URL 1', 'slide_in_section', '', 20);
	$ebor_options->add_setting('input', 'stack_side_menu_button_1_text', 'Button Text 1', 'slide_in_section', '', 30);
	$ebor_options->add_setting('input', 'stack_side_menu_button_1_label', 'Button Label 1', 'slide_in_section', '', 40);
	$ebor_options->add_setting('input', 'stack_side_menu_button_1_target', 'Button Target 1', 'slide_in_section', '', 45);
	$ebor_options->add_setting('input', 'stack_side_menu_button_2_url', 'Button URL 2', 'slide_in_section', '', 50);
	$ebor_options->add_setting('input', 'stack_side_menu_button_2_text', 'Button Text 2', 'slide_in_section', '', 60);
	$ebor_options->add_setting('input', 'stack_side_menu_button_2_label', 'Button Label 2', 'slide_in_section', '', 70);
	$ebor_options->add_setting('input', 'stack_side_menu_button_2_target', 'Button Target 2', 'slide_in_section', '', 75);
	
	$ebor_options->add_section('header_social_settings_section', 'Header Social Settings', 20, $theme_name . ': Header Settings');
	
	//header Icons
	for( $i = 1; $i < 11; $i++ ){
		$ebor_options->add_setting('select', 'header_social_icon_' . $i, 'Header Social Icon ' . $i, 'header_social_settings_section', 'none', 20 + $i + $i, $new_social_options);
		$ebor_options->add_setting('input', 'header_social_url_' . $i, 'Header Social URL ' . $i, 'header_social_settings_section', '', 21 + $i + $i);
	}
	
	$ebor_options->add_section('archive_header_section', 'Archive Header Settings', 35, $theme_name . ': Header Settings', '<code>NOTE:</code> The header options set here will override the global option set in "appearance => customise => header settings => header layout" for each given archive.');
	$ebor_options->add_setting('select', 'blog_header_layout', 'Override Header: Blog', 'archive_header_section', 'no', 5, $override_header_options);
	$ebor_options->add_setting('select', 'portfolio_header_layout', 'Override Header: Portfolio', 'archive_header_section', 'no', 10, $override_header_options);
	$ebor_options->add_setting('select', 'team_header_layout', 'Override Header: Team', 'archive_header_section', 'no', 15, $override_header_options);
	$ebor_options->add_setting('select', 'career_header_layout', 'Override Header: Careers', 'archive_header_section', 'no', 17, $override_header_options);
	$ebor_options->add_setting('select', 'search_header_layout', 'Override Header: Search', 'archive_header_section', 'no', 20, $override_header_options);
	$ebor_options->add_setting('select', 'shop_header_layout', 'Override Header: Shop', 'archive_header_section', 'no', 25, $override_header_options);
	
	/**
	 * All Blog Settings
	 */
	$ebor_options->add_panel( $theme_name . ': Blog Settings', 215, 'All of the controls in this section directly relate to the control of blog items within ' . $theme_name);
	
	/**
	 * single.php settings
	 */
	$ebor_options->add_section('blog_single_section', 'Single Blog Post Settings', 15, $theme_name . ': Blog Settings');
	$ebor_options->add_setting('select', 'stack_single_post_title', 'Show post title?', 'blog_single_section', 'yes', 1, $yesNo);
	$ebor_options->add_setting('select', 'stack_post_sidebar', 'Show post sidebar?', 'blog_single_section', 'no', 2, $yesNo);
	$ebor_options->add_setting('select', 'stack_single_featured_image', 'Show Featured Image below post title?', 'blog_single_section', 'no', 3, $yesNo);
	$ebor_options->add_setting('select', 'stack_single_post_meta', 'Show post meta under title?', 'blog_single_section', 'yes', 5, $yesNo);
	$ebor_options->add_setting('select', 'stack_image_format_post_author', 'Show post author in header? (Image Format)', 'blog_single_section', 'yes', 6, $yesNo);
	$ebor_options->add_setting('select', 'stack_single_post_tags', 'Show post tags?', 'blog_single_section', 'yes', 7, $yesNo);
	$ebor_options->add_setting('select', 'stack_single_post_sharing', 'Show sharing buttons after content?', 'blog_single_section', 'yes', 10, $yesNo);
	$ebor_options->add_setting('select', 'stack_single_post_author', 'Show post author section?', 'blog_single_section', 'yes', 13, $yesNo);
	$ebor_options->add_setting('select', 'stack_single_post_cta', 'Show call to action section?', 'blog_single_section', 'yes', 15, $yesNo);
	$ebor_options->add_setting('input', 'stack_single_post_cta_shortcode', 'Add shortcode for CTA box (CF7)', 'blog_single_section', '', 17);
	$ebor_options->add_setting('select', 'stack_single_post_nav', 'Show post navigation?', 'blog_single_section', 'yes', 19, $yesNo);
	$ebor_options->add_setting('select', 'stack_single_post_related', 'Show related posts?', 'blog_single_section', 'yes', 20, $yesNo);
	$ebor_options->add_setting('select', 'stack_blog_single_breadcrumbs', 'Show breadcrumbs?', 'blog_single_section', 'no', 25, $yesNo);
	
	/**
	 * Archive settings
	 */
	$ebor_options->add_section('blog_archive_section', 'Blog Archive Settings', 15, $theme_name . ': Blog Settings');
	$ebor_options->add_setting('input', 'stack_blog_title', 'Blog index title', 'blog_archive_section', 'Our Blog', 5);
	$ebor_options->add_setting('input', 'stack_blog_description', 'Blog index description', 'blog_archive_section', '', 10);
	$ebor_options->add_setting('image', 'stack_blog_breadcrumb_image', 'Blog Titles Background Image', 'blog_archive_section', '', 15);
	$ebor_options->add_setting('select', 'stack_blog_layout', 'Global Blog Layout', 'blog_archive_section', 'cards-sidebar', 20, $blog_options);
	$ebor_options->add_setting('input', 'stack_magazine_loop_cta', 'Add shortcode for Magazine Layout CTA box (CF7)', 'blog_archive_section', '', 25);
	$ebor_options->add_setting('select', 'stack_show_loop_categories', 'Show category dropdown above blog loop?', 'blog_archive_section', 'yes', 30, $yesNo);
	
	/**
	 * All team Settings
	 */
	$ebor_options->add_panel( $theme_name . ': Team Settings', 220, 'All of the controls in this section directly relate to the control of team items within ' . $theme_name);
	
	/**
	 * Archive settings
	 */
	$ebor_options->add_section('team_archive_section', 'Team Archive Settings', 15, $theme_name . ': Team Settings');
	$ebor_options->add_setting('input', 'stack_team_title', 'Team Archive Title', 'team_archive_section', 'Our Team', 10);
	$ebor_options->add_setting('image', 'stack_team_breadcrumb_image', 'Team Titles Background Image', 'team_archive_section', '', 12);
	$ebor_options->add_setting('select', 'stack_team_layout', 'Global Team Layout', 'team_archive_section', 'grid-3', 20, $team_options);
	
	$ebor_options->add_section('team_single_section', 'Single Team Post Settings', 15, $theme_name . ': Team Settings');
	$ebor_options->add_setting('select', 'stack_single_team_nav', 'Show post navigation?', 'team_single_section', 'yes', 19, $yesNo);
	
	/**
	 * All Portfolio Settings
	 */
	$ebor_options->add_panel( $theme_name . ': Portfolio Settings', 220, 'All of the controls in this section directly relate to the control of Portfolio items within ' . $theme_name);
	
	/**
	 * Portfolio Archive settings
	 */
	$ebor_options->add_section('portfolio_archive_section', 'Portfolio Archive Settings', 15, $theme_name . ': Portfolio Settings');
	$ebor_options->add_setting('input', 'stack_portfolio_title', 'Portfolio Archive Title', 'portfolio_archive_section', 'Our Portfolio', 10);
	$ebor_options->add_setting('image', 'stack_portfolio_breadcrumb_image', 'Portfolio Titles Background Image', 'portfolio_archive_section', '', 15);
	$ebor_options->add_setting('select', 'stack_portfolio_layout', 'Global Portfolio Layout', 'portfolio_archive_section', 'titles-inside-2', 20, $portfolio_options);
	$ebor_options->add_setting('select', 'stack_portfolio_filters', 'Show category dropdown above portfolio loop?', 'portfolio_archive_section', 'yes', 30, $yesNo);
	$ebor_options->add_setting('select', 'stack_portfolio_carousel_columns', 'Portfolio Carousels Columns', 'portfolio_archive_section', 'col-sm-6', 35, $portfolio_carousel_columns);
	
	
	/**
	 * Portfolio single settings
	 */
	$ebor_options->add_section('portfolio_single_section', 'Portfolio Single Post Settings', 25, $theme_name . ': Portfolio Settings');
	$ebor_options->add_setting('select', 'stack_single_portfolio_layout', 'Single Portfolio Post Layout', 'portfolio_single_section', 'split', 20, array('split' => 'Split (image left, text right)', 'feed' => 'Feed (image top, text bottom)', 'blank' => 'Blank (no image)'));
	$ebor_options->add_setting('select', 'stack_single_portfolio_nav', 'Show post navigation?', 'portfolio_single_section', 'yes', 25, $yesNo);
	
	/**
	 * All shop Settings
	 */
	$ebor_options->add_panel( $theme_name . ': Shop Settings', 235, 'All of the controls in this section directly relate to the control of shop items within ' . $theme_name);
	
	/**
	 * Shop Archive settings
	 */
	$ebor_options->add_section('shop_archive_section', 'Shop Archive Settings', 15, $theme_name . ': Shop Settings');
	$ebor_options->add_setting('input', 'stack_shop_title', 'Shop Archive Title', 'shop_archive_section', 'Our Shop', 10);
	$ebor_options->add_setting('image', 'stack_shop_breadcrumb_image', 'Shop Titles Background Image', 'shop_archive_section', '', 15);
	$ebor_options->add_setting('select', 'stack_shop_layout', 'Global Shop Layout', 'shop_archive_section', 'column-3', 20, $shop_options);
	$ebor_options->add_setting('select', 'stack_single_product_layout', 'Single Product Layout', 'shop_archive_section', 'single-product', 25, $shop_single_options);
	$ebor_options->add_setting('select', 'stack_product_excerpt', 'Show excerpt of product text in product feeds?', 'shop_archive_section', 'no', 30, $yesNo);
	$ebor_options->add_setting('select', 'stack_product_rating', 'Show star rating of product in feeds?', 'shop_archive_section', 'no', 35, $yesNo);
	$ebor_options->add_setting('select', 'stack_add_to_cart', 'Add to cart button style', 'shop_archive_section', 'modern', 40, array('modern' => 'Modern', 'traditional' => 'Traditional'));
	
	/**
	 * All Career Settings
	 */
	$ebor_options->add_panel( $theme_name . ': Career Settings', 225, 'All of the controls in this section directly relate to the control of Career items within ' . $theme_name);
	
	/**
	 * Archive settings
	 */
	$ebor_options->add_section('career_archive_section', 'Career Archive Settings', 15, $theme_name . ': Career Settings');
	$ebor_options->add_setting('input', 'stack_careers_title', 'Careers Archive Title', 'career_archive_section', 'Current Openings', 10);
	$ebor_options->add_setting('image', 'stack_career_breadcrumb_image', 'Career Titles Background Image', 'career_archive_section', '', 12);
	$ebor_options->add_setting('textarea', 'stack_careers_cta', 'Careers Call To Action Section', 'career_archive_section', $preset, 15);
	
	/**
	 * All Footer Settings
	 */
	$ebor_options->add_panel( $theme_name . ': Footer Settings', 245, 'All of the controls in this section directly relate to the control of footer design within ' . $theme_name);
	
	$ebor_options->add_section('footer_layout_section', 'Layout Settings', 10, $theme_name . ': Footer Settings', '<strong>Please Note:</strong> Your choice here will not affect every page if you installed our demo data. Some of our installed demo data pages, specifically the homepages, have a footer override set. Please watch <a href="https://www.youtube.com/watch?v=kQwOWbVY0K0">This Explanatory Video</a> for what this means, and how to use these settings.');
	$ebor_options->add_setting('select', 'footer_layout', 'Global Footer Layout', 'footer_layout_section', 'short-3', 20, $footer_options);
	$ebor_options->add_setting('image', 'stack_footer_background_image', 'Footer Background Image', 'footer_layout_section', '', 25);
	
	$ebor_options->add_section('sub_footer_section', 'Sub Footer Settings', 15, $theme_name . ': Footer Settings', '<code>NOTE:</code> For details on the "target" options for buttons <a href="https://www.w3schools.com/tags/att_a_target.asp" target="_blank">see here</a>');
	$ebor_options->add_setting('textarea', 'stack_footer_copyright', 'Footer Copyright Text', 'sub_footer_section', '<span class="type--fine-print"><a href="http://www.tommusrhodus.com">Stack Premium WordPress Theme by TommusRhodus</a></span>', 5);
	$ebor_options->add_setting('input', 'stack_footer_email', 'Footer Email Address', 'sub_footer_section', 'support@stack.io', 10);
	$ebor_options->add_setting('input', 'stack_footer_button_text', 'Footer Button Text', 'sub_footer_section', 'Contact Us', 15);
	$ebor_options->add_setting('input', 'stack_footer_button_url', 'Footer Button URL', 'sub_footer_section', '', 20);
	$ebor_options->add_setting('input', 'stack_footer_button_target', 'Footer Button Target', 'sub_footer_section', '', 25);
	
	$ebor_options->add_section('footer_section', 'Footer Settings', 10, $theme_name . ': Footer Settings');
	$ebor_options->add_setting('input', 'stack_footer_subscribe_shortcode', 'Add shortcode for subscribe footer contact form', 'footer_section', '', 17);
	
	$ebor_options->add_section('footer_social_settings_section', 'Footer Social Settings', 20, $theme_name . ': Footer Settings');
	
	//Footer Icons
	for( $i = 1; $i < 11; $i++ ){
		$ebor_options->add_setting('select', 'footer_social_icon_' . $i, 'Footer Social Icon ' . $i, 'footer_social_settings_section', 'none', 20 + $i + $i, $new_social_options);
		$ebor_options->add_setting('input', 'footer_social_url_' . $i, 'Footer Social URL ' . $i, 'footer_social_settings_section', '', 21 + $i + $i);
	}
	
	$ebor_options->add_section('footer_logo_section', 'Footer Logo Settings', 25, $theme_name . ': Footer Settings');
	$ebor_options->add_setting('image', 'stack_footer_logo_dark', 'Footer Dark Logo', 'footer_logo_section', '', 5);
	$ebor_options->add_setting('image', 'stack_footer_logo_light', 'Footer Light Logo', 'footer_logo_section', '', 10);
	
	//Colour Options
	$ebor_options->add_setting('color', 'color_primary', 'Primary Colour', 'colors', '#4A90E2', 5);
	$ebor_options->add_setting('color', 'color_primary_1', 'Primary Colour 1', 'colors', '#31639C', 10);
	$ebor_options->add_setting('color', 'color_primary_2', 'Primary Colour 2', 'colors', '#465773', 15);
	$ebor_options->add_setting('color', 'color_primary_3', 'Primary Colour 3', 'colors', '#4A90E2', 17);
	$ebor_options->add_setting('color', 'color_dark', 'Dark Colour', 'colors', '#252525', 20);
	$ebor_options->add_setting('color', 'color_bg_secondary', 'Secondary Colour', 'colors', '#FAFAFA', 25);
	$ebor_options->add_setting('color', 'color_on_dark', 'Primary Colour on Dark', 'colors', '#FFFFFF', 30);
	$ebor_options->add_setting('color', 'color_body', 'Body Text', 'colors', '#666666', 35);
	$ebor_options->add_setting('color', 'color_heading', 'Headings Text', 'colors', '#252525', 40);

	/**
	 * Instagram API Stuff
	 */
	$ebor_options->add_section('instagram_api_section', $theme_name . ': Instagram Settings', 340, false, '<code>IMPORTANT NOTE:</code> This is the Instagram setup section for the theme, it requires an Access Token and Client ID.<br /><br />Due to how Instagram have set their API you have to register as a developer with Instagram for this to work.<br /><br />For setup details, <a href="https://tommusrhodus.ticksy.com/article/7566" target="_blank">please read this</a>');
	$ebor_options->add_setting('input', 'instagram_token', 'Instagram Access Token', 'instagram_api_section', 'replaceWithYourOwn', 5);
	$ebor_options->add_setting('input', 'instagram_client', 'Instagram Client ID', 'instagram_api_section', 'replaceWithYourOwn', 10);
	
	//Advanced Styling
	$ebor_options->add_panel( $theme_name . ': Advanced Styling Settings', 207, 'All of the controls in this section directly relate to the styling page of ' . $theme_name);
	
	$ebor_options->add_section('advanced_site_section', 'Site', 5, $theme_name . ': Advanced Styling Settings', '');
	$ebor_options->add_setting('input', 'stack_site_width', 'Custom Site Max Width (1170px)', 'advanced_site_section', '', 3);
	
	//Advanced Type
	$ebor_options->add_section('advanced_font_section', 'Typography', 10, $theme_name . ': Advanced Styling Settings', 'For details how to use these advanced options, please see <a target="_blank" href="https://www.youtube.com/watch?v=xExtiU9oiPc">this video</a>');
	$ebor_options->add_setting('input', 'stack_body_font', 'Body Font Name (Open Sans Default)', 'advanced_font_section', 'Open Sans', 3);
	$ebor_options->add_setting('input', 'stack_heading_font', 'Heading Font Name (Open Sans Default)', 'advanced_font_section', 'Open Sans', 5);
	$ebor_options->add_setting('input', 'stack_button_font', 'Button Font Name (Open Sans Default)', 'advanced_font_section', 'Open Sans', 10);
	$ebor_options->add_setting('input', 'stack_nav_font', 'Navigation Font Name (Open Sans Default)', 'advanced_font_section', 'Open Sans', 15);
	$ebor_options->add_setting('input', 'stack_heading_weight', 'Heading Font Weight (300 Default)', 'advanced_font_section', '300', 20);
	$ebor_options->add_setting('input', 'stack_body_weight', 'Body Font Weight (400 Default)', 'advanced_font_section', '400', 25);
	$ebor_options->add_setting('input', 'stack_strong_weight', 'Bold Text Font Weight (600 Default)', 'advanced_font_section', '600', 30);
	$ebor_options->add_setting('input', 'stack_google_font_string', 'Google Font URL Loading Parameters (Open Sans:200,300,400,400i,500,600,700|Merriweather:300,300i|Material Icons - Default)', 'advanced_font_section', 'Open Sans:200,300,400,400i,500,600,700|Merriweather:300,300i|Material Icons', 35);
	
	$ebor_options->add_section('advanced_type_section', 'Typography Sizes', 10, $theme_name . ': Advanced Styling Settings');
	$ebor_options->add_setting('input', 'stack_base_size', 'Base Font Size (14 Default)', 'advanced_type_section', '14', 1);
	$ebor_options->add_setting('input', 'stack_base_size_mobile', 'Base Font Size Mobile (13 Default)', 'advanced_type_section', '13', 2);
	$ebor_options->add_setting('input', 'stack_h1_size', 'h1 Heading Size (3.14285714285714em Default)', 'advanced_type_section', '3.14285714285714em', 5);
	$ebor_options->add_setting('input', 'stack_h2_size', 'h2 Heading Size (2.35714285714286em Default)', 'advanced_type_section', '2.35714285714286em', 10);
	$ebor_options->add_setting('input', 'stack_h3_size', 'h3 Heading Size (1.78571428571429em Default)', 'advanced_type_section', '1.78571428571429em', 15);
	$ebor_options->add_setting('input', 'stack_h4_size', 'h4 Heading Size (1.35714285714286em Default)', 'advanced_type_section', '1.35714285714286em', 20);
	$ebor_options->add_setting('input', 'stack_h5_size', 'h5 Heading Size (1em Default)', 'advanced_type_section', '1em', 25);
	$ebor_options->add_setting('input', 'stack_h6_size', 'h6 Heading Size (0.85714285714286em Default)', 'advanced_type_section', '0.85714285714286em', 30);
	$ebor_options->add_setting('input', 'stack_p_lead_size', 'p.lead Size (1.35714285714286em Default)', 'advanced_type_section', '1.35714285714286em', 32);
	$ebor_options->add_setting('input', 'stack_base_line_height', 'Base Line Height (1.85714285714286em Default)', 'advanced_type_section', '1.85714285714286em', 35);
	$ebor_options->add_setting('input', 'stack_h1_line_height', 'h1 Heading Line Height (1.31818181818182em Default)', 'advanced_type_section', '1.31818181818182em', 40);
	$ebor_options->add_setting('input', 'stack_h2_line_height', 'h2 Heading Line Height (1.36363636363636em Default)', 'advanced_type_section', '1.36363636363636em', 45);
	$ebor_options->add_setting('input', 'stack_h3_line_height', 'h3 Heading Line Height (1.5em Default)', 'advanced_type_section', '1.5em', 50);
	$ebor_options->add_setting('input', 'stack_h4_line_height', 'h4 Heading Line Height (1.68421052631579em Default)', 'advanced_type_section', '1.68421052631579em', 55);
	$ebor_options->add_setting('input', 'stack_h6_line_height', 'h6 Heading Line Height (2.16666666666667em Default)', 'advanced_type_section', '2.16666666666667em', 60);
	$ebor_options->add_setting('input', 'stack_p_lead_line_height', 'p.lead Line Height (1.68421052631579em Default)', 'advanced_type_section', '1.68421052631579em', 65);
	$ebor_options->add_setting('input', 'stack_fine_size', 'Fine Print Size (0.85714285714286em Default)', 'advanced_type_section', '0.85714285714286em', 70);
	$ebor_options->add_setting('input', 'stack_ul_line_height', 'UL/OL Line Height (2.34375em Default)', 'advanced_type_section', '2.34375em', 75);
	
	$ebor_options->add_section('advanced_button_section', 'Buttons', 15, $theme_name . ': Advanced Styling Settings');
	$ebor_options->add_setting('input', 'stack_button_border_radius', 'Button Border Radius (6px Default)', 'advanced_button_section', '6px', 5);
	$ebor_options->add_setting('input', 'stack_button_border_width', 'Button Border Width (1px Default)', 'advanced_button_section', '1px', 10);
	$ebor_options->add_setting('input', 'stack_button_font_weight', 'Button Font Weight(700 Default)', 'advanced_button_section', '700', 15);
	
	/*
	$ebor_options->add_section('advanced_nav_section', 'Navigation Items', 20, $theme_name . ': Advanced Styling Settings');
	$ebor_options->add_setting('input', 'stack_nav_font_size', 'Nav Item Font Size (11px Default)', 'advanced_nav_section', '11px', 5);
	$ebor_options->add_setting('input', 'stack_nav_font_weight', 'Nav Item Font Weight (400 Default)', 'advanced_nav_section', '400', 10);
	$ebor_options->add_setting('input', 'stack_nav_letter_spacing', 'Nav Item Letter Spacing (0px Default)', 'advanced_nav_section', '0px', 15);
	*/
	
	$ebor_options->add_section('advanced_forms_section', 'Forms', 25, $theme_name . ': Advanced Styling Settings');
	$ebor_options->add_setting('input', 'stack_label_font_size', 'Label Font Size (1.14285714285714em Default)', 'advanced_forms_section', ' 1.14285714285714em', 5);
	$ebor_options->add_setting('input', 'stack_label_font_weight', 'Label Font Weight (400 Default)', 'advanced_forms_section', '400', 10);
	$ebor_options->add_setting('input', 'stack_input_border_radius', 'Input Border Radius (6px Default)', 'advanced_forms_section', '6px', 15);
	$ebor_options->add_setting('input', 'stack_input_font_size', 'Input Font Size (1.14285714285714em Default)', 'advanced_forms_section', '1.14285714285714em', 25);
	$ebor_options->add_setting('input', 'stack_checkbox_border_radius', 'Checkbox Border Radius (6px Default)', 'advanced_forms_section', '6px', 30);
	$ebor_options->add_setting('input', 'stack_checkbox_padding', 'Checkbox Padding (0 Default)', 'advanced_forms_section', '0', 35);
	$ebor_options->add_setting('input', 'stack_radio_border_radius', 'Radio Border Radius (50% Default)', 'advanced_forms_section', '50%', 40);
	$ebor_options->add_setting('input', 'stack_radio_padding', 'Radio Padding (0 Default)', 'advanced_forms_section', '0', 45);
	
	$ebor_options->add_section('advanced_nav_section', 'Menu Items', 30, $theme_name . ': Advanced Styling Settings', 'For details how to use these advanced options, please see <a target="_blank" href="https://www.youtube.com/watch?v=xExtiU9oiPc">this video</a>');
	$ebor_options->add_setting('input', 'stack_nav_opacity', 'Menu Items Opacity (0 to 1 in decimal)(0.5 default)', 'advanced_nav_section', '0.5', 5);
	$ebor_options->add_setting('input', 'stack_sub_nav_opacity', 'Sub Menu Items Opacity (0 to 1 in decimal)(0.75 default)', 'advanced_nav_section', '0.75', 10);
	
	$ebor_options->add_panel( $theme_name . ': Visual Composer Settings', 1095, 'All of the controls in this section directly relate to the control of visual composer within ' . $theme_name);
	
	//Visual Composer 
	$ebor_options->add_section('visual_composer_section', 'Visual Composer Settings', 10, $theme_name . ': Visual Composer Settings');
	
	/**
	 * Settings (The Actual Options)
	 * Repeated settings are stepped using a for() loop and counter
	 * 
	 * add_setting($type, $option, $title, $section, $default, $priority, $select_options)
	 * 
	 * @since 1.0.0
	 * @author tommusrhodus
	 */
	//Visual Composer
	$ebor_options->add_setting('select', 'stack_vc_redirect_post', 'Redirect standard template for posts?', 'visual_composer_section', 'yes', 5, array('yes' => 'Yes', 'no' => 'No'));
	$ebor_options->add_setting('select', 'stack_vc_redirect_portfolio', 'Redirect standard template for portfolio posts?', 'visual_composer_section', 'yes', 10, array('yes' => 'Yes', 'no' => 'No')); 
	$ebor_options->add_setting('select', 'stack_vc_redirect_team', 'Redirect standard template for team posts?', 'visual_composer_section', 'yes', 15, array('yes' => 'Yes', 'no' => 'No'));
	$ebor_options->add_setting('select', 'stack_vc_redirect_page', 'Redirect standard template for pages?', 'visual_composer_section', 'yes', 20, array('yes' => 'Yes', 'no' => 'No'));
	$ebor_options->add_setting('select', 'stack_vc_redirect_product', 'Redirect standard template for products?', 'visual_composer_section', 'yes', 25, array('yes' => 'Yes', 'no' => 'No'));
	$ebor_options->add_setting('select', 'stack_vc_redirect_career', 'Redirect standard template for careers?', 'visual_composer_section', 'yes', 30, array('yes' => 'Yes', 'no' => 'No'));
	
}