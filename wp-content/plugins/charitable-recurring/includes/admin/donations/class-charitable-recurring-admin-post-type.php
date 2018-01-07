<?php
/**
 * The class that defines how donations are managed on the admin side.
 * 
 * @package     Charitable_Recurring/Classes/Charitable_Recurring_Admin_Post_Type
 * @version     1.0.0
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; 

if ( ! class_exists( 'Charitable_Recurring_Admin_Post_Type' ) ) :

/**
 * Charitable_Recurring_Admin_Post_Type class.
 *
 * @final
 * @since       1.0.0
 */
final class Charitable_Recurring_Admin_Post_Type {

    /**
     * The single instance of this class.  
     *
     * @var     Charitable_Recurring_Admin_Post_Type|null
     * @access  private
     * @static
     */
    private static $instance = null;


    /**
     * The single instance of this class.  
     *
     * @var     Charitable_Recurring::POST_TYPE
     * @access  private
     * @static
     */
    private static $post_type = Charitable_Recurring::POST_TYPE;

    /**
     * Get the approved statuses once 
     *
     * @var     Charitable_Recurring_Admin_Post_Type|null
     * @access  private
     */
    private $valid_statuses = null;


    /**
     * Create object instance. 
     *
     * @access  public
     * @since   1.0.0
     */
    public function __construct() {
        $this->meta_box_helper = new Charitable_Meta_Box_Helper( 'charitable-recuring_donation' );
        do_action( 'charitable_recurring_admin_donation_post_type_start', $this );
    }


    /**
     * Returns and/or create the single instance of this class.  
     *
     * @return  Charitable_Recurring_Admin_Post_Type
     * @access  public
     * @since   1.0.0
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Charitable_Recurring_Admin_Post_Type();
        }

        return self::$instance;
    } 


    /**
     * Returns and/or create the valid donation statuses  
     *
     * @return  Charitable_Recurring_Admin_Post_Type
     * @access  public
     * @since   1.0.0
     */
    public function get_valid_statuses() {
        if ( is_null( $this->valid_statuses ) ) {
            $this->valid_statuses = charitable_recurring_get_valid_donation_statuses();
        }

        return $this->valid_statuses;
    } 


    /**
     * Sets up the meta boxes to display on the donation admin page.     
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function add_meta_boxes() {    
        foreach ( $this->get_meta_boxes() as $meta_box_id => $meta_box ) {
            add_meta_box( 
                $meta_box_id, 
                $meta_box['title'], 
                array( $this->meta_box_helper, 'metabox_display' ), 
                self::$post_type, 
                $meta_box['context'], 
                $meta_box['priority'], 
                $meta_box
            );
        }
    }

    /**
     * Remove default meta boxes.   
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function remove_meta_boxes() {
        global $wp_meta_boxes;

        $charitable_meta_boxes = $this->get_meta_boxes();
        
        foreach ( $wp_meta_boxes[ self::$post_type ] as $context => $priorities ) {
            foreach ( $priorities as $priority => $meta_boxes ) {
                foreach ( $meta_boxes as $meta_box_id => $meta_box ) {
                    if ( ! isset( $charitable_meta_boxes[ $meta_box_id ] ) ) {                        
                        remove_meta_box( $meta_box_id, self::$post_type, $context );
                    }
                }                
            }
        }
    }
    

    /**
     * Returns an array of all meta boxes added to the donation post type screen. 
     *
     * @return  array
     * @access  private
     * @since   1.0.0
     */
    private function get_meta_boxes() {
        $meta_boxes = array(
            'donation-overview'  => array( 
                'title'         => __( 'Donation Overview', 'charitable-recurring' ), 
                'context'       => 'normal', 
                'priority'      => 'high', 
                'view'          => 'metaboxes/donation/recurring-donation-overview'
            ),             
            'donation-details'     => array(
                'title'         => __( 'Donation Details', 'charitable-recurring' ), 
                'context'       => 'side',
                'priority'      => 'high',
                'view'          => 'metaboxes/donation/donation-details'
            ), 
            'donation-log'      => array(
                'title'         => __( 'Donation Log', 'charitable-recurring' ), 
                'context'       => 'normal',
                'priority'      => 'low',
                'view'          => 'metaboxes/donation/donation-log'
            ), 
        );

        return apply_filters( 'charitable_recurring_donation_meta_boxes', $meta_boxes );  
    }


