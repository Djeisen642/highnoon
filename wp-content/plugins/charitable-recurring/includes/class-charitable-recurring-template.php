<?php
/**
 * Charitable Recurring template
 *
 * @version     1.0.0
 * @package     Charitable Recurring/Classes/Charitable_Recurring_Template
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Recurring_Template' ) ) : 

/**
 * Charitable_Recurring_Template
 *
 * @since       1.0.0
 */
class Charitable_Recurring_Template extends Charitable_Template {

    /**
     * Class constructor. 
     *
     * @param   string|array $template_name     A single template name or an ordered array of template
     * @param   bool        $load               If true the template file will be loaded if it is found.
     * @param   bool        $require_once       Whether to require_once or require. Default true. Has no effect if $load is false.
     * @param   string      $base_template_path allow plugins to call templates that can be overriden by themes
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function __construct( $template_name, $load = true, $require_once = true ) {
        parent::__construct( $template_name, $load, $require_once );    
    }

    
    /**
     * Return the base template path.
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_base_template_path() {
        return charitable_recurring()->get_path( 'templates' );
    }
}

endif;