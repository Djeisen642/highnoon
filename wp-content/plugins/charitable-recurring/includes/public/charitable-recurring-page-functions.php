<?php 
/**
 * Charitable Page Functions. 
 * 
 * @package 	Charitable/Functions/Page
 * @version     1.0.0
 * @author 		Kathy Darling
 * @copyright 	Copyright (c) 2016, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Displays a template. 
 *
 * @param 	string|string[] $template_name A single template name or an ordered array of template.
 * @param 	mixed[] $args 				   Optional array of arguments to pass to the view.
 * @return 	Charitable_Template
 * @since 	1.0.0
 */
function charitable_recurring_template( $template_name, array $args = array() ) {
	if ( empty( $args ) ) {
		$template = new Charitable_Recurring_Template( $template_name ); 
	}
	else {
		$template = new Charitable_Recurring_Template( $template_name, false ); 
		$template->set_view_args( $args );
		$template->render();
	}
	return $template;
}
