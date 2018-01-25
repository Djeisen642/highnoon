<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Class ClonePostsPagesLearnDashContents
 * @package uncanny_pro_toolkit
 */
class ClonePostsPagesLearnDashContents extends toolkit\Config implements toolkit\RequiredFunctions {
	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( __CLASS__, 'run_frontend_hooks' ) );
	}

	/*
	 * Initialize frontend actions and filters
	 */
	public static function run_frontend_hooks() {

		if ( true === self::dependants_exist() ) {

			/* ADD FILTERS ACTIONS FUNCTION */
			add_filter( 'post_row_actions', array( __CLASS__, 'add_clone_to_action_rows' ), 10, 2 );
			add_filter( 'page_row_actions', array( __CLASS__, 'add_clone_to_action_rows' ), 10, 2 );
			add_action( 'post_submitbox_misc_actions', array( __CLASS__, 'uncanny_clone_post_publish_box' ) );
			add_action( 'admin_action_clone_learndash_quiz_contents', array( __CLASS__, 'uncanny_clone_quiz' ) );
			add_action( 'admin_action_clone_post_contents', array( __CLASS__, 'uncanny_clone_contents' ) );
		}

	}

	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = esc_html__( 'Duplicate Pages & Posts', 'uncanny-pro-toolkit' );

		$kb_link = 'http://www.uncannyowl.com/knowledge-base/duplicate-pages-posts/';

		/* Sample Simple Description with shortcode */
		$class_description = esc_html__( 'Easily clone pages, posts, LearnDash courses, lessons, topics, quizzes and more. This plugin handles quiz duplication properly.', 'uncanny-pro-toolkit' );

		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-clone"></i><span class="uo_pro_text">PRO</span>';

		$tags = 'general';
		$type = 'pro';

		return array(
			'title'            => $class_title,
			'type'             => $type,
			'tags'             => $tags,
			'kb_link'          => $kb_link, // OR set as null not to display
			'description'      => $class_description,
			'dependants_exist' => self::dependants_exist(),
			'settings'         => false, // OR
			//'settings'         => self::get_class_settings( $class_title ),
			'icon'             => $class_icon,
		);

	}

	/**
	 * Does the plugin rely on another function or plugin
	 *
	 * @return boolean || string Return either true or name of function or plugin
	 *
	 */
	public static function dependants_exist() {

		/* Checks for LearnDash */
		global $learndash_post_types;
		if ( ! isset( $learndash_post_types ) ) {
			return 'Plugin: LearnDash';
		}

		// Return true if no dependency or dependency is available
		return true;

	}

	/**
	 * Add Clone link to Publish Box of WordPress Edit screen
	 */
	public static function uncanny_clone_post_publish_box() {
		$post = get_post( absint( $_GET['post'] ) );
		if ( 'sfwd-assignment' === $post->post_type || ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && 'product' === $post->post_type ) ) {
			return;
		}

		?>
        <div class="misc-pub-section misc-pub-clone" id="Clone">
			<?php
			if ( "sfwd-quiz" === $post->post_type ) {
				$action = "?action=clone_learndash_quiz_contents&return_to_post=true&post=" . $post->ID;
			} else {
				$action = "?action=clone_post_contents&return_to_post=true&post=" . $post->ID;
			}
			$post_type_object = get_post_type_object( $post->post_type );

			?>
            <style>
                .misc-pub-clone #post-clone-display::before {
                    content: "\f105";
                    font: 400 20px/1 dashicons;
                    speak: none;
                    display: inline-block;
                    padding: 0 2px 0 0;
                    top: 0;
                    left: -1px;
                    position: relative;
                    vertical-align: top;
                    -webkit-font-smoothing: antialiased;
                    -moz-osx-font-smoothing: grayscale;
                    text-decoration: none !important;
                    color: #82878c;
                }
            </style>
            <span id="post-clone-display"><strong>Action:</strong> </span>
            <a href="<?php echo $action; ?>" class="edit-visibility hide-if-no-js"><span
                        aria-hidden="true">Clone this <?php echo $post_type_object->labels->singular_name ?></span></a>
        </div>
		<?php
	}

	/**
	 * @param $actions
	 * @param $post
	 *
	 * @return mixed
	 */
	public static function add_clone_to_action_rows( $actions, $post ) {
		//Don't add Clone link to Woocommerce, or Products post type of woocommerce
		if ( 'sfwd-assignment' === $post->post_type || ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && 'product' === $post->post_type ) ) {
			return $actions;
		}
		if ( "sfwd-quiz" === $post->post_type ) {
			$action = "?action=clone_learndash_quiz_contents&post=" . $post->ID;
		} else {
			$action = "?action=clone_post_contents&post=" . $post->ID;
		}
		$post_type_object = get_post_type_object( $post->post_type );
		$can_edit_post    = current_user_can( $post_type_object->cap->edit_post, $post->ID );
		if ( $can_edit_post ) {
			$actions['uncanny_clone'] = '<a href="' . $action . '" title="' . esc_attr( __( 'Clone this ' . $post_type_object->labels->singular_name ) ) . '">' . __( 'Clone this ' . $post_type_object->labels->singular_name ) . '</a>';
		}

		return $actions;
	}

	/**
	 *
	 */
	public static function uncanny_clone_quiz() {

		if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] ) || ( isset( $_REQUEST['action'] ) && 'clone_learndash_quiz_contents' === $_REQUEST['action'] ) ) ) {
			wp_die( __( 'No quiz to duplicate has been supplied!', 'uncanny-pro-toolkit' ) );
		}

		// Get the original post
		$id   = ( isset( $_GET['post'] ) ? $_GET['post'] : $_POST['post'] );
		$post = get_post( absint( $id ) );

		// Copy the post and insert it
		if ( isset( $post ) && $post != null ) {
			self::duplicate_quiz_create_duplicate( $post );

			wp_redirect( admin_url( 'edit.php?post_type=' . $post->post_type ) );
			exit;

		} else {
			wp_die( esc_attr( __( 'Copy creation failed, could not find original:', 'uncanny-pro-toolkit' ) ) . ' ' . htmlspecialchars( $id ) );
		}

	}

	/**
	 *
	 */
	public static function uncanny_clone_contents() {

		if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] ) || ( isset( $_REQUEST['action'] ) && 'clone_post_contents' === $_REQUEST['action'] ) ) ) {
			wp_die( __( 'No quiz to duplicate has been supplied!', 'uncanny-pro-toolkit' ) );
		}

		// Get the original post
		$id   = ( isset( $_GET['post'] ) ? $_GET['post'] : $_POST['post'] );
		$post = get_post( absint( $id ) );

		// Copy the post and insert it
		if ( isset( $post ) && $post != null ) {
			$new_id = self::duplicate_contents_create_duplicate( $post, 'draft', $post->post_parent );
			if ( isset( $_GET['return_to_post'] ) ) {
				wp_redirect( admin_url( 'post.php?post=' . $new_id . '&action=edit' ) );
			} else {
				wp_redirect( admin_url( 'edit.php?post_type=' . $post->post_type ) );
			}
			exit;

		} else {
			wp_die( esc_attr( __( 'Copy creation failed, could not find original:', 'uncanny-pro-toolkit' ) ) . ' ' . htmlspecialchars( $id ) );
		}

	}

	/**
	 * @param $post
	 * @param string $status
	 * @param string $parent_id
	 *
	 * @return int|\WP_Error
	 */
	public static function duplicate_quiz_create_duplicate( $post, $status = '', $parent_id = '' ) {
		global $wpdb;
		// We don't want to clone revisions
		if ( 'revision' === $post->post_type ) {
			return false;
		}

		if ( 'attachment' !== $post->post_type ) {
			$status = 'draft';
		}

		$new_post_author = get_current_user();

		$new_post                  = array(
			'menu_order'     => $post->menu_order,
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author->ID,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_mime_type' => $post->post_mime_type,
			'post_parent'    => $new_post_parent = empty( $parent_id ) ? $post->post_parent : $parent_id,
			'post_password'  => $post->post_password,
			'post_status'    => $new_post_status = ( empty( $status ) ) ? $post->post_status : $status,
			'post_title'     => $post->post_title . " ( Duplicate Quiz )",
			'post_type'      => $post->post_type,
		);
		$new_post['post_date']     = $new_post_date = $post->post_date;
		$new_post['post_date_gmt'] = get_gmt_from_date( $new_post_date );

		$new_post_id = wp_insert_post( $new_post );

		// add taxonomies
		$categories = wp_get_post_terms( $post->ID, 'category' );
		$tags       = wp_get_post_terms( $post->ID, 'post_tag' );

		$all_categories = array();
		foreach ( $categories as $category ) {
			$all_categories[] = $category->term_id;
		}
		
		$all_tags = array();
		foreach ( $tags as $tag ) {
			$all_tags[] = $tag->term_id;
		}

		if( ! empty(($all_categories))){
			wp_set_object_terms( $new_post_id, $all_categories,'category');
        }

		if( ! empty(($all_tags))){
			wp_set_object_terms( $new_post_id, $all_tags,'post_tag');
		}

		// If the copy is published or scheduled, we have to set a proper slug.
		if ( $new_post_status == 'publish' || $new_post_status == 'future' ) {
			$post_name = wp_unique_post_slug( $post->post_name, $new_post_id, $new_post_status, $post->post_type, $new_post_parent );

			$new_post              = array();
			$new_post['ID']        = $new_post_id;
			$new_post['post_name'] = $post_name;

			// Update the post into the database
			wp_update_post( $new_post );
		}

		$current_quiz_id = get_post_meta( $post->ID, 'quiz_pro_id', true );

		//Clone quiz settings for new quiz
		$wpdb->query( "CREATE TEMPORARY TABLE tmpQuizPro SELECT * FROM " . $wpdb->prefix . "wp_pro_quiz_master WHERE id = $current_quiz_id;" );
		$wpdb->query( "UPDATE tmpQuizPro SET id = NULL WHERE id = $current_quiz_id;" );
		$wpdb->query( "INSERT INTO  " . $wpdb->prefix . "wp_pro_quiz_master  SELECT * FROM tmpQuizPro ;" );
		$wpdb->query( "DROP TEMPORARY TABLE IF EXISTS tmpQuizPro;" );

		//Get new Quiz ID
		$new_quiz_id = $wpdb->get_var( "SELECT MAX(id) AS ID FROM " . $wpdb->prefix . "wp_pro_quiz_master" );

		//Clone Question & Answers for new quiz
		$wpdb->query( "CREATE TEMPORARY TABLE tmpQuizPro SELECT * FROM " . $wpdb->prefix . "wp_pro_quiz_question WHERE quiz_id = $current_quiz_id;" );
		$wpdb->query( "UPDATE tmpQuizPro SET id = NULL, quiz_id = $new_quiz_id WHERE quiz_id = $current_quiz_id;" );
		$wpdb->query( "INSERT INTO  " . $wpdb->prefix . "wp_pro_quiz_question  SELECT * FROM tmpQuizPro ;" );
		$wpdb->query( "DROP TEMPORARY TABLE IF EXISTS tmpQuizPro;" );

		//Clone existing post meta & update quiz ID to new clone quiz
		$current_post_meta = get_post_meta( $post->ID );

		foreach ( $current_post_meta as $key => $value ) {
			$val = maybe_unserialize( $value[0] );
			switch ( $key ):
				case 'quiz_pro_id':
					update_post_meta( $new_post_id, $key, $new_quiz_id );
					break;
				case '_sfwd-quiz':
					if ( is_array( $val ) ) {
						$sfwd_quiz = array();
						foreach ( $val as $qKey => $qVal ) {
							if ( 'sfwd-quiz_quiz_pro' === $qKey ) {
								$sfwd_quiz['sfwd-quiz_quiz_pro'] = $new_quiz_id;
							} else {
								$sfwd_quiz[ $qKey ] = $qVal;
							}
						}
						update_post_meta( $new_post_id, $key, $sfwd_quiz );
					}
					break;
				default:
					update_post_meta( $new_post_id, $key, $val );
			endswitch;

		}

		return $new_post_id;
	}

	/**
	 * @param $post
	 * @param string $status
	 * @param string $parent_id
	 *
	 * @return int|\WP_Error
	 */
	public static function duplicate_contents_create_duplicate( $post, $status = '', $parent_id = '' ) {
		global $wpdb;
		// We don't want to clone revisions
		if ( 'revision' === $post->post_type ) {
			return false;
		}

		if ( 'attachment' !== $post->post_type ) {
			$status = 'draft';
		}

		$new_post_author = get_current_user();

		$new_post = array(
			'menu_order'     => $post->menu_order,
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author->ID,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_mime_type' => $post->post_mime_type,
			'post_parent'    => $new_post_parent = empty( $parent_id ) ? $post->post_parent : $parent_id,
			'post_password'  => $post->post_password,
			'post_status'    => $new_post_status = ( empty( $status ) ) ? $post->post_status : $status,
			'post_title'     => $post->post_title . " ( Duplicate )",
			'post_type'      => $post->post_type,
			'post_date'      => $post->post_date,
			'post_date_gmt'  => get_gmt_from_date( $post->post_date ),
		);

		$new_post_id = wp_insert_post( $new_post );

		// If the copy is published or scheduled, we have to set a proper slug.
		if ( $new_post_status == 'publish' || $new_post_status == 'future' ) {
			$post_name = wp_unique_post_slug( $post->post_name, $new_post_id, $new_post_status, $post->post_type, $new_post_parent );

			$new_post              = array();
			$new_post['ID']        = $new_post_id;
			$new_post['post_name'] = $post_name;

			// Update the post into the database
			wp_update_post( $new_post );
		}

		//Clone existing post meta & update quiz ID to new post / page / custom post type
		$current_post_meta = get_post_meta( $post->ID );

		foreach ( $current_post_meta as $key => $value ) {
			$val = maybe_unserialize( $value[0] );
			switch ( $key ):
				default:
					update_post_meta( $new_post_id, $key, $val );
			endswitch;

		}
		$get_tags_terms_cats = $wpdb->get_var( "SELECT COUNT(object_id) AS total FROM $wpdb->term_relationships WHERE object_id = " . $post->ID );
		if ( $get_tags_terms_cats > 0 ) {
			//Clone quiz settings for new quiz
			$wpdb->query( "DELETE FROM  " . $wpdb->term_relationships . " WHERE object_id = $new_post_id;" );
			$wpdb->query( "CREATE TEMPORARY TABLE tmpCopyCats SELECT * FROM " . $wpdb->term_relationships . " WHERE object_id = " . $post->ID . ";" );
			$wpdb->query( "UPDATE tmpCopyCats SET object_id = $new_post_id WHERE object_id = $post->ID;" );
			$wpdb->query( "INSERT INTO  " . $wpdb->term_relationships . "  SELECT * FROM tmpCopyCats ;" );
			$wpdb->query( "DROP TEMPORARY TABLE IF EXISTS tmpCopyCats;" );

		}

		if ( 'groups' === $post->post_type ) {
			if ( function_exists( 'learndash_group_enrolled_courses' ) ) {
				$courses = learndash_group_enrolled_courses( $post->ID );
				if ( ! empty( $courses ) ) {
					//foreach ( $courses as $course ) {
					learndash_set_group_enrolled_courses( $new_post_id, $courses );
					//}
				}
			}
		}

		if( class_exists( 'LDLMS_Course_Steps') ){
			if ( \LearnDash_Settings_Section::get_section_setting('LearnDash_Settings_Courses_Builder', 'enabled' ) == 'yes' ) {
				// Rebuild Course Steps
				$course_steps = new \LDLMS_Course_Steps( $new_post_id );
				$course_steps->load_steps();
				$course_steps->build_steps();
				$course_steps->set_step_to_course();
			}
        }


		return $new_post_id;
	}
}