<?php
/**
 * Plugin Name:       Charitable - Anonymous Donations
 * Plugin URI:        https://www.wpcharitable.com/extensions/charitable-anonymous-donations/
 * Description:       Allow your supporters to donate anonymously.
 * Version:           1.2.1
 * Author:            WP Charitable
 * Author URI:        https://www.wpcharitable.com
 * Requires at least: 4.2
 * Tested up to:      4.9.1
 *
 * Text Domain:       charitable-anonymous
 * Domain Path:       /languages/
 *
 * @package  Charitable Anonymous
 * @category Core
 * @author   Studio164a
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/**
 * Load plugin class, but only if Charitable is found and activated.
 *
 * @since  1.0.0
 * @since  1.2.0 Returns a boolean instead of void.
 *
 * @return boolean Whether the extension has been loaded.
 */
function charitable_anonymous_load() {	
	require_once( 'includes/class-charitable-anonymous.php' );

	$has_dependencies = true;

	/* Check for Charitable */
	if ( ! class_exists( 'Charitable' ) ) {
		if ( ! class_exists( 'Charitable_Extension_Activation' ) ) {
			require_once 'includes/class-charitable-extension-activation.php';
		}

		$activation = new Charitable_Extension_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
		$activation = $activation->run();

		$has_dependencies = false;
	} 
	
	if ( $has_dependencies ) {
		new Charitable_Anonymous( __FILE__ );
	}

	return $has_dependencies;
}

add_action( 'plugins_loaded', 'charitable_anonymous_load', 1 );
