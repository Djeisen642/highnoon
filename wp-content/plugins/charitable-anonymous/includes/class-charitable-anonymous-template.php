<?php
/**
 * Charitable Anonymous template
 *
 * @version   1.0.0
 * @package   Charitable Anonymous/Classes/Charitable_Anonymous_Template
 * @author    Eric Daams
 * @copyright Copyright (c) 2017, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! class_exists( 'Charitable_Anonymous_Template' ) ) : 

/**
 * Charitable_Anonymous_Template
 *
 * @since       1.0.0
 */
class Charitable_Anonymous_Template extends Charitable_Template {
    
    /**
     * Set theme template path. 
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_theme_template_path() {
        return trailingslashit( apply_filters( 'charitable_anonymous_theme_template_path', 'charitable/charitable-anonymous' ) );
    }

    /**
     * Return the base template path.
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_base_template_path() {
        return charitable_anonymous()->get_path( 'templates' );
    }
}

endif;