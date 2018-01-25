<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

class CourseAccessExpiry extends toolkit\Config implements toolkit\RequiredFunctions {

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

			//Expiration date shortcode
			add_shortcode( 'uo_expiration_in', array( __CLASS__, 'expiration_in' ) );

		}

	}

	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = __( 'Days Until Course Expiry', 'uncanny-pro-toolkit' );

		$kb_link = 'http://www.uncannyowl.com/knowledge-base/days-until-course-expiry/';

		/* Sample Simple Description with shortcode */
		$class_description = __( 'Use this shortcode to display the number of days until the learner\'s access expires for the current course. This is a useful shortcode to include on course pages.', 'uncanny-pro-toolkit' );

		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-hourglass-end"></i><span class="uo_pro_text">PRO</span>';

		$tags = 'learndash';
		$type = 'pro';

		return array(
			'title'            => $class_title,
			'type'             => $type,
			'tags'             => $tags,
			'kb_link'          => $kb_link, // OR set as null not to display
			'description'      => $class_description,
			'dependants_exist' => self::dependants_exist(),
			'settings'         => false,
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

		return true;

	}

	/*
	 * Shortcode [expiration_in]
	 *
	 */
	public static function expiration_in( $attributes ) {


		$a = shortcode_atts( array(
			'pre-text'  => __( 'Course Access Expires in', 'uncanny-pro-toolkit' ),
			'course-id' => null
		), $attributes );

		$current_user_id = get_current_user_id();

		$course_id = $a['course-id'];

		if ( null === $course_id ) {
			global $post;
			$post_object = $post;
			// Get course id
			if ( $post->post_type == 'sfwd-courses' ) {
				$course_id = $post->ID;
			}
		} else {
			$post_object = get_post( (int) $course_id );
			if ( $post_object ) {
				$course_id = $post_object->ID;
			}
		}


		// Get course id from related lesson, topic, or quiz
		if ( $post_object->post_type == 'sfwd-lessons' || $post_object->post_type == 'sfwd-topic' || $post_object->post_type == 'sfwd-quiz' ) {
			$course_id = learndash_get_course_id($post_object->ID);
		}

		// if course id not found
		if ( null === $course_id || '' === $course_id ) {
			return '';
		}

		// Get expiration date
		$course_access_up_to = ld_course_access_expires_on( $course_id, $current_user_id );

		if ( 0 !== $course_access_up_to && sfwd_lms_has_access( $course_id, $current_user_id ) ) {

			$current_time = time();

			$amount_seconds_between = $course_access_up_to - $current_time;

			$amount_days_between = floor( $amount_seconds_between / 60 / 60 / 24 );

			if ( 0 > $amount_days_between ) {
				return '';
			}

			if ( 1 === $amount_days_between ) {
				$text = sprintf(__('%s %s day',''), $a['pre-text'], $amount_days_between);
				$text = apply_filters( '', $text,$a['pre-text'], $amount_days_between);
				return $text;
			} else {
				$text = sprintf(__('%s %s days',''), $a['pre-text'], $amount_days_between);
				$text = apply_filters( '', $text,$a['pre-text'], $amount_days_between);
				return $text;
			}


		} else {
			return '';
		}

	}

}