    /**
     * Change messages when a post type is updated.
     * @param  array $messages
     * @return array
     */
    public function post_messages( $messages ) {
        global $post, $post_ID;
        $messages[ Charitable_Recurring::POST_TYPE ] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf( __( 'Recurring Donation updated. <a href="%s">View Recurring Donation</a>', 'charitable-recurring' ), esc_url( get_permalink( $post_ID ) ) ),
            2 => __( 'Custom field updated.', 'charitable-recurring' ),
            3 => __( 'Custom field deleted.', 'charitable-recurring' ),
            4 => __( 'Recurring Donation updated.', 'charitable-recurring' ),
            5 => isset( $_GET['revision'] ) ? sprintf( __( 'Recurring Donation restored to revision from %s', 'charitable-recurring' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6 => sprintf( __( 'Recurring Donation published. <a href="%s">View Recurring Donation</a>', 'charitable-recurring' ), esc_url( get_permalink( $post_ID ) ) ),
            7 => __( 'Recurring Donation saved.', 'charitable-recurring' ),
            8 => sprintf( __( 'Recurring Donation submitted. <a target="_blank" href="%s">Preview Recurring Donation</a>', 'charitable-recurring' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
            9 => sprintf( __( 'Recurring Donation scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Recurring Donation</a>', 'charitable-recurring' ),
              date_i18n( __( 'M j, Y @ G:i', 'charitable-recurring' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
            10 => sprintf( __( 'Recurring Donation draft updated. <a target="_blank" href="%s">Preview Recurring Donation</a>', 'charitable-recurring' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) )
            );

        return $messages;
    }

    /**
     * Customize donations columns.  
     *
     * @see     get_column_headers
     *
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public function dashboard_columns( $column_names ) {

        $column_names = apply_filters( 'charitable_recurring_donation_dashboard_column_names', array(
            'cb'                => '<input type="checkbox"/>',
            'id'                => __( 'Recurring Donation', 'charitable-recurring' ),          
            'amount'            => __( 'Amount Donated', 'charitable-recurring' ), 
            'campaigns'         => __( 'Campaign(s)', 'charitable-recurring' ),           
            'donation_date'     => __( 'Date', 'charitable-recurring' ),  
            'donations'     => __( 'Related Donations', 'charitable-recurring' ),  
            'post_status'            => __( 'Status', 'charitable-recurring' ),
        ) );

        return $column_names;
    }

    /**
     * Add information to the dashboard donations table listing.
     *
     * @see     WP_Posts_List_Table::single_row()
     * 
     * @param   string  $column_name    The name of the column to display.
     * @param   int     $post_id        The current post ID.
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function dashboard_column_item( $column_name, $post_id ) {       
            
        $donation = $this->get_donation( $post_id );
        
        switch ( $column_name ) {
            case 'id' :
               $display = sprintf( '<a href="%s" title="%s">%s</a>', 
                   esc_url( add_query_arg( array( 'post' => $donation->get_donation_id(), 'action' => 'edit' ), admin_url( 'post.php' ) ) ), 
                   __( 'View Donation Details', 'charitable-recurring' ),
                    sprintf( _x( '#%d', 'number symbol', 'charitable-recurring' ), $donation->get_donation_id() ) );
                if( $name = $donation->get_donor()->get_name() ) {
                    $display .= sprintf( _x( ' by %s', 'charitable-recurring', 'Donation by donor name' ), $name );
                }
                break;
            case 'post_status' : 
                $display = '<mark class="status '. $donation->get_status() .'">'. strtolower( $donation->get_status( true ) ) . '</mark>';
                break;
            case 'amount' : 
                $display = $donation->get_recurring_donation_amount(true);
                $display .= '<span class="meta">' . sprintf( _x( 'via %s', 'charitable-recurring' ), $donation->get_gateway_label() ). '</span>';
                break;          
            case 'campaigns' : 
                $donations = $donation->get_campaign_donations();
                $total = count( $donations );
                $display = '';
                $i = 1;
                foreach( $donations as $d ){
                    $display .= sprintf( '<a href="edit.php?post_type=%s&campaign_id=%s">%s</a>', 
                        self::$post_type,
                        $d['campaign_id'],
                        get_the_title( $d['campaign_id'] ) );
                    if( $i != $total ){
                        $display .= ', ';
                    }
                    $i++;
                }
                break;
            case 'donation_date' :              
                $display = $donation->get_date(); 
                break;
            case 'donations' :    
                $display = sprintf( '<a href="%s">%s</a>',
                    admin_url( 'edit.php?post_status=all&post_type=' . Charitable::DONATION_POST_TYPE . '&_recurring_related_donations=' . absint( $donation->get_donation_id() ) ),
                        count( $donation->get_related_donations() )
                    );          
                break;
            default :
                $display = '';
                break;
        }
        echo apply_filters( 'charitable_recurring_donation_column_display', $display, $column_name, $post_id, $donation );
    }   

    /**
     * Returns the donation object. Caches the object to avoid re-creating this for each column.
     *
     * @return  Charitable_Donation
     * @access  private
     * @since   1.0.0
     */
    private function get_donation( $post_id ) {
        $donation = wp_cache_get( $post_id, 'charitable_donation' );

        if ( false === $donation ) {

            $donation = charitable_get_donation( $post_id );

            wp_cache_set( $post_id, $donation, 'charitable_donation' );

        }

        return $donation;
    }
  

    /**
     * Make columns sortable
     * 
     * @param   array  $columns  .
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public function sortable_columns( $columns ) {
        $sortable_columns = array(
            'id'       => 'ID',
            'amount'   => 'amount',
            'donation_date' => 'date'
        );

        return wp_parse_args( $sortable_columns, $columns );          
    }


   /**
     * Set list table primary column for products and orders.
     * Support for WordPress 4.3.
     *
     * @param  string $default
     * @param  string $screen_id
     * @return string
     * @since  1.0.0
     */
    public function primary_column( $default, $screen_id ) {

        if ( 'edit-' . self::$post_type === $screen_id ) {
            return 'id';
        }

        return $default;
    }


    /**
     * Set row actions for products and orders.
     *
     * @param  array $actions
     * @param  WP_Post $post
     * @return array
     * @since  1.0.0
     */
    public function row_actions( $actions, $post ) {

        if ( self::$post_type === $post->post_type ) {
            if ( isset( $actions['inline hide-if-no-js'] ) ) {
                unset( $actions['inline hide-if-no-js'] );
            }

            if ( isset( $actions['edit'] ) ) {

                $title  = esc_attr__( 'View Details', 'charitable-recurring' );
                $text   = __( 'View', 'charitable-recurring' );
                $url    = esc_url( add_query_arg( array(
                    'post' => $post->ID,
                    'action' => 'edit',
                ), admin_url( 'post.php' ) ) );

                $actions['edit'] = sprintf( '<a href="%s" title="%s">%s</a>', $url, $title, $text );

            }
        }

        return $actions;
    }


    /**
     * Customize the output of the status views.
     *
     * @param   string[] $views
     * @return  string[]
     * @access  public
     * @since   1.4.0
     */
    public function set_status_views( $views ) {

        $counts = (array) $this->get_status_counts();
        
        $current = isset( $_GET["post_status"] ) ? $_GET["post_status"] : "";

        foreach ( $this->get_valid_statuses() as $key => $label ) {

            $views[ $key ] = sprintf( '<a href="%s"%s>%s <span class="count">(%d)</span></a>', 
                add_query_arg( array( 'post_status' => $key, 'paged' => FALSE ) ),
                $current === $key ? ' class="current"' : '', 
                $label,
                isset( $counts[ $key ] ) ? $counts[ $key ] : '0'
            );

        }

        $views['all'] = sprintf( '<a href="%s"%s>%s <span class="count">(%d)</span></a>',
            remove_query_arg( array( 'post_status', 'paged' ) ),
            'all' === $current || '' === $current ? ' class="current"' : '',
            __( 'All', 'charitable-recurring' ), 
            array_sum( $counts ) 
        );

        unset( $views['mine'] );

        return $views;
    }


    /**
     * Remove edit from the bulk actions.
     *
     * @param   array $actions
     * @return  array
     * @since   1.4.0
     */
    public function remove_bulk_actions( $actions ) {
        if ( isset( $actions['edit'] ) ) {
            unset( $actions['edit'] );
        }

        return $actions;
    }

    /**
     * Retrieve the bulk actions
     *
     * @return  array $actions Array of the bulk actions
     * @access  public
     * @since   1.0.0
     */
    public function get_bulk_actions() {
        $actions = array();

        foreach ( $this->get_valid_statuses() as $status_key => $label ) {
            $actions[ 'set-' . $status_key ] = sprintf( _x( 'Set to %s', 'set donation status to x', 'charitable-recurring' ), $label );
        }

        return apply_filters( 'charitable_donations_table_bulk_actions', $actions );
    }

    /**
     * Add extra bulk action options to mark orders as complete or processing.
     *
     * Using Javascript until WordPress core fixes: https://core.trac.wordpress.org/ticket/16031
     *
     * @global  string $post_type
     * @return  void
     * @access  public
     * @since   1.4.0
     */
    public function bulk_admin_footer() {
        global $post_type;

        if ( self::$post_type == $post_type ) {
            ?>
            <script type="text/javascript">
            (function($) { 

                <?php
                foreach ( $this->get_bulk_actions() as $status_key => $label ) {
                    printf( "jQuery('<option>').val('%s').text('%s').appendTo( [ '#bulk-action-selector-top', '#bulk-action-selector-bottom' ] );", $status_key, $label );
                }
                ?>

                
            })(jQuery);
            </script>
            <?php
        }
    }

    /**
     * Process the new bulk actions for changing order status.
     *
     * @return  void
     * @access  public
     * @since   1.4.0
     */
    public function process_bulk_action() {

        // We only want to deal with donations. In case any other CPTs have an 'active' action
        if ( ! isset( $_REQUEST['post_type'] ) || self::$post_type !== $_REQUEST['post_type'] || ! isset( $_REQUEST['post'] ) ) {
            return;
        }

        check_admin_referer( 'bulk-posts' );

        // get the action
        $action = '';

        if ( isset( $_REQUEST['action'] ) && -1 != $_REQUEST['action'] ) {
            $action = $_REQUEST['action'];
        } else if ( isset( $_REQUEST['action2'] ) && -1 != $_REQUEST['action2'] ) {
            $action = $_REQUEST['action2'];
        }

        // Bail out if this is not a status-changing action
        if ( strpos( $action, 'set-' ) === false ) {
            $sendback = remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'locked', 'ids' ), wp_get_referer() );
            wp_redirect( esc_url_raw( $sendback ) );
        }

        $donation_statuses = $this->get_valid_statuses();

        $new_status    = str_replace( 'set-', '', $action ); // get the status name from action

        $report_action = 'marked_' . $new_status;

        // Sanity check: bail out if this is actually not a status, or is
        // not a registered status
        if ( ! isset( $donation_statuses[ $new_status ] ) ) {
            return;
        }

        $changed = 0;

        $post_ids = array_map( 'absint', (array) $_REQUEST['post'] );

        foreach ( $post_ids as $post_id ) {

            $donation = charitable_get_donation( $post_id );
            $donation->update_status( $new_status );
            do_action( 'charitable_donations_table_do_bulk_action', $post_id, $new_status );
            $changed++;

        }

        $sendback = add_query_arg( array( 'post_type' => self::$post_type, $report_action => true, 'changed' => $changed, 'ids' => join( ',', $post_ids ) ), '' );

        if ( isset( $_GET['post_status'] ) ) {
            $sendback = add_query_arg( 'post_status', sanitize_text_field( $_GET['post_status'] ), $sendback );
        }

        wp_redirect( esc_url_raw( $sendback ) );
        exit();
    }


    /**
     * Show confirmation message that order status changed for number of orders.
     */
    public function bulk_admin_notices() {
        global $post_type, $pagenow;

        // Bail out if not on shop order list page
        if ( 'edit.php' !== $pagenow || self::$post_type !== $post_type ) {
            return;
        }

        $donation_statuses = $this->get_valid_statuses();

        // Check if any status changes happened
        foreach ( $donation_statuses as $slug => $name ) {

            if ( isset( $_REQUEST[ 'marked_' . $slug ] ) ) {

                $number = isset( $_REQUEST['changed'] ) ? absint( $_REQUEST['changed'] ) : 0;
                $message = sprintf( _n( 'Recurring donation status changed.', '%s recurring donation statuses changed.', $number, 'charitable-recurring' ), number_format_i18n( $number ) );
                echo '<div class="updated"><p>' . $message . '</p></div>';

                break;
            }
        }
    }


    /**
     * Modify bulk messages
     */
    public function bulk_messages( $bulk_messages, $bulk_counts ) {

        $bulk_messages[ self::$post_type ] = array(
            'updated'   => _n( "%d recurring donation updated.", "%d recurring donations updated.", $bulk_counts['updated'], 'charitable-recurring' ),
            'locked'    => ( 1 == $bulk_counts['locked'] ) ? __( "1 recurring donation not updated, somebody is editing it.", 'charitable-recurring' ) :
                               _n( "%s recurring donation not updated, somebody is editing it.", "%s recurring donations not updated, somebody is editing them.", $bulk_counts['locked'], 'charitable-recurring' ),
            'deleted'   => _n( "%s recurring donation permanently deleted.", "%s recurring donations permanently deleted.", $bulk_counts['deleted'], 'charitable-recurring' ),
            'trashed'   => _n( "%s recurring donation moved to the Trash.", "%s recurring donations moved to the Trash.", $bulk_counts['trashed'], 'charitable-recurring' ),
            'untrashed' => _n( "%s recurring donation restored from the Trash.", "%s recurring donations restored from the Trash.", $bulk_counts['untrashed'], 'charitable-recurring' ),
        );

        return $bulk_messages;

    }


    /**
     * Disable the month's dropdown (will replace with custom range search)
     *
     * @param mixed $public_query_vars
     * @param  str $post_type
     * @return array
     * @since  1.0.0
     */
    public function disable_months_dropdown( $disable, $post_type ) {
        if( self::$post_type == $post_type ){
            $disable = true;
        }

        return $disable;
    }


    /**
     * Add date-based filters above the donations table.
     *
     * @param  string $post_type
     * @since  1.0.0
     */
    public function restrict_manage_posts( $post_type = '' ) {
        global $typenow;

        /* Show custom filters to filter orders by donor. */
        if ( in_array( $typenow, array( self::$post_type ) ) ) {

            charitable_admin_view( 'recurring-donations-page/filters' );

        }
    }

    /**
     * Add extra buttons after filters
     *
     * @param   string $which
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function extra_tablenav( $which ) {
        global $typenow;

        /* Add the export button. */
        if ( 'top' == $which && in_array( $typenow, array( self::$post_type ) ) ) {
            charitable_admin_view( 'donations-page/export' );
        }

    }


    /**
     * Add modal template to footer
     *
     * @param   string $which
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function modal_forms() {
        global $typenow;

        /* Add the modal form. */
        if ( in_array( $typenow, array( self::$post_type ) ) ) {
            charitable_admin_view( 'recurring-donations-page/export-form' );
            charitable_admin_view( 'recurring-donations-page/filter-form' );
        }

    }

    /**
     * Load the modal scripts.
     *
     * @param   string $hook
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function load_scripts( $hook ) {

        if ( 'edit.php' != $hook ) {
            return;
        }

        global $typenow;

        /* Enqueue the scripts for donation page */
        if ( in_array( $typenow, array( Charitable_Recurring::POST_TYPE ) ) ) {
            wp_dequeue_style( 'charitable-admin' );

            wp_enqueue_style( 'lean-modal-css' );
            wp_enqueue_style( 'charitable-admin' );
            wp_enqueue_script( 'jquery-core' );
            wp_enqueue_script( 'lean-modal' );
            wp_enqueue_script( 'charitable-admin-donations' );
        }

    }

    /**
     * Use a slightly modified templates
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function admin_view_path( $path, $view, $view_args ) {

        if( $view == 'recurring-donations-page/filters' ){
            $path = charitable_recurring()->get_path( 'admin' ) . 'views/recurring-donations-page/filters.php';
        }
        if( $view == 'recurring-donations-page/filter-form' ){
            $path = charitable_recurring()->get_path( 'admin' ) . 'views/recurring-donations-page/filter-form.php';
        }
        if( $view == 'recurring-donations-page/export-form' ){
            $path = charitable_recurring()->get_path( 'admin' ) . 'views/recurring-donations-page/export-form.php';
        }
        if( $view == 'metaboxes/donation/recurring-donation-overview' ){
            $path = charitable_recurring()->get_path( 'admin' ) . 'views/metaboxes/donation/recurring-donation-overview.php';
        }
        return $path;
    }


    /**
     * Custom filters
     *
     * @param  array $vars
     * @return array
     * @since  1.0.0
     */
    public function request_query( $vars ) {
        global $typenow;

        if ( self::$post_type === $typenow ) { 

            // No Status: fix WP's crappy handling of "all" post status
            if ( ! isset( $vars['post_status'] ) ) {
                $vars['post_status'] = array_keys( $this->get_valid_statuses() );
            }

            /* Set up date query */
            if ( isset( $_GET[ 'start_date' ] ) && ! empty( $_GET[ 'start_date' ] ) ) {
                $start_date = $this->get_parsed_date( $_GET[ 'start_date' ] );            
                
                $vars[ 'date_query' ][ 'after' ] = array(
                    'year' => $start_date[ 'year' ],
                    'month' => $start_date[ 'month' ],
                    'day' => $start_date[ 'day' ]
                );
            }

            if ( isset( $_GET[ 'end_date' ] ) && ! empty( $_GET[ 'end_date' ] ) ) {
                $end_date = $this->get_parsed_date( $_GET[ 'end_date' ] );

                $vars[ 'date_query' ][ 'before' ] = array(
                    'year' => $end_date[ 'year' ],
                    'month' => $end_date[ 'month' ],
                    'day' => $end_date[ 'day' ]
                );
            }


            // filter by campaign
            if ( isset( $_GET[ 'campaign_id' ] ) && ! empty( $_GET[ 'campaign_id' ] ) ) {
                $campaign_donations_db = new Charitable_Campaign_Donations_DB();

                $ids = $campaign_donations_db->get_donations_on_campaign( intval( $_GET[ 'campaign_id' ] ) );

                if( ! empty( $ids ) ){  
                    $ids = wp_list_pluck( $ids, 'donation_id' );     
                    $vars[ 'post__in' ] = (array) $ids; 
                }
                
            }

        }

        return $vars;
    }


    /**
     * column sorting handler
     *
     * @param  array $vars
     * @return array
     * @since  1.0.0
     */
    public function posts_clauses( $clauses ) {

        global $typenow, $wpdb;

        if ( self::$post_type === $typenow ) {
        
            // Sorting
            if ( isset( $_GET['orderby'] ) ) {

                $order = isset( $_GET['order'] ) && strtoupper( $_GET['order'] ) == 'ASC' ? 'ASC' : 'DESC';

                switch ( $_GET['orderby'] ) {

                    case 'amount' :
                        // join the donation ID to the recurring donation's post parent
                        // @todo: this won't hold up if ever recurring donations are manually created w/o a "parent"
                        $clauses['join'] = "JOIN {$wpdb->prefix}charitable_campaign_donations cd ON cd.donation_id = $wpdb->posts.post_parent ";
                        $clauses['orderby'] = "cd.amount " . $order;
                        break;
                    case 'status' :
                        $clauses['orderby'] = $wpdb->posts . ".post_status " . $order;
                        break;

                }
            }

        }

        return $clauses;
    }


    /**
     * Return the status counts, taking into account any current filters.
     *
     * @return  array
     * @access  protected
     * @since   1.4.0
     */
    protected function get_status_counts() {
        if ( ! isset( $this->status_counts ) ) {

            $args = array( 'post_type' => Charitable_Recurring::POST_TYPE );

            if ( isset( $_GET['s'] ) && strlen( $_GET['s'] ) ) {
                $args['s'] = $_GET['s'];
            }

            if ( isset( $_GET['start_date'] ) && strlen( $_GET['start_date'] ) ) {
                $args['start_date'] = $this->get_parsed_date( $_GET['start_date'] );
            }

            if ( isset( $_GET['end_date'] ) && strlen( $_GET['end_date'] ) ) {
                $args['end_date'] = $this->get_parsed_date( $_GET['end_date'] );
            }

            $status_counts = Charitable_Donations::count_by_status( $args );

            $this->status_counts = array();

            foreach ( $status_counts as $status => $data ) {
                $this->status_counts[ $status ] = $data->num_donations;
            }

        }

        return $this->status_counts;
    }


    /**
     * Given a date, returns an array containing the date, month and year. 
     *
     * @return  string[]
     * @access  protected
     * @since   1.0.0
     */
    protected function get_parsed_date( $date ) {
        $time = strtotime( $date );

        return array(
            'year' => date( 'Y', $time ),
            'month' => date( 'm', $time ),
            'day' => date( 'd', $time )
        );
    }

}

endif; // End class_exists check