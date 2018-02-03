<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Class UncannyDripLessonsByGroup
 * @package uncanny_pro_toolkit
 */
class UncannyDripLessonsByGroup extends toolkit\Config implements toolkit\RequiredFunctions {

	public static $learndash_post_types = array( 'sfwd-lessons' );

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
			add_filter( 'learndash_post_args', array( __CLASS__, 'add_group_access_to_post_args' ) );

			# Change again when the option is called on "Edit Lesson" page
			add_filter( 'sfwd-lessons_display_settings', array( __CLASS__, 'change_lesson_setting' ) );

			# When post is saved
			add_action( 'save_post', array( __CLASS__, 'save_post' ), 50, 3 );

			# Change shortcodes and hooks to show the lesson because there is no hooking point to control it, so I change entire screen
			add_action( 'after_setup_theme', array( __CLASS__, 'change_hooks_and_shortcodes' ), 1 );

			# Call a javascript
			//add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ) );

			#Convert String DateTime to UnixTimeStamp
			add_action( 'admin_init', array( __CLASS__, 'reformat_date_to_unix' ), 999 );
		}

	}

	# Change the shortcode

	/**
	 *
	 */
	public static function change_hooks_and_shortcodes() {
		# Replace the function
		remove_filter( 'learndash_content', 'lesson_visible_after', 1 );
		add_filter( 'learndash_content', array( __CLASS__, 'lesson_visible_after' ), 1, 2 );
		add_filter( 'learndash_template', array( __CLASS__, 'learndash_template' ), 1, 5 );
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
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = esc_html__( 'Drip Lessons by LearnDash Group', 'uncanny-pro-toolkit' );

		$kb_link = 'http://www.uncannyowl.com/knowledge-base/drip-lessons-by-ldgroup/';

		$class_description = esc_html__( 'Unlock access to LearnDash lessons by setting dates for LearnDash Groups rather than for all enrolled users.', 'uncanny-pro-toolkit' );

		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-user-times"></i><span class="uo_pro_text">PRO</span>';

		$tags = 'learndash';
		$type = 'pro';

		return array(
			'title'            => $class_title,
			'type'             => $type,
			'tags'             => $tags,
			'kb_link'          => $kb_link,
			'description'      => $class_description,
			'dependants_exist' => self::dependants_exist(),
			'settings'         => false, // OR
			'icon'             => $class_icon,
		);

	}

	/**
	 *
	 */
	/*public static function admin_enqueue_scripts() {
		global $post;

		if ( 'sfwd-lessons' === $post->post_type ) {
			wp_enqueue_script( stripslashes( __CLASS__ ), plugins_url( 'assets/js/time_limit_lesson_for_group.js', dirname( __FILE__ ) ), array( 'jquery' ) );
		}
	}*/

	/**
	 * @param $post_args
	 *
	 * @return array
	 */
	public static function add_group_access_to_post_args( $post_args ) {
		// Get all groups
		if ( ! is_user_logged_in() ) {
			return $post_args;
		}
		$groups = learndash_get_groups();

		// If any group is not exists, this option will be disabled
		if ( ! $groups ) {
			return $post_args;
		}

		// group_selection
		$group_selection = array(
			0     => 'Select a LearnDash Group',
			'all' => 'All Other Users',
		);

		foreach ( $groups as $group ) {
			if ( $group && is_object( $group ) ) {
				$group_selection[ $group->ID ] = $group->post_title;
			}
		}

		$new_post_args = array();

		foreach ( $post_args as $key => $val ) {
			// add option on lessons setting
			if ( in_array( $val['post_type'], self::$learndash_post_types, true ) ) {
				$new_post_args[ $key ]           = $val;
				$new_post_args[ $key ]['fields'] = array();

				foreach ( $post_args[ $key ]['fields'] as $key_lessons => $val_lessons ) {
					$new_post_args[ $key ]['fields'][ $key_lessons ] = $val_lessons;

					if ( 'visible_after' === $key_lessons ) {
						$new_post_args[ $key ]['fields']['set_groups_for_dates'] = array(
							'name'            => 'LearnDash Group',
							'type'            => 'select',
							'help_text'       => 'Choose a group for a custom drip date',
							'initial_options' => $group_selection,
						);
					}
				}
			} else {
				$new_post_args[ $key ] = $val;
			}
		}

		return $new_post_args;
	}


	# Change again when the option is called on "Edit Lesson" page

	/**
	 * @param $setting
	 *
	 * @return mixed
	 */
	public static function change_lesson_setting( $setting ) {
		// Get the post which are modifying
		global $post;

		foreach ( $setting['sfwd-lessons_set_groups_for_dates']['initial_options'] as $group_id => &$group_name ) {
			if ( ! $group_id ) {
				continue;
			}
			$date = get_post_meta( $post->ID, stripslashes( __CLASS__ ) . '-' . $group_id, true );
			// Add tha ( date ) after group name on selection if exists
			if ( 'all' === $group_id ) {
				$original_option = get_post_meta( $post->ID, '_sfwd-lessons', true );
				if ( key_exists( '', $original_option ) ) {
					$original_date = $original_option['sfwd-lessons_visible_after_specific_date'];
					if ( self::is_timestamp( $original_date ) ) {
						$original_date = learndash_adjust_date_time_display( $original_date );
						$group_name    = $group_name . ' ( ' . $original_date . ' )';
					}

					update_post_meta( $post->ID, stripslashes( __CLASS__ ) . '-all', $original_option['sfwd-lessons_visible_after_specific_date'] );
				}
			} elseif ( $date ) {
				if ( is_array( $date ) ) {
					$date = self::reformat_date( $date );
					$date = learndash_adjust_date_time_display( $date );
				}
				if ( self::is_timestamp( $date ) ) {
					$date_format = get_option( 'date_format' );
					$time_format = get_option( 'time_format' );
					$date        = date_i18n( "$date_format $time_format", $date );
				}
				$group_name = $group_name . ' &mdash; (' . $date . ')';
			}
		}

		return $setting;
	}

	# When post is saved

	/**
	 * @param $post_id
	 * @param $post
	 *
	 * @return bool
	 */
	public static function save_post( $post_id, $post ) {
		// prevent auto saving
		// check user capacity
		// check post type
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return true;
		}
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return true;
		}
		if ( 'sfwd-lessons' !== $post->post_type ) {
			return true;
		}

		// if group was set, save it
		$group_id = $_POST['sfwd-lessons_set_groups_for_dates'];
		//self::trace_logs( $_REQUEST, 'Request', 'request' );
		if ( ! empty( $group_id ) ) {
			$date = self::reformat_date( $_POST['sfwd-lessons_visible_after_specific_date'] );
			//self::trace_logs( $date, 'Date', 'request' );
			if ( 0 === $date ) {
				delete_post_meta( $post_id, stripslashes( __CLASS__ ) . '-' . $group_id );
			} else {
				update_post_meta( $post_id, stripslashes( __CLASS__ ) . '-' . $group_id, $date );
			}
		}

		// get original options and reset it
		$original_option                                             = get_post_meta( $post_id, '_sfwd-lessons', true );
		$original_date                                               = get_post_meta( $post_id, stripslashes( __CLASS__ ) . '-all', true );
		$original_option['sfwd-lessons_set_groups_for_dates']        = '';
		$original_option['sfwd-lessons_visible_after_specific_date'] = $original_date;

		update_post_meta( $post_id, '_sfwd-lessons', $original_option );

		return true;
	}

	/**
	 * @param $date
	 *
	 * @return array|false|int
	 */
	public static function reformat_date( $date ) {
		if ( is_array( $date ) ) {
			if ( isset( $date['aa'] ) ) {
				$date['aa'] = intval( $date['aa'] );
			} else {
				$date['aa'] = 0;
			}

			if ( isset( $date['mm'] ) ) {
				$date['mm'] = intval( $date['mm'] );
			} else {
				$date['mm'] = 0;
			}

			if ( isset( $date['jj'] ) ) {
				$date['jj'] = intval( $date['jj'] );
			} else {
				$date['jj'] = 0;
			}

			if ( isset( $date['hh'] ) ) {
				$date['hh'] = intval( $date['hh'] );
			} else {
				$date['hh'] = 0;
			}

			if ( isset( $date['mn'] ) ) {
				$date['mn'] = intval( $date['mn'] );
			} else {
				$date['mn'] = 0;
			}

			if ( ( ! empty( $date['aa'] ) ) && ( ! empty( $date['mm'] ) ) && ( ! empty( $date['jj'] ) ) ) {

				$date_string = sprintf( '%04d-%02d-%02d %02d:%02d:00', intval( $date['aa'] ), intval( $date['mm'] ), intval( $date['jj'] ), intval( $date['hh'] ), intval( $date['mn'] ) );

				$date_string_gmt = get_gmt_from_date( $date_string, 'Y-m-d H:i:s' );

				return strtotime( $date_string_gmt );
			} else {
				return 0;
			}
		} else {
			return $date;
		}
	}

	# Change the template as one in template dir of this plugin

	/**
	 * @param $filepath
	 * @param $name
	 * @param $args
	 * @param $echo
	 * @param $return_file_path
	 *
	 * @return string
	 */
	public static function learndash_template( $filepath, $name, $args, $echo, $return_file_path ) {

		if ( 'course' === $name ) {
			$filepath = dirname( dirname( __FILE__ ) ) . '/templates/drip-template.php';
		}

		return $filepath;
	}

	# Access Permission Change for Single Page

	/**
	 * @param $content
	 * @param $post
	 *
	 * @return string
	 */
	public static function lesson_visible_after( $content, $post ) {
		if ( empty( $post->post_type ) ) {
			return $content;
		}

		$user = wp_get_current_user();
		if ( in_array( 'administrator', $user->roles ) ) {
			return $content;
		}
		$uncanny_active_classes = get_option( 'uncanny_toolkit_active_classes', '' );
		if ( ! empty( $uncanny_active_classes ) ) {
			if ( key_exists( 'uncanny_pro_toolkit\GroupLeaderAccess', $uncanny_active_classes ) ) {
				$course_id         = learndash_get_course_id( $post->ID );
				$get_course_groups = learndash_get_course_groups( $course_id );
				$groups_of_leader  = learndash_get_administrators_group_ids( $user->ID );
				$matching          = array_intersect( $groups_of_leader, $get_course_groups );
				if ( in_array( 'group_leader', $user->roles ) && ! empty( $matching ) ) {
					return $content;
				}
			}
		}


		if ( 'sfwd-lessons' === $post->post_type ) {
			$lesson_id = $post->ID;
		} elseif ( 'sfwd-topic' === $post->post_type || 'sfwd-quiz' === $post->post_type ) {
			$lesson_id = learndash_get_setting( $post, 'lesson' );
			if ( empty( $lesson_id ) ) {
				return $content;
			}
		} else {
			return $content;
		}

		// Compare Two of Dates and return minimum value
		$lesson_access_from = self::get_lesson_access_from( $lesson_id, $user->ID );

		if ( empty ( $lesson_access_from ) ) {
			return $content;
		} else {
			$content     = sprintf( __( 'Available on: %s', 'uncanny-pro-toolkit' ), learndash_adjust_date_time_display( $lesson_access_from ) . ' <br><br>' );
			$course_id   = learndash_get_course_id( $lesson_id );
			$course_link = get_permalink( $course_id );
			$content     .= '<a href="' . esc_url( $course_link ) . '">' . esc_html__( 'Return to Course Overview', 'uncanny-pro-toolkit' ) . '</a>';

			return '<div class=\'notavailable_message\'>' . apply_filters( 'leardash_lesson_available_from_text', $content, $post, $lesson_access_from ) . '</div>';
		}
	}

	# Access Permission for user's group

	/**
	 * @param $lesson_id
	 * @param $user_id
	 *
	 * @return bool|string
	 */
	private static function ld_lesson_access_group( $lesson_id, $user_id ) {
		$user_groups = learndash_get_users_group_ids( $user_id );
		//No group found, assumption: Available
		if ( empty( $user_groups ) ) {
			return 'Available';
		}

		$group_dates = array();
		foreach ( $user_groups as $group_id ) {
			$date = get_post_meta( $lesson_id, stripslashes( __CLASS__ ) . '-' . $group_id, true );
			if ( ! empty( $date ) ) {
				echo self::attempt_to_unix( $date );
				if ( self::is_timestamp( $date ) ) {
					$group_dates[ $group_id ] = $date;
				} else {
					$group_dates[ $group_id ] = strtotime( $date );
				}
			}
		}

		//Array contains Group Dates!
		asort( $group_dates );
		$return = false;
		if ( ! empty( $group_dates ) ) {
			foreach ( $user_groups as $group_id ) {
				if ( ! empty( $group_dates[ $group_id ] ) && time() < $group_dates[ $group_id ] ) {
					$return = false;
				} elseif ( ! empty( $group_dates[ $group_id ] ) && time() >= $group_dates[ $group_id ] ) {
					return 'Available';
				}
			}
		} else {
			//No Group Dates found
			return 'Available';
		}

		if ( false === $return ) {
			foreach ( $group_dates as $date ) {
				return $date;
			}
		}

		return false;
	}

	# It will use in the course template so I put this on here as public method

	/**
	 * @param $lesson_id
	 * @param $user_id
	 *
	 * @return bool|string
	 */
	public static function get_lesson_access_from( $lesson_id, $user_id ) {
		$lesson_access_from = ld_lesson_access_from( $lesson_id, $user_id );
		// Check Group Access As Well
		$lesson_access_group = self::ld_lesson_access_group( $lesson_id, $user_id );
		// Compare Two of Them without null, and return maximum value
		if ( empty( $lesson_access_group ) ) {
			return $lesson_access_from;
		}

		//Group access is available
		if ( 'Available' === $lesson_access_group ) {
			$user_groups = learndash_get_users_group_ids( $user_id );
			//If user is not part of any group, and
			// global drip date is set, use that
			if ( empty( $user_groups ) && ! empty( $lesson_access_from ) ) {
				return $lesson_access_from;
			}

			return false;
		}

		return $lesson_access_group;
	}

	/**
	 * @param $string
	 *
	 * @return bool
	 */
	public static function is_timestamp( $timestamp ) {
		if ( is_numeric( $timestamp ) && strtotime( date( 'd-m-Y H:i:s', $timestamp ) ) === (int) $timestamp ) {
			return $timestamp;
		} else {
			return false;
		}
	}


	/**
	 *
	 */
	public static function reformat_date_to_unix() {
		if ( 'no' === get_option( 'group_drip_date_modified_to_unix', 'no' ) ) {
			global $wpdb;
			$groups = $wpdb->get_results( "SELECT * FROM {$wpdb->postmeta} WHERE meta_key LIKE '" . stripslashes( __CLASS__ ) . "%'" );
			// If any group is not exists, this option will be disabled
			if ( ! empty( $groups ) ) {
				// group_selection
				foreach ( $groups as $group ) {
					$post_id      = $group->post_id;
					$key          = $group->meta_key;
					$current_date = $group->meta_value;
					self::trace_logs( $current_date, '$current_date', 'unix' );
					if ( ! empty( $current_date ) && 0 !== $current_date ) {
						if ( false === self::is_timestamp( $current_date ) ) {
							//attempt to convert to unix timestamp
							if ( is_array( maybe_unserialize( $current_date ) ) ) {
								$date_format  = get_option( 'date_format' );
								$time_format  = get_option( 'time_format' );
								$current_date = date( "$date_format $time_format", self::reformat_date( $current_date ) );
							}
							self::trace_logs( $current_date, '$current_date_after_array_check', 'unix' );
							$unix_time = self::attempt_to_unix( $current_date );
							self::trace_logs( $unix_time, '$unix_time', 'unix' );
							if ( false !== $unix_time ) {
								//DateTime was able to convert it to unix time, all good
								update_post_meta( $post_id, $key, $unix_time );
								$bak = str_replace( stripslashes( __CLASS__ ), 'bak-UncannyDripLessonsByGroup', $key );
								update_post_meta( $post_id, $bak, $current_date ); //keep a backup, Just-in-case
							}
						}
					}
				}
			}
			update_option( 'group_drip_date_modified_to_unix', 'yes' );
		}
	}

	/**
	 * @param $date
	 *
	 * @return bool
	 */
	public static function attempt_to_unix( $date ) {
		try {
			$date = new \DateTime( $date );

			return $date->getTimestamp();
		} catch ( \Exception $e ) {
			return false;
		}
	}
}