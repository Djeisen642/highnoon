<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Sample
 * @package uncanny_pro_toolkit
 * @subpackage  uncanny_learndash_toolkit\Sample
 * @since       1.0.0
 */
class LessonTopicAutoComplete extends toolkit\Config implements toolkit\RequiredFunctions {


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

			// Remove html output of mark complete button
			add_filter( 'learndash_mark_complete', array( __CLASS__, 'remove_mark_complete_button' ), 99, 2 );

			// Auto Complete LearnDash Module learndash_after everything gets loaded
			// Calling at 'shutdown' because we want to give the user at least a second to navigate through
			add_action( 'shutdown', array( __CLASS__, 'auto_complete_module' ), 10 );

			// Add auto complete setting to LearnDash Lessons, Topics
			add_filter( 'learndash_post_args', array( __CLASS__, 'add_auto_complete_to_post_args' ) );

		}

	}

	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = esc_html__( 'Autocomplete Lessons & Topics', 'uncanny-pro-toolkit' );

		$kb_link = 'http://www.uncannyowl.com/knowledge-base/autocomplete-lessons-topics/';

		/* Sample Simple Description with shortcode */
		$class_description = esc_html__( 'Automatically mark all lessons and topics as completed on user visit and remove Mark Complete buttons. Global settings can be overridden for individual lessons and topics.', 'uncanny-pro-toolkit' );

		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-book "></i><span class="uo_pro_text">PRO</span>';
		$tags       = 'learndash';
		$type       = 'pro';

		return array(
			'title'            => $class_title,
			'type'             => $type,
			'tags'             => $tags,
			'kb_link'          => $kb_link, // OR set as null not to display
			'description'      => $class_description,
			'dependants_exist' => self::dependants_exist(),
			'settings'         => self::get_class_settings( $class_title ),
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
	 * @return HTML
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
				'label'      => 'Global Settings',
				'radio_name' => 'uo_global_auto_complete',
				'radios'     => array(
					array(
						'value' => 'auto_complete_all',
						'text'  => 'Enable auto-completion for all lessons and topics **<br>'
					),
					array(
						'value' => 'auto_complete_only_lesson_topics_set',
						'text'  => 'Disable autocompletion for all lessons and topics **'
					)
				),
			),
			array(
				'type'       => 'html',
				'class'      => 'uo-additional-information',
				'inner_html' => __( '<div>** This global setting can be overridden for individual lessons and topics in the Edit page of the associated lesson or topic.</div>', 'uncanny-pro-toolkit' ),
			),

		);

		// Build html
		$html = self::settings_output( array(
			'class'   => __CLASS__,
			'title'   => $class_title,
			'options' => $options,
		) );

		return $html;
	}

	/**
	 * Filter HTML output to mark course complete button
	 *
	 * @param  string $return
	 * @param object $post
	 *
	 * @return string $return
	 */
	public static function remove_mark_complete_button( $return, $post ) {

		/* LD 2.3 removed the next if until mark complete is clicked, since we removed the mark complete button
		 * there is no way to progress. LD allows to added the next to be added back in version 2.3.0.2. Let's use it!
		*/
		add_filter( 'learndash_show_next_link', array( __CLASS__, 'learndash_show_next_link_progression' ), 10, 3 );

		$post_type = $post->post_type;

		if ( self::maybe_complete( $post ) ) {
			// Remove mark complete button if its a lesson or topic
			if ( in_array( $post_type, self::$auto_completed_post_types ) ) {
				return '';
			}
		}

		return $return;

	}

	/*
	 * Filter to bring back next navigation links
	 *
	 * @param bool $show_next_link
	 * @param int $user_id
	 * @param int $post_id
	 *
	 * return bool
	 */
	public static function learndash_show_next_link_progression( $show_next_link = false, $user_id = 0, $post_id = 0 ) {
		return true;
	}

	/*
	 *  Auto Complete LearnDash Module
	 */
	public static function auto_complete_module() {

		$user_ID = get_current_user_id();

		global $post;

		if ( null !== $post && ! is_admin() ) {

			$course_id = learndash_get_course_id();
			$post_ID   = $post->ID;
			$post_type = $post->post_type;

			if ( in_array( $post_type, self::$auto_completed_post_types ) ) {

				// mark module complete if it is a lesson or topic
				if ( self::maybe_complete( $post ) && sfwd_lms_has_access( $course_id ) ) {

					$lesson_progression_enabled = learndash_lesson_progression_enabled();

					// only mark lesson complete if lesson's topics and quizzes are complete
					if ( 'sfwd-lessons' === $post_type ) {

						$lesson_completed = toolkit\MarkLessonsComplete::check_lesson_complete( $post_ID );

						if ( $lesson_completed ) {

							if ( true === $lesson_completed['topics_completed'] && true === $lesson_completed['quizzes_completed'] ) {

								// if learndash lesson progression is enabled and the previous lesson is not marked complete
								// do not mark completed
								$previous_lesson_completed  = is_previous_complete( $post );

								if ( $lesson_progression_enabled && ! $previous_lesson_completed ) {
									// do nothing
								} else {
								    // only mark complete is the lessons isnt aready complete
                                    if( ! learndash_is_lesson_complete( $user_ID, $post_ID ) ){

	                                    $lesson_id = $post_ID;

                                        // Make sure the module is available
                                        if( class_exists('uncanny_pro_toolkit/UncannyDripLessonsByGroup')){
	                                        $lesson_access_from = UncannyDripLessonsByGroup::get_lesson_access_from( $lesson_id, $user_ID);
                                        }else{
	                                        $lesson_access_from = false;
                                        }

	                                    if ( empty ( $lesson_access_from ) ) {
		                                    learndash_process_mark_complete( $user_ID, $post_ID );
                                        }

                                    }

								}
							}


						}
					}

					// Mark topic complete
					if ( 'sfwd-topic' === $post_type ) {

						// Function is miss worded, passing topic ID does get a list of quizzes for the topic
						$topic_quiz_list = learndash_get_lesson_quiz_list( $post_ID );

						// Check if previous topic is complete
						$previous_topic_completed  = is_previous_complete( $post );

						// check if lesson progression is enabled
						if($lesson_progression_enabled){

							// Mark topic complete only if lesson previous topic is completed
							if( $previous_topic_completed  && empty( $topic_quiz_list )){
								if( ! learndash_is_topic_complete( $user_ID, $post_ID ) ){

									$lesson_id = learndash_get_setting( $post, 'lesson' );

									// Make sure the module is available
									if( class_exists('uncanny_pro_toolkit/UncannyDripLessonsByGroup')){
										$lesson_access_from = UncannyDripLessonsByGroup::get_lesson_access_from( $lesson_id, $user_ID);
									}else{
										$lesson_access_from = false;
									}

									if ( empty ( $lesson_access_from ) ) {
										learndash_process_mark_complete( $user_ID, $post_ID );
									}
								}

							}

                        }else{

							// Only mark complete if topic does not have a quiz
							if ( empty( $topic_quiz_list ) ) {
								if( ! learndash_is_topic_complete( $user_ID, $post_ID ) ){

									$lesson_id = learndash_get_setting( $post, 'lesson' );

									// Make sure the module is available
									if( class_exists('uncanny_pro_toolkit/UncannyDripLessonsByGroup')){
										$lesson_access_from = UncannyDripLessonsByGroup::get_lesson_access_from( $lesson_id, $user_ID);
									}else{
										$lesson_access_from = false;
									}

									if ( empty ( $lesson_access_from ) ) {
										learndash_process_mark_complete( $user_ID, $post_ID );
									}

								}

							}

                        }

						$lesson_id        = get_post_meta( $post_ID, 'lesson_id', true );
						$lesson_completed = toolkit\MarkLessonsComplete::check_lesson_complete( $lesson_id );

						// Mark the topics associated lesson if all of the lessons topics / quizzes are complete
						if ( $lesson_completed ) {
							if ( true === $lesson_completed['topics_completed'] && true === $lesson_completed['quizzes_completed'] ) {

								// Make sure the module is available
								if( class_exists('uncanny_pro_toolkit/UncannyDripLessonsByGroup')){
									$lesson_access_from = UncannyDripLessonsByGroup::get_lesson_access_from( $lesson_id, $user_ID);
								}else{
									$lesson_access_from = false;
								}

							    // is the lesson already complete?? lets not double tap it
								if( ! learndash_is_lesson_complete( $user_ID, $lesson_id ) && empty ( $lesson_access_from ) ){
									learndash_process_mark_complete( $user_ID, $lesson_id );
								}

							}

							$link = '';

							// is there a next post link... if there is than Done button isn't needed
							$next_post_link = learndash_next_post_link();

							if ( true === $lesson_completed['topics_completed'] && empty( $topic_quiz_list ) && '' === $next_post_link ) {

								$lesson_quiz_ids = $lesson_completed['quiz_list_left'];
								if ( true === $lesson_completed['quizzes_completed'] || empty( $lesson_quiz_ids ) ) {

                                    // Go To Lesson Page
									$link = learndash_next_post_link( '', true, get_post($lesson_id) );

								} else {

									// Go To Next Quiz

									// If the user never attempted a quiz bug empty array returned
									// TODO fix mark-lessons-complete.php
									// @see mark-lessons-complete


									foreach ( $lesson_quiz_ids as $quiz_id ) {

										$is_quiz_notcomplete = learndash_is_quiz_notcomplete( null, array( $quiz_id ) );

										// Redirect to first incomplete quiz in list
										if ( $is_quiz_notcomplete ) {
											$link = get_permalink( $quiz_id );
											break;
										}

									}
								}

								// is courseis complete, redirect to the course page
								$completed_on = get_user_meta( $user_ID, 'course_completed_' . $course_id, true );
								if ( !empty( $completed_on ) ) {
									$link = get_permalink( $course_id );
								}

								/*
                                 * With typical LearnDash functionality, when users click "Mark Complete" on the last topic in a
                                 * lesson, they are automatically advanced to the next lesson.  With "autocomplete" turned on,
                                 * there is no button, and also no link to the next lesson.
                                 *
                                 * Add hidden button to page if its a topic and on click progress to the next lesson or quiz
                                 */
								?>
								<script>

									var uoDoneRedirect = jQuery.noConflict();
									uoDoneRedirect(function ($) {
										var formButton =
											'<form data-uo-redirect="" style="" id="sfwd-mark-complete" class="uo-done-redirect">' +
											'<input type="submit" value="<?php echo __('Done','uncanny-pro-toolkit');?>" id="learndash_mark_complete_button">' +
											'</form>';

										$('#learndash_next_prev_link').before(formButton);

										$('.uo-done-redirect').on('click', function (e) {
											e.preventDefault();
											var link = '<?php echo $link; ?>';
											if ('' !== link) {
												window.location.href = link;
											}

										});
									});
								</script>

								<?php

							}
						}

					}


				}
			}
		}
	}


	/*
	 * Only complete if the Pro Auto complete for lesson/Topic is on
	 * @param object $post_object custom post type lesson object
	 *
	 * @return bool $maybe_complete
	 */
	private static function maybe_complete( $post_object ) {

		$maybe_complete = true;
		//Checking if lesson is available to be marked complete?
		$uncanny_active_classes = get_option( 'uncanny_toolkit_active_classes', '' );
		if ( ! empty( $uncanny_active_classes ) ) {
			if ( key_exists( 'uncanny_pro_toolkit\UncannyDripLessonsByGroup', $uncanny_active_classes ) ) {
				$lesson_access_from = UncannyDripLessonsByGroup::get_lesson_access_from( $post_object->ID, wp_get_current_user()->ID );
				if ( ! empty( $lesson_access_from ) ) {
					$maybe_complete = false;

					return $maybe_complete;
				}
			}
		}

		$feature_auto_complete_default = self::get_settings_value( 'uo_global_auto_complete', 'uncanny_pro_toolkitLessonTopicAutoComplete' );
		$post_options_auto_complete    = learndash_get_setting( $post_object );


		// Is this lesson using auto-complete
		if ( isset( $post_options_auto_complete['uo_auto_complete'] ) ) {

			if ( 'disabled' === $post_options_auto_complete['uo_auto_complete'] ) {
				$maybe_complete = false;
			}

			if ( 'use_globals' === $post_options_auto_complete['uo_auto_complete'] && 'auto_complete_only_lesson_topics_set' === $feature_auto_complete_default ) {
				$maybe_complete = false;
			}

		}

		// Is the lesson topic auto-complete not set
		if ( ! isset( $post_options_auto_complete['uo_auto_complete'] ) ) {

			if ( 'auto_complete_only_lesson_topics_set' === $feature_auto_complete_default ) {
				$maybe_complete = false;
			}

		}

		return $maybe_complete;
	}

	/* Add Auto Complete to LearnDash Options Meta Box
	 * @param array $post_args array of options from the LearnDash custom post type option meta box
	 *
	 * @return array $new_post_args
	 */
	public static function add_auto_complete_to_post_args( $post_args ) {

		// Push existing and new fields
		$new_post_args = array();

		// Loop through all post arguments
		foreach ( $post_args as $key => $val ) {

			// add option on LD post type settings meta box
			if ( in_array( $val['post_type'], self::$auto_completed_post_types ) ) {
				$new_post_args[ $key ]           = $val;
				$new_post_args[ $key ]['fields'] = array();

				//Add new field to top
				$new_post_args[ $key ]['fields']['uo_auto_complete'] = array(
					'name'            => __( 'Auto Complete', 'uncanny-pro-toolkit' ),
					'type'            => 'select',
					'help_text'       => __( 'Automatically complete lesson or topic on page visit', 'uncanny-pro-toolkit' ),
					'initial_options' => array(
						'disabled'    => 'Disabled',
						'enabled'     => 'Enabled',
						'use_globals' => 'Use Global Settings'
					),
					'default'         => 'use_globals'
				);

				// loop through existing fields to get proper placement of new fields
				foreach ( $post_args[ $key ]['fields'] as $field_key => $field_val ) {
					$new_post_args[ $key ]['fields'][ $field_key ] = $field_val;

				}
			} else {
				$new_post_args[ $key ] = $val;
			}
		}

		return $new_post_args;
	}
}