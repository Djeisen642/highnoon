<?php
/**
 * This class is responsible for adding & saving the anonymous donors fields to the donors widget.
 *
 * @package   Charitable Anonymous/Classes/Charitable_Anonymous_Donors_Widget
 * @version   1.0.0
 * @author    Eric Daams
 * @copyright Copyright (c) 2017, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Anonymous_Donors_Widget' ) ) : 

/**
 * Charitable_Anonymous_Donors_Widget
 *
 * @since       1.0.0
 */
class Charitable_Anonymous_Donors_Widget {

    /**
     * @var     Charitable_Anonymous_Donors_Widget
     * @access  private
     * @static
     * @since   1.1.0
     */
    private static $instance = null;

    /**
     * Create class object. Private constructor. 
     * 
     * @access  private
     * @since   1.1.0
     */
    private function __construct() {
    }

    /**
     * Create and return the class object.
     *
     * @access  public
     * @static
     * @since   1.1.0
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Charitable_Anonymous_Donors_Widget();            
        }

        return self::$instance;
    }

    /**
     * Add the anonymous donors setting to the end of the widget.
     *
     * @param   mixed[] $args
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function add_anonymous_donors_fields( $args, $widget ) {
        ?>
        <p>
            <input id="<?php echo esc_attr( $widget->get_field_id( 'hide_anonymous_donors' ) ) ?>" type="checkbox" name="<?php echo esc_attr( $widget->get_field_name( 'hide_anonymous_donors' ) ); ?>" <?php checked( $args['hide_anonymous_donors'] ) ?>>
            <label for="<?php echo esc_attr( $widget->get_field_id( 'hide_anonymous_donors' ) ) ?>"><?php _e( 'Hide anonymous donors', 'charitable-anonymous' ) ?></label>
        </p>         
        <?php
    }

    /**
     * The value for anonymous_donation should always be either 1 or 0.  
     *
     * @param   mixed[] $instance
     * @param   mixed[] $new_instance
     * @return  mixed[] $instance
     * @access  public
     * @since   1.0.0
     */
    public function save_anonymous_donors_fields( $instance, $new_instance ) {
        $instance['hide_anonymous_donors'] = isset( $new_instance['hide_anonymous_donors'] ) && $new_instance['hide_anonymous_donors'];
        return $instance;
    }

    /**
     * Set default value for anonymous_donors field. 
     *
     * @param   mixed[] $default
     * @return  mixed[]
     * @access  public
     * @since   1.0.0
     */
    public function anonymous_donors_fields_default_args( $default ) {
        $default['hide_anonymous_donors'] = 0;
        return $default;
    }

    /**
     * Modify the query args that are sent to the Charitable_Donor_Query. 
     *
     * @param   mixed[] $query_args
     * @param   mixed[] $instance
     * @return  mixed[] 
     * @access  public
     * @since   1.0.0
     */
    public function anonymous_donors_query_args( $query_args, $instance ) {
        if ( $instance['hide_anonymous_donors'] ) {
            $query_args['exclude_anonymous'] = $instance['hide_anonymous_donors'];
        }
        
        return $query_args;
    }
}

endif;
