<?php
/**
 * Metabox
 *
 * @package     LearnDash\EDD\Metabox
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


/**
 * Register meta box for LearnDash
 *
 * @since       1.0.0
 * @return      void
 */
function learndash_edd_add_meta_box() {
    add_meta_box(
        'learndash',
        __( 'LearnDash', 'learndash-edd' ),
        'learndash_edd_render_meta_box',
        'download',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'learndash_edd_add_meta_box' );


/**
 * Render meta box
 *
 * @since       1.0.0
 * @global      object $post The post we are editing
 * @return      void
 */
function learndash_edd_render_meta_box() {
    global $post;

    $post_id                = $post->ID;
    $enable_courses     = get_post_meta( $post_id, '_edd_learndash_is_course', true ) ? true : false;
    $enable_courses_css = ( $enable_courses == false ? ' style="display: none;"' : '' );
    $learndash_course   = get_post_meta( $post_id, '_edd_learndash_course', true );
    $courses        = get_posts( array( 'post_type' => 'sfwd-courses', 'posts_per_page' => -1, 'orderby' => 'post_title', 'order' => 'ASC' ) );
    $course_list = array();
    $selected = array();
    if ( $courses ) {
        foreach ( $courses as $course ) {
            $course_list[$course->ID] = $course->post_title;
            if ( in_array( $course->ID, $learndash_course ) ) {
                $selected[] = $course->ID;
            }
        }
    }
    $fields = new EDD_HTML_Elements();
    ?>
    <p>
        <input type="checkbox" name="_edd_learndash_is_course" id="_edd_learndash_is_course" value="1" <?php echo checked( true, $enable_courses, false ); ?> />
        <label for="_edd_learndash_is_course"><?php _e( 'Is this a LearnDash course?', 'learndash-edd' ); ?></label>
    </p>

    <p id="edd_learndash_course_wrapper"<?php echo $enable_courses_css; ?>>
        <label for="_edd_learndash_course"><?php _e( 'Which course?', 'learndash-edd' ); ?></label>
        <?php
            if( $courses ) {
                echo $fields->select( array(
                    'id'               => '_edd_learndash_course',
                    'name'             => '_edd_learndash_course[]',
                    'options'          => $course_list,
                    'multiple'         => true,
                    'selected'         => $selected,
                    'chosen'           => true,
                    'show_option_none' => false,
                    'show_option_all'  => false,
                    'placeholder'      => 'Choose one or more courses',
                ) );
            } else {
                printf( __( 'No LearnDash courses found! Do you need to <a href="%s">create one</a>?', 'learndash-edd' ), admin_url( 'post-new.php?post_type=sfwd-courses' ) );
            }
        ?>
    </p>
    <?php
    
    wp_nonce_field( basename( __FILE__ ), 'learndash_edd_meta_box_nonce' );
}


/**
 * Save post meta when the save_post action is called
 *
 * @since       1.0.0
 * @param       int $post_id The ID of the post we are saving
 * @global      object $post The post we are saving
 * @return      void
 */
function learndash_edd_meta_box_save( $post_id ) {
    global $post;

    // Don't process if nonce can't be validated
    if( ! isset( $_POST['learndash_edd_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['learndash_edd_meta_box_nonce'], basename( __FILE__ ) ) ) return $post_id;

    // Don't process if this is an autosave
    if( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || isset( $_REQUEST['bulk_edit'] ) ) return $post_id;

    // Don't process if this is a revision
    if( isset( $post->post_type ) && $post->post_type == 'revision' ) return $post_id;

    // Don't process if the current user shouldn't be editing this product
    if( ! current_user_can( 'edit_product', $post_id ) ) return $post_id;

    // The default fields that get saved
    $fields = array(
        '_edd_learndash_course',
        '_edd_learndash_is_course'
    );
    
    foreach( $fields as $field ) {
        if( isset( $_POST[ $field ] ) ) {
            if( is_string( $_POST[ $field ] ) ) {
                $new = esc_attr( $_POST[ $field ] );
            } else {
                $new = $_POST[ $field ];
            }

            update_post_meta( $post_id, $field, $new );
        } else {
            delete_post_meta( $post_id, $field );
        }
    }
}
add_action( 'save_post', 'learndash_edd_meta_box_save' );
