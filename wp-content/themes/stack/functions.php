<?php 

/**
 * Define theme folder URL, saves querying the template directory multiple times.
 */
define('EBOR_THEME_DIRECTORY', esc_url(trailingslashit( get_template_directory_uri() )));
define('EBOR_REQUIRE_DIRECTORY', trailingslashit( get_template_directory() ));

/**
 * Ebor Framework
 * Queue Up Theme-Side Framework, everything else is loaded in the ebor-framework plugin.
 * 
 * You can install a child theme to modify all aspects of the theme, if you need to modify anything from the /admin/ folder
 * Just delete the require line below and move it to the functions.php of your child theme, make sure to copy over the entire /admin/ folder too.
 * It's very rare you'd need to do that, but if you do, you'll need to delete this require on every theme update.
 * 
 * Note that to override a function from the /admin/ folder, you don't need to copy the folder to your child theme, every function is wrapped
 * in a conditional so that it can be called directly from your child theme and ignored in the parent theme.
 * 
 * @since 1.0.0
 * @author TommusRhodus
 */
require_once( EBOR_REQUIRE_DIRECTORY . 'admin/theme_menus_widgets.php' );
require_once( EBOR_REQUIRE_DIRECTORY . 'admin/theme_layouts.php' );
require_once( EBOR_REQUIRE_DIRECTORY . 'admin/theme_functions.php' );
require_once( EBOR_REQUIRE_DIRECTORY . 'admin/theme_scripts.php' );
require_once( EBOR_REQUIRE_DIRECTORY . 'admin/theme_filters.php' );
require_once( EBOR_REQUIRE_DIRECTORY . 'admin/theme_support.php' );
require_once( EBOR_REQUIRE_DIRECTORY . 'admin/theme_options.php' );	

/**
 * Some parts of the framework only need to run on admin views.
 * These would be those.
 * Calling these only on admin saves some operation time for the theme, everything in the name of speed.
 */
if( is_admin() ){
	if (!( class_exists( 'TGM_Plugin_Activation' ) ))
		require_once( EBOR_REQUIRE_DIRECTORY . 'admin/class-tgm-plugin-activation.php' );
		
	require_once( EBOR_REQUIRE_DIRECTORY . 'admin/theme_metaboxes.php' );
}

/**
 * If visual composer is installed, grab all required files.
 * Wrapped in an if statement so that we can save parsing this if visual composer is not used.
 * It's a speed boost basically.
 */
if( function_exists('vc_set_as_theme') ){
	require_once( EBOR_REQUIRE_DIRECTORY . "vc_init.php" );
}

if( class_exists('woocommerce') ){
	require_once( EBOR_REQUIRE_DIRECTORY . "woocommerce_init.php" );
}

if( function_exists('variant_page_builder_init') ){
	require_once( EBOR_REQUIRE_DIRECTORY . "variant_init.php" );
}