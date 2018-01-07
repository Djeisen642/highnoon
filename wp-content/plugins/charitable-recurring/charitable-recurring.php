<?php
/**
 * Plugin Name: Charitable - Recurring Donations
 * Plugin URI: https://www.wpcharitable.com/extensions/charitable-recurring-donations/
 * Description: Accept recurring donations
 * Version: 1.0.7
 * Author: 	Kathy Darling
 * Author URI: https://www.kathyisawesome.com
 * Requires at least: 4.6
 * Tested up to: 4.8.2
 *
 * Text Domain: 		charitable-recurring
 * Domain Path: 		/languages/
 *
 * @package 			Charitable Recurring
 * @category 			Core
 * @author 				Kathy Darling
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Load plugin class, but only if Charitable is found and activated.
 *
 * @return 	void
 * @since 	1.0.0
 */
function charitable_recurring_load() {	
	require_once( 'includes/class-charitable-recurring.php' );

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
	else {

		new Charitable_Recurring( __FILE__ );

	}	
}

add_action( 'charitable_addons_start', 'charitable_recurring_load' );
