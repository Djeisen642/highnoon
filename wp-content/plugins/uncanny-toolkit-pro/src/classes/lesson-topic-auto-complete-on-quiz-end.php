<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class LessonTopicAutoCompleteOnQuizEnd
 * @package uncanny_pro_toolkit
 */
class LessonTopicAutoCompleteOnQuizEnd extends toolkit\Config implements toolkit\RequiredFunctions {

	public static $auto_completed_post_types = array( 'sfwd-lessons', 'sfwd-topic' );

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

			add_action( 'learndash_quiz_completed', array( __CLASS__, 'complete_associated_topic_lesson' ) );
		}

	}

	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = esc_html__( 'Autocomplete Lessons & Topics on Quiz Results Page', 'uncanny-pro-toolkit' );

		$kb_link = 'http://www.uncannyowl.com/knowledge-base/autocomplete-lessons-topics-on-quiz-completion/';

		/* Sample Simple Description with shortcode */
		$class_description = esc_html__( 'Automatically mark LearnDash lessons and topics as completed when the user reaches the quiz results page with a passing mark.', 'uncanny-pro-toolkit' );

		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-percent"></i><span class="uo_pro_text">PRO</span>';

		$tags = 'learndash';
		$type = 'pro';

		return array(
			'title'            => $class_title,
			'type'             => $type,
			'tags'             => $tags,
			'kb_link'          => $kb_link, // OR set as null not to display
			'description'      => $class_description,
			'dependants_exist' => self::dependants_exist(),
			'settings'         => null, //self::get_class_settings( $class_title ),
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
	 * HTML for modal to create settings
	 *
	 * @static
	 *
	 * @param $class_title
	 *
	 * @return string
	 */
	public static function get_class_settings( $class_title ) {

		// Get pages to populate drop down
		$args = array(
			'sort_order'  => 'asc',
			'sort_column' => 'post_title',
			'post_type'   => 'page',
			'post_status' => 'publish',
		);

		$pages     = get_pages( $args );
		$drop_down = array();
		array_push( $drop_down, array( 'value' => 0, 'text' => '- Select Page -' ) );

		foreach ( $pages as $page ) {
			array_push( $drop_down, array( 'value' => $page->ID, 'text' => $page->post_title ) );
		}

		// Create options
		$options = array(
			array(
				'type'       => 'radio',
				'label'      => 'Use Global Settings',
				'radio_name' => 'uo_global_auto_complete',
				'radios'     => array(
					array(
						'value' => 'auto_complete_all',
						'text'  => 'Enable auto-completion for all lessons and topics **<br>',
					),
					array(
						'value' => 'auto_complete_only_lesson_topics_set',
						'text'  => 'Disable autocompletion for all lessons and topics **',
					),
				),
			),
			array(
				'type'       => 'html',
				'class'      => 'uo-additional-information',
				'inner_html' => __( '<div>** This global setting can be overridden for individual lessons and topics in the Edit page of the associated lesson or topic.</div>', 'uncanny-pro-toolkit' ),
			),

		);

		// Build html
		$html = toolkit\Config::settings_output( array(
			'class'   => __CLASS__,
			'title'   => $class_title,
			'options' => $options,
		) );

		return $html;
	}

	/**
	 * @param $quiz
	 */
	public static function complete_associated_topic_lesson( $quiz ) {

		$user                    = wp_get_current_user();
		$quiz                    = $quiz['quiz'];
		$related_lesson_topic_id = learndash_get_setting( $quiz, 'lesson' );

		$post = get_post( $related_lesson_topic_id );

		if ( 'sfwd-lessons' === $post->post_type ) {
			$lesson = $post;

			$all_quizzes = self::check_quiz_list( $lesson->ID );

			if ( $all_quizzes && learndash_lesson_topics_completed( $lesson->ID, false ) ) {
				//Marking Topic Complete
				if ( ! learndash_is_lesson_complete( get_current_user_id(), $lesson->ID ) ) {
					learndash_process_mark_complete( $user->ID, $lesson->ID );
				}
			}
		}

		if ( 'sfwd-topic' === $post->post_type ) {
			$topic     = $post;
			$lesson_id = learndash_get_setting( $topic, 'lesson' );
			$lesson    = get_post( $lesson_id );

			// Are all quizzes complete in the topic
			$all_topic_quizzes = self::check_quiz_list( $topic->ID );

//			toolkit\Config::trace_logs( 'Yes: ' . $topic->ID, 'IS IT TOPIC', 'equiz' );
//			toolkit\Config::trace_logs( $all_topic_quizzes, 'All Topic Quizzes', 'equiz' );

			if ( $all_topic_quizzes ) {
				//Marking Topic Complete
				if ( ! learndash_is_lesson_complete( get_current_user_id(), $topic->ID ) ) {
					learndash_process_mark_complete( $user->ID, $topic->ID );
				}
				// Check and mark lesson complete in all topics are completed in the lesson

			}

			// Are all quizzes complete in the topic
			$all_lesson_quizzes = self::check_quiz_list( $lesson->ID );
//			toolkit\Config::trace_logs( 'Yes: ' . $lesson->ID, 'IS IT related Leson', 'equiz' );
//			toolkit\Config::trace_logs( $all_lesson_quizzes, 'All Lesson Quizzes', 'equiz' );

			// Check and mark lesson complete in all topics are completed in the lesson
			if ( $all_lesson_quizzes ) {
				//Marking Topic Complete
				if ( ! learndash_is_lesson_complete( get_current_user_id(), $lesson->ID ) ) {
					learndash_lesson_topics_completed( $lesson->ID, true );
				}
			}

		}
	}

	/**
	 * @param $id
	 *
	 * @return bool
	 */
	public static function check_quiz_list( $id ) {

		$user = wp_get_current_user();

		// Get all user quiz results
		$user_quizzes = get_user_meta( $user->ID, '_sfwd-quizzes', true );
		if ( '' === $user_quizzes ) {
			$user_quizzes = array();
		}
//		toolkit\Config::trace_logs( $user_quizzes, 'User Quizzes', 'equiz' );
		$quiz_list = learndash_get_lesson_quiz_list( $id );
		if ( '' === $quiz_list ) {
			$quiz_list = array();
		}

//		toolkit\Config::trace_logs( $quiz_list, 'Quiz List', 'equiz' );

		// Compare amount of quizzes in lesson with the amount of matching user quizzes passed
		$quizzes_passed = array();

		if ( ( is_array( $user_quizzes ) && ! empty( $user_quizzes ) ) && ( is_array( $quiz_list ) && ! empty( $quiz_list ) ) ) {
			// Loop all quizzes in lessons
			foreach ( $quiz_list as $quiz ) {

				// Loop all quizzes completed by user
				foreach ( $user_quizzes as $user_quiz ) {

					// check if lesson quiz id and completed quiz id match
					if ( $quiz['post']->ID == (int) $user_quiz['quiz'] ) {
//						toolkit\Config::trace_logs( $quiz['post']->ID, 'in loop hit $quiz', 'equiz' );
//						toolkit\Config::trace_logs( $user_quiz['quiz'], 'in loop hit $user_quiz', 'equiz' );
//						toolkit\Config::trace_logs( $user_quiz['pass'], 'in loop hit $user_quiz[pass]', 'equiz' );
						// Check if the quiz was passed
						if ( 1 == $user_quiz['pass'] ) {
							$quizzes_passed[ (int) $user_quiz['quiz'] ] = true;
//							toolkit\Config::trace_logs( $quizzes_passed, 'in loop hit passed 1', 'equiz' );
						}

					}

				}

			}
		}
//		toolkit\Config::trace_logs( $quizzes_passed, 'Quizzes Passed Array', 'equiz' );
//		toolkit\Config::trace_logs( count ( $quizzes_passed ), '$amount_quizzes_user_passed', 'equiz' );
//		toolkit\Config::trace_logs( count( $quiz_list ), '$amount_quizzes_in_lesson', 'equiz' );
		if ( count( $quizzes_passed ) === count( $quiz_list ) ) {
			$quizzes_completed = true;
		} else {
			$quizzes_completed = false;
		}

		return $quizzes_completed;
	}
}