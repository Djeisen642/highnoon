<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class learnDashMyCourses
 * @package uncanny_pro_toolkit
 */
class learnDashMyCourses extends toolkit\Config implements toolkit\RequiredFunctions {
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
			add_shortcode( 'uo_dashboard', array( __CLASS__, 'uo_course_dashboard' ) );
			add_filter( 'uo-dashboard-template', array( __CLASS__, 'uo_dashboard_get_template' ) );
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'add_dashboard_style' ) );
		}

	}

	public static function add_dashboard_style() {
		wp_enqueue_style( 'uo_dashboard', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/frontend/css/uo_dashboard.css', array(), UNCANNY_TOOLKIT_PRO_VERSION, 'all' );
	}


	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = esc_html__( 'LearnDash Course Dashboard', 'uncanny-pro-toolkit' );

		$kb_link = 'http://www.uncannyowl.com/knowledge-base/learndash-course-dashboard/';

		/* Sample Simple Description with shortcode */
		$class_description = esc_html__( 'Use the [uo_dashboard] shortcode to display the list of enrolled courses for the current user. This is essentially a modified version of the [ld_profile] shortcode without profile data.', 'uncanny-pro-toolkit' );

		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-book "></i><span class="uo_pro_text">PRO</span>';

		$tags = 'learndash';
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
	 * @param $atts
	 *
	 * @return string
	 */
	public static function uo_course_dashboard( $atts ) {

		if ( isset( $atts['user_id'] ) ) {
			$user_id = absint( $atts['user_id'] );
		} else {
			$current_user = wp_get_current_user();

			if ( empty( $current_user->ID ) ) {
				$user_id = 0;
			}else{
				$user_id = $current_user->ID;
			}
		}

		if ( isset( $atts['orderby'] ) ) {

			// Make a correct order by value isset
			$allowed_order_by = array( 'title', 'date', 'menu_order' );
			if ( in_array( $atts['orderby'], $allowed_order_by ) ) {
				$order_by = $atts['orderby'];
			} else {
				return 'The order by value is not of the type title, date, or menu_order.';
			}
		} else {
			$order_by = 'ID';
		}

		if ( isset( $atts['order'] ) ) {

			// Make a correct order value isset
			$allowed_order = array( "asc", "desc" );
			if ( in_array( $atts['order'], $allowed_order ) ) {
				$order = $atts['order'];
			} else {
				return 'The order value is not of the type asc, or desc';
			}
		} else {
			$order = 'desc';
		}


		if ( empty( $current_user ) ) {
			$current_user = get_user_by( 'id', $user_id );
		}

		// Set sorting
		$sort_atts = array(
			'order'   => $order,
			'orderby' => $order_by,
		);

		if ( function_exists( 'ld_get_mycourses' ) ) {

			if( 0 === $user_id ){

				if( isset($atts['show'])){

					if( 'open' === $atts['show']){
						// Get open courses for logged out users
						$user_courses = learndash_get_open_courses();
					}elseif( 'all' === $atts['show']){
						// Show all courses
						$course_query_args = array(
							'post_type'			=>	'sfwd-courses',
							'post_status' => 'publish'
						);

						$courses = get_posts( $course_query_args );
						$user_courses = wp_list_pluck( $courses, 'ID' );
					}else{
						return '';
					}

				}else{

					return '';
					
				}



			}else{
				$user_courses = ld_get_mycourses( $user_id, $sort_atts );
			}

		} else {
			return;
		}

		$usermeta           = get_user_meta( $user_id, '_sfwd-quizzes', true );
		$quiz_attempts_meta = empty( $usermeta ) ? false : $usermeta;
		$quiz_attempts      = array();

		if ( function_exists( 'learndash_certificate_details' ) ) {
			if ( ! empty( $quiz_attempts_meta ) ) {
				foreach ( $quiz_attempts_meta as $quiz_attempt ) {
					$c                          = learndash_certificate_details( $quiz_attempt['quiz'], $user_id );
					$quiz_attempt['post']       = get_post( $quiz_attempt['quiz'] );
					$quiz_attempt["percentage"] = ! empty( $quiz_attempt["percentage"] ) ? $quiz_attempt["percentage"] : ( ! empty( $quiz_attempt["count"] ) ? $quiz_attempt["score"] * 100 / $quiz_attempt["count"] : 0 );

					if ( $user_id == get_current_user_id() && ! empty( $c["certificateLink"] ) && ( ( isset( $quiz_attempt['percentage'] ) && $quiz_attempt['percentage'] >= $c["certificate_threshold"] * 100 ) ) ) {
						$quiz_attempt['certificate'] = $c;
					}
					$quiz_attempts[ learndash_get_course_id( $quiz_attempt['quiz'] ) ][] = $quiz_attempt;
				}
			}
		}
		$args = array(
			'user_id'       => $user_id,
			'quiz_attempts' => $quiz_attempts,
			'current_user'  => $current_user,
			'user_courses'  => $user_courses,
		);

		//Check to see if the file is in template to override default template.
		$file_path = get_stylesheet_directory() . '/uncanny-toolkit-pro/templates/dashboard-template.php';

		if ( ! file_exists( $file_path ) ) {
			$file_path = apply_filters( 'uo-dashboard-template', 'uo_dashboard_get_template' );
		}

		extract( $args );
		$level = ob_get_level();
		ob_start();
		include( $file_path );

		$contents = learndash_ob_get_clean( $level );

		return $contents;

	}


	/**
	 * @return string
	 */
	public static function uo_dashboard_get_template() {
		$filepath = dirname( dirname( __FILE__ ) ) . '/templates/dashboard-template.php';

		return $filepath;
	}

}
