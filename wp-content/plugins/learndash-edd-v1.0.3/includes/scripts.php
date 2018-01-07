<?php
/**
 * Scripts
 *
 * @package     LearnDash\EDD\Scripts
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


/**
 * Load admin scripts
 *
 * @since       1.0.0
 * @global      string $post_type The type of post that we are editing
 * @return      void
 */
function learndash_edd_admin_scripts( $hook ) {
    global $post_type;

    if( ( $hook == 'post.php' || $hook == 'post-new.php' ) && $post_type == 'download' ) {
        wp_enqueue_script( 'learndash_edd_metabox_js', LEARNDASH_EDD_URL . 'assets/js/metabox.js', array( 'jquery' ) );
    }
}
add_action( 'admin_enqueue_scripts', 'learndash_edd_admin_scripts', 100 );

