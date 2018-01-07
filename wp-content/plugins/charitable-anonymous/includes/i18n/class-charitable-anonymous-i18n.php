<?php
/**
 * Sets up translations for Charitable Anonymous.
 *
 * @package   Charitable/Classes/Charitable_i18n
 * @version   1.1.2
 * @author    Eric Daams
 * @copyright Copyright (c) 2017, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Anonymous_i18n' ) ) : 

/**
 * Charitable_Anonymous_i18n
 *
 * @since       1.1.2
 */
class Charitable_Anonymous_i18n extends Charitable_i18n {

    /**
     * The single instance of this class.  
     *
     * @var     Charitable_Anonymous_i18n|null
     * @access  private
     * @static
     */
    private static $instance = null;    

    /**
     * @var     string
     */
    protected $textdomain = 'charitable-anonymous';

    /**
     * Returns and/or create the single instance of this class.  
     *
     * @return  Charitable_Anonymous_i18n
     * @access  public
     * @since   1.1.0
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Charitable_Anonymous_i18n();
        }

        return self::$instance;
    }

    /**
     * Set up the class. 
     *
     * @access  protected
     * @since   1.0.0
     */
    protected function __construct() {
        $this->languages_directory = apply_filters( 'charitable_anonymous_languages_directory', 'charitable-anonymous/languages' );
        $this->locale = apply_filters( 'plugin_locale', get_locale(), $this->textdomain );
        $this->mofile = sprintf( '%1$s-%2$s.mo', $this->textdomain, $this->locale );

        $this->load_textdomain();
    }
}

endif;