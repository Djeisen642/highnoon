<?php
/**
 * Class ShowAllCourses
 *
 * This class fetches Custom Post Type of Courses
 * created under LearnDash to form a grid view.
 *
 *
 * @package     uncanny_learndash_toolkit
 * @subpackage  uncanny_pro_toolkit\ShowAllCourses
 * @since       1.0.1
 * @since       1.1.0 Added ignore_default_soring, default_sorting
 * @since       1.2.0 Fixed has_shortcode line to include if ! empty $post->ID for 404 pages
 * @since       1.4 added enrolled_only to grid_view_ignore_list() in order to remove courses that user is not enrolled in
 * @since       1.4 added empty courses message in case no enrolled courses found or empty courses
 */

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class ShowAllCourses
 * @package uncanny_pro_toolkit
 */
class ShowAllCourses extends toolkit\Config implements toolkit\RequiredFunctions {
	/**
	 * Class constructor
	 *
	 * @since 1.0.1
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( __CLASS__, 'run_frontend_hooks' ) );
	}

	/*
	 * Initialize frontend actions and filters
	 *
	 * @since 1.0.1
	 */
	public static function run_frontend_hooks() {

		if ( true === self::dependants_exist() ) {
			/* ADD FILTERS ACTIONS FUNCTION */
			if ( ! is_admin() ) {
				add_shortcode( 'uo_courses', array( __CLASS__, 'uo_courses' ) );
			}
			add_filter( 'uo_grid_view_style', array( __CLASS__, 'uo_grid_view_get_style' ), 10, 1 );
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'uo_grid_view_style' ), 99 );
			add_image_size( 'uo_course_image_size', 624, 468, true ); //3X the image we need so that it looks good on mobile view
			add_action( 'wp_footer', array( __CLASS__, 'grid_page_js' ) );
			add_filter( 'learndash_post_args', array( __CLASS__, 'learndash_course_grid_post_args' ), 10, 1 );
		}

	}

	/**
	 * Description of class in Admin View
	 *
	 *
	 * @since 1.0.1
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = esc_html__( 'Enhanced Course Grid', 'uncanny-pro-toolkit' );

		$kb_link = 'http://www.uncannyowl.com/knowledge-base/enhanced-course-grid/';

		/* Sample Simple Description with shortcode */
		$class_description = esc_html__( 'Add a highly customizable grid of LearnDash courses to the front end, learner dashboard or anywhere you want. This is a great tool for sites with a large number of courses.', 'uncanny-pro-toolkit' );

		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-book"></i><span class="uo_pro_text">PRO</span>';

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
			'icon'             => $class_icon,
		);

	}

	/**
	 * Does the plugin rely on another function or plugin
	 *
	 * @since 1.0.1
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
	 * @since 1.0.1
	 *
	 * If there's a shortcode on page, than add stylesheets
	 * else ignore adding on all pages.
	 */
	public static function uo_grid_view_style() {
		global $post;
		if ( ! empty( $post->ID ) && has_shortcode( $post->post_content, 'uo_courses' ) ) {
			wp_enqueue_style( 'course-grid-view-core', plugins_url( '/assets/frontend/css/course-grid-view-core.css', dirname( __FILE__ ) ), array(), UNCANNY_TOOLKIT_PRO_VERSION );
			$grid_view_css = apply_filters( 'uo_grid_view_style', plugins_url( '/assets/frontend/css/course-grid-view.css', dirname( __FILE__ ) ) );
			wp_enqueue_style( 'course-grid-view', $grid_view_css, array(), UNCANNY_TOOLKIT_PRO_VERSION );
		}
	}

	/**
	 *
	 * @since 1.0.1
	 *
	 * @param $style_sheet
	 *
	 * @return string
	 */
	public static function uo_grid_view_get_style( $style_sheet ) {
		$file_path = get_stylesheet_directory() . '/uncanny-toolkit-pro/css/course-grid-view.css';
		$http_path = get_stylesheet_directory_uri() . '/uncanny-toolkit-pro/css/course-grid-view.css';

		if ( file_exists( $file_path ) ) {
			return $http_path;
		} else {
			return $style_sheet;
		}
	}

	/**
	 *
	 * @since 1.0.1
	 * @since 1.1.0 || Added new attributes: ignore_default_sorting which calls a method to generate grid view
	 * @since 1.1.0 || Added new attributes: default_sorting which calls a method to generate grid view
	 *
	 * @param $atts
	 *
	 * @return string || Returns complete grid if courses are found or empty if conditions are not met
	 */
	public static function uo_courses( $atts ) {

		$atts = shortcode_atts(
			array(
				'category'               => 'all',
				//all|category-slug
				'ld_category'            => 'all',
				//all|category-slug
				'enrolled_only'          => 'no',
				//yes|no
				'not_enrolled'           => 'no',
				//yes|no
				'limit'                  => 4,
				//all|3-9
				'cols'                   => 4,
				//3|4|5
				'hide_view_more'         => 'no',
				//yes|no
				'hide_credits'           => 'no',
				//yes|no
				'hide_description'       => 'no',
				//yes|no
				'more'                   => '',
				//''|URL
				'show_image'             => 'yes',
				//yes|no
				'price'                  => 'yes',
				//$|Any
				'currency'               => '$',
				//yes|no
				'link_to_course'         => 'yes',
				//yes|no
				'orderby'                => 'title',
				//date|title|any acceptable WP_Query argument
				'order'                  => 'ASC',
				//ASC|DESC
				'default_sorting'        => 'course-progress,enrolled,not-enrolled,coming-soon,completed',
				//course-progress, enrolled, not-enrolled, coming-soon, completed
				'ignore_default_sorting' => 'no',
				//yes|no
				'border_hover'           => '',
				//''|#HEX
				'view_more_color'        => '',
				//''|#HEX
				'view_more_hover'        => '',
				//''|#HEX
				'view_more_text_color'   => '',
				//''|#HEX
				'view_more_text'         => 'View More <i class="fa fa fa-arrow-circle-right"></i>',
				//View More
				'view_less_text'         => 'View Less <i class="fa fa fa-arrow-circle-right"></i>',
				//View Less
			),
			$atts,
			'uo_courses' );

		$args = array(
			'post_type'      => 'sfwd-courses',
			'post_status'    => 'publish',
			'posts_per_page' => 999,
			'order'          => sanitize_text_field( $atts['order'] ),
			'orderby'        => sanitize_text_field( $atts['orderby'] ),
		);
		if ( isset( $atts['ld_category'] ) && 'all' !== $atts['ld_category'] ) {
			$args['tax_query'] = array(
				'relation' => 'OR',
				array(
					'taxonomy' => 'ld_course_category',
					'field'    => 'slug',
					'terms'    => array( sanitize_text_field( $atts['ld_category'] ) ),
				),
			);
		}
		if ( isset( $atts['category'] ) && 'all' !== $atts['category'] ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => array( sanitize_text_field( $atts['category'] ) ),
			);
		}
		//self::trace_logs( $args, 'Args', 'args' );
		$courses       = get_posts( $args );
		$total_courses = count( $courses );
		$total         = 0;
		$cols          = $atts['cols'];
		$show          = $atts['limit'];
		$ignore        = $atts['ignore_default_sorting'];
		if ( $cols < 3 || $cols > 5 ) {
			$cols = 4;
		}
		if ( 'all' === $atts['limit'] ) {
			$show = 999;
		}
		if ( count( $courses ) > $show && 'all' !== $atts['limit'] ) {
			$total = 1;
		}
		if ( $atts['limit'] < $atts['cols'] ) {
			$total = 0;
		}
		if ( 'yes' === $atts['hide_view_more'] ) {
			$total = 0;
		}

		if ( 'yes' === $ignore ) {
			$grid   = self::grid_view_ignore_list( $courses, $cols, $atts['enrolled_only'] );
			$return = self::build_ignored_view( $grid, $atts, $total, $show, $total_courses );
		} else {
			$grid   = self::grid_view_course_list( $courses, $cols );
			$return = self::build_default_view( $grid, $atts, $total, $show, $total_courses );
		}

		return $return;
	}


	/**
	 *
	 * @since 1.0.1 || Initial release
	 * @since 1.1.0 || Added default sorting so that subset in grid can be re-arranged
	 *
	 * @param $grid
	 * @param $atts
	 * @param $total
	 * @param $show
	 * @param $total_courses
	 *
	 * @return string
	 */
	private static function build_default_view( $grid, $atts, $total, $show, $total_courses ) {
		$grid_wrapper_start = '<div class="uo-grid-wrapper">';
		$grid_wrapper_end   = '</div>';
		$course_progress    = '';
		$enrolled           = '';
		$not_enrolled       = '';
		$coming_soon        = '';
		$completed_course   = '';
		$view_more          = '';
		$default_order      = explode( ',', $atts['default_sorting'] );

		if ( is_array( $default_order ) ) {
			foreach ( $default_order as $order ) {
				$order = trim( $order );
				switch ( $order ) {
					case 'course-progress':
						if ( 'no' === $atts['not_enrolled'] ) {
							if ( count( $grid['course_progress'] ) && $total < $show ) {
								foreach ( $grid['course_progress'] as $key => $value ) {
									$course          = $grid['course_info'][ $key ];
									$course_progress .= self::course_grid_single( $atts, $course, $value['status_icon'], $value['grid_classes'], $value['percentage'], $value['completed'] );
									unset( $grid['course_progress'][ $key ] );
									$total ++;
									if ( (int) $total === (int) $show ) {
										$total ++;
										break;
									}
								}
							}
						}
						break;
					case 'enrolled':
						if ( 'no' === $atts['not_enrolled'] ) {
							if ( count( $grid['enrolled'] ) && $total < $show ) {
								foreach ( $grid['enrolled'] as $key => $value ) {
									$course   = $grid['course_info'][ $key ];
									$enrolled .= self::course_grid_single( $atts, $course, $value['status_icon'], $value['grid_classes'], $value['percentage'], $value['completed'] );
									unset( $grid['enrolled'][ $key ] );
									$total ++;
									if ( (int) $total === (int) $show ) {
										$total ++;
										break;
									}
								}
							}
						}
						break;
					case 'completed':
						if ( count( $grid['completed'] ) && $total < $show ) {
							foreach ( $grid['completed'] as $key => $value ) {
								$course           = $grid['course_info'][ $key ];
								$completed_course .= self::course_grid_single( $atts, $course, $value['status_icon'], $value['grid_classes'], $value['percentage'], $value['completed'] );
								unset( $grid['completed'][ $key ] );
								$total ++;
								if ( (int) $total === (int) $show ) {
									$total ++;
									break;
								}
							}
						}

						break;
					case 'not-enrolled':
						if ( count( $grid['not_enrolled'] ) && $total < $show ) {
							foreach ( $grid['not_enrolled'] as $key => $value ) {
								$course = $grid['course_info'][ $key ];
								if ( 'no' === $atts['link_to_course'] ) {
									$permalink = false;
								} else {
									$permalink = 'course-page';
								}
								$not_enrolled .= self::course_grid_single( $atts, $course, $value['status_icon'], $value['grid_classes'], 0, false, $permalink );
								unset( $grid['not_enrolled'][ $key ] );
								$total ++;
								if ( (int) $total === (int) $show ) {
									$total ++;
									break;
								}
							}
						}

						break;
					case 'coming-soon':
						if ( count( $grid['coming_soon'] ) && $total < $show ) {
							foreach ( $grid['coming_soon'] as $key => $value ) {
								$course = $grid['course_info'][ $key ];
								if ( 'no' === $atts['link_to_course'] ) {
									$permalink = false;
								} else {
									$permalink = 'course-page';
								}
								$coming_soon .= self::course_grid_single( $atts, $course, $value['status_icon'], $value['grid_classes'], 0, false, $permalink );
								unset( $grid['coming_soon'][ $key ] );
								$total ++;
								if ( (int) $total === (int) $show ) {
									$total ++;
									break;
								}
							}
						}

						break;
				}
			}
		}

		$rand = rand( 598, 45451 );

		if ( 'all' !== $show && $total_courses > $show ) {
			$view_more = self::show_view_more( $atts, $grid['view_more']['classes'], $atts['category'] . '-' . $rand, $atts['more'] );
		}

		if ( 'yes' === $atts['hide_view_more'] ) {
			$view_more = '';
		} else {
			$view_more = self::show_view_more( $atts, $grid['view_more']['classes'], $atts['category'] . '-' . $rand, $atts['more'] );
		}
		if ( 999 === (int) $show ) {
			$view_more = '';
		}

		if ( $total_courses == $show ) {
			$view_more = '';
		}

		if ( 'yes' === $atts['enrolled_only'] ) {
			$grid1 = $grid_wrapper_start;
			if ( is_array( $default_order ) ) {
				foreach ( $default_order as $order ) {
					$order = trim( $order );
					switch ( $order ) {
						case 'course-progress':
							$grid1 .= $course_progress;
							break;
						case 'enrolled':
							$grid1 .= $enrolled;
							break;
						case 'completed':
							$grid1 .= $completed_course;
							break;
					}
				}
			}
			$grid1 .= $view_more . $grid_wrapper_end;
		} elseif ( 'yes' === $atts['not_enrolled'] ) {
			$grid1 = $grid_wrapper_start;
			if ( is_array( $default_order ) ) {
				foreach ( $default_order as $order ) {
					$order = trim( $order );
					switch ( $order ) {
						case 'not-enrolled':
							$grid1 .= $not_enrolled;
							break;
						case 'coming-soon':
							$grid1 .= $coming_soon;
							break;
					}
				}
			}
			$grid1 .= $view_more . $grid_wrapper_end;
		} else {
			$grid1 = $grid_wrapper_start;
			if ( is_array( $default_order ) ) {
				foreach ( $default_order as $order ) {
					$order = trim( $order );
					switch ( $order ) {
						case 'course-progress':
							$grid1 .= $course_progress;
							break;
						case 'enrolled':
							$grid1 .= $enrolled;
							break;
						case 'completed':
							$grid1 .= $completed_course;
							break;
						case 'not-enrolled':
							$grid1 .= $not_enrolled;
							break;
						case 'coming-soon':
							$grid1 .= $coming_soon;
							break;
					}
				}
			}
			$grid1 .= $view_more . $grid_wrapper_end;
		}


		if ( 'no' !== $atts['more'] ) {

			$grid_wrapper_start = '<div class="uo-grid-wrapper uo-clear-all" id="' . $atts['category'] . '-' . $rand . '">';
			$grid_wrapper_end   = '</div>';
			$course_progress    = '';
			$enrolled           = '';
			$not_enrolled       = '';
			$coming_soon        = '';
			$completed_course   = '';
			$view_more          = '';

			if ( count( $grid['course_progress'] ) ) {
				foreach ( $grid['course_progress'] as $key => $value ) {
					$course          = $grid['course_info'][ $key ];
					$course_progress .= self::course_grid_single( $atts, $course, $value['status_icon'], $value['grid_classes'], $value['percentage'], $value['completed'] );
					unset( $grid['course_progress'][ $key ] );
				}
			}
			if ( count( $grid['enrolled'] ) ) {
				foreach ( $grid['enrolled'] as $key => $value ) {
					$course   = $grid['course_info'][ $key ];
					$enrolled .= self::course_grid_single( $atts, $course, $value['status_icon'], $value['grid_classes'], $value['percentage'], $value['completed'] );
					unset( $grid['enrolled'][ $key ] );
				}
			}
			if ( count( $grid['not_enrolled'] ) ) {
				foreach ( $grid['not_enrolled'] as $key => $value ) {
					$course = $grid['course_info'][ $key ];
					if ( 'no' === $atts['link_to_course'] ) {
						$permalink = false;
					} else {
						$permalink = 'course-page';
					}
					$not_enrolled .= self::course_grid_single( $atts, $course, $value['status_icon'], $value['grid_classes'], 0, false, $permalink );
					unset( $grid['not_enrolled'][ $key ] );
				}
			}
			if ( count( $grid['coming_soon'] ) ) {
				foreach ( $grid['coming_soon'] as $key => $value ) {
					$course = $grid['course_info'][ $key ];
					if ( 'no' === $atts['link_to_course'] ) {
						$permalink = false;
					} else {
						$permalink = 'course-page';
					}
					$coming_soon .= self::course_grid_single( $atts, $course, $value['status_icon'], $value['grid_classes'], 0, false, $permalink );
					unset( $grid['coming_soon'][ $key ] );
				}
			}
			if ( count( $grid['completed'] ) ) {
				foreach ( $grid['completed'] as $key => $value ) {
					$course           = $grid['course_info'][ $key ];
					$completed_course .= self::course_grid_single( $atts, $course, $value['status_icon'], $value['grid_classes'], $value['percentage'], $value['completed'] );
					unset( $grid['completed'][ $key ] );
				}
			}

			if ( 'yes' === $atts['enrolled_only'] ) {
				$grid2 = $grid_wrapper_start;
				if ( is_array( $default_order ) ) {
					foreach ( $default_order as $order ) {
						$order = trim( $order );
						switch ( $order ) {
							case 'course-progress':
								$grid2 .= $course_progress;
								break;
							case 'enrolled':
								$grid2 .= $enrolled;
								break;
							case 'completed':
								$grid2 .= $completed_course;
								break;
						}
					}
				}
				$grid2 .= $view_more . $grid_wrapper_end;
			} elseif ( 'yes' === $atts['not_enrolled'] ) {
				$grid2 = $grid_wrapper_start;
				if ( is_array( $default_order ) ) {
					foreach ( $default_order as $order ) {
						$order = trim( $order );
						switch ( $order ) {
							case 'not-enrolled':
								$grid2 .= $not_enrolled;
								break;
							case 'coming-soon':
								$grid2 .= $coming_soon;
								break;
						}
					}
				}
				$grid2 .= $view_more . $grid_wrapper_end;
			} else {
				$grid2 = $grid_wrapper_start;
				if ( is_array( $default_order ) ) {
					foreach ( $default_order as $order ) {
						$order = trim( $order );
						switch ( $order ) {
							case 'course-progress':
								$grid2 .= $course_progress;
								break;
							case 'enrolled':
								$grid2 .= $enrolled;
								break;
							case 'completed':
								$grid2 .= $completed_course;
								break;
							case 'not-enrolled':
								$grid2 .= $not_enrolled;
								break;
							case 'coming-soon':
								$grid2 .= $coming_soon;
								break;
						}
					}
				}
				$grid2 .= $view_more . $grid_wrapper_end;
			}

		}

		$style  = self::grid_style( $atts );
		$script = self::grid_js( $atts );

		$semi_grid = $grid1 . $grid2;
		if ( substr_count( $semi_grid, 'grid-course' ) <= $show ) {
			$semi_grid = str_replace( 'uo-view-more-holder', 'uo-view-more-holder hidden ', $semi_grid );
		}

		return $style . $semi_grid . $script;
	}

	/**
	 * @since 1.1.0 || This function generates ignore_default grid view
	 * @since 1.4   || added enrolled_only view, so that it does not show all courses
	 *
	 * @param $grid
	 * @param $atts
	 * @param $total
	 * @param $show
	 * @param $total_courses
	 *
	 * @return string
	 */
	private static function build_ignored_view( $grid, $atts, $total, $show, $total_courses ) {
		$grid_wrapper_start = '<div class="uo-grid-wrapper">';
		$grid_wrapper_end   = '</div>';
		$all_courses        = '';
		$grid1              = '';
		$grid2              = '';
		$view_more          = '';
		if ( count( $grid['all_courses'] ) ) {
			if ( count( $grid['all_courses'] ) && $total < $show ) {
				foreach ( $grid['all_courses'] as $key => $value ) {
					$course      = $grid['course_info'][ $key ];
					$all_courses .= self::course_grid_single( $atts, $course, $value['status_icon'], $value['grid_classes'], $value['percentage'], $value['completed'] );
					unset( $grid['all_courses'][ $key ] );
					$total ++;
					if ( (int) $total === (int) $show ) {
						$total ++;
						break;
					}
				}
			}

			$rand = rand( 598, 45451 );
			if ( 'all' !== $show && $total_courses > $show ) {
				$view_more = self::show_view_more( $atts, $grid['view_more']['classes'], $atts['category'] . '-' . $rand, $atts['more'] );
			}
			if ( 'yes' === $atts['hide_view_more'] ) {
				$view_more = '';
			} else {
				$view_more = self::show_view_more( $atts, $grid['view_more']['classes'], $atts['category'] . '-' . $rand, $atts['more'] );
			}
			if ( 999 === (int) $show ) {
				$view_more = '';
			}

			$grid1 = $grid_wrapper_start . $all_courses . $view_more . $grid_wrapper_end;


			if ( 'no' !== $atts['more'] ) {

				$grid_wrapper_start = '<div class="uo-grid-wrapper uo-clear-all" id="' . $atts['category'] . '-' . $rand . '">';
				$grid_wrapper_end   = '</div>';
				$all_courses        = '';
				$view_more          = '';

				if ( count( $grid['all_courses'] ) ) {
					foreach ( $grid['all_courses'] as $key => $value ) {
						$course      = $grid['course_info'][ $key ];
						$all_courses .= self::course_grid_single( $atts, $course, $value['status_icon'], $value['grid_classes'], $value['percentage'], $value['completed'] );
						unset( $grid['all_courses'][ $key ] );
					}
				}


				$grid2 = $grid_wrapper_start . $all_courses . $view_more . $grid_wrapper_end;

			}
		} else {
			$grid1 = '<h3>';
			$grid1 .= esc_attr__( "Sorry! You don't have any enrolled courses.", 'uncanny-pro-toolkit' );
			$grid1 .= '</h3>';
		}

		$style  = self::grid_style( $atts );
		$script = self::grid_js( $atts );

		$semi_grid = $grid1 . $grid2;
		if ( substr_count( $semi_grid, 'grid-course' ) <= $show ) {
			$semi_grid = str_replace( 'uo-view-more-holder', 'uo-view-more-holder hidden ', $semi_grid );
		}

		return $style . $semi_grid . $script;
	}


	/**
	 * @since 1.1.0 || Returns page inline <style> tag if override attributes are added to shortcode
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	private static function grid_style( $atts ) {
		$style = '<style>';
		if ( ! empty( $atts['border_hover'] ) ) {
			$style .= '.uo-grid-wrapper .grid-course:hover .uo-border{border-color:' . esc_attr( $atts['border_hover'] ) . '}';
		}
		if ( ! empty( $atts['view_more_color'] ) ) {
			$style .= '.uo-view-more a{background-color:' . esc_attr( $atts['view_more_color'] ) . '}';
			$style .= '#ribbon{background-color:' . esc_attr( $atts['view_more_color'] ) . '; box-shadow: 0px 2px 4px ' . esc_attr( $atts['view_more_color'] ) . '}';
		}
		if ( ! empty( $atts['view_more_hover'] ) ) {
			$style .= '.uo-view-more a:hover{background-color:' . esc_attr( $atts['view_more_hover'] ) . '}';
			$style .= '#ribbon:after{border-color:' . esc_attr( $atts['view_more_hover'] ) . ' ' . esc_attr( $atts['view_more_hover'] ) . ' transparent transparent;}';
		}
		if ( ! empty( $atts['view_more_text_color'] ) ) {
			$style .= '.uo-view-more a{color:' . esc_attr( $atts['view_more_text_color'] ) . '}';
		}
		$style .= '</style>';

		return $style;
	}

	/**
	 * @since 1.1.0 || Returns page inline <javascript> tag for View More Animation
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	private static function grid_js( $atts ) {


		ob_start();

		?>

		<script>
			if (typeof uoViewMoreText === 'undefined') {
				// the namespace is not defined
				var uoViewMoreText = true;

				(function ($) { // Self Executing function with $ alias for jQuery

					/* Initialization  similar to include once but since all js is loaded by the browser automatically the all
					 * we have to do is call our functions to initialize them, his is only run in the main configuration file
					 */
					$(document).ready(function () {

						jQuery('.uo-view-more-anchor').click(function () {
							var target = jQuery(jQuery(this).attr('data-target'));
							if (target.length > 0) {
								if (target.is(':visible')) {
									jQuery(this).html('<?php echo $atts['view_more_text']; ?>');
								} else {
									jQuery(this).html('<?php echo $atts['view_less_text']; ?>');
								}
							}
						})

					});
				})(jQuery);
			}
		</script>

		<?php

		return ob_get_clean();

	}

	/**
	 * @since 1.5 || echos <javascript> in the footer.. helpful for multiple
	 * grid implementations on a page.
	 *
	 */
	public static function grid_page_js() {

		ob_start();

		?>

		<script>
			if (typeof uoViewMoreModules === 'undefined') {
				// the namespace is not defined
				var uoViewMoreModules = true;

				(function ($) { // Self Executing function with $ alias for jQuery

					/* Initialization  similar to include once but since all js is loaded by the browser automatically the all
					 * we have to do is call our functions to initialize them, his is only run in the main configuration file
					 */
					$(document).ready(function () {

						jQuery('.uo-view-more-anchor').click(function (e) {
							var target = jQuery(jQuery(this).attr('data-target'));
							if (target.length > 0) {
								console.log(target.is(':visible'));
								if (target.is(':visible')) {
									target.css('display', 'none');
								} else {
									target.css('display', 'flex');
									jQuery('html, body').animate({
										scrollTop: target.offset().top - 250
									}, 2000);
								}
							}
						})

					});
				})(jQuery);
			}
		</script>

		<?php

		echo ob_get_clean();

	}


	/**
	 * @since 1.0.1 || Returns pre-sorted multiple arrays for default_sorting
	 *
	 * @param $courses
	 * @param $show
	 *
	 * @return array
	 */
	private static function grid_view_course_list( $courses, $show ) {
		$is_enrolled      = false;
		$grid_classes     = array( 'grid-course' );
		$enrolled         = array();
		$completed_course = array();
		$not_enrolled     = array();
		$coming_soon      = array();
		$course_progress  = array();
		$percentage_array = array();
		$course_info      = array();

		switch ( $show ) {
			case 3:
				$grid_classes[] = 'uo-col-13';
				$grid_classes[] = 'uo-3-col';
				break;
			case 4:
				$grid_classes[] = 'uo-col-14';
				$grid_classes[] = 'uo-4-col';
				break;
			case 5:
				$grid_classes[] = 'uo-col-15';
				$grid_classes[] = 'uo-5-col';
				break;
			case 6:
				$grid_classes[] = 'uo-col-16';
				$grid_classes[] = 'uo-6-col';
				break;
			default:
				$grid_classes[] = 'uo-col-14';
				$grid_classes[] = 'uo-4-col';
				break;
		}

		foreach ( $courses as $course ) {
			$course_info[ $course->ID ] = (object) array( 'ID' => $course->ID, 'post_title' => $course->post_title );
			$status_icon                = '';
			if ( is_user_logged_in() ) {
				$user_id     = get_current_user_id();
				$is_enrolled = sfwd_lms_has_access( $course->ID, $user_id );
			}

			$status = learndash_course_status( $course->ID );

			if ( 'Completed' === $status ) {
				$status_icon = esc_html__( 'Complete', 'uncanny-pro-toolkit' ) . ' <i class="fa fa-check-circle"></i>';
			} elseif ( has_tag( 'coming-soon', $course->ID ) ) {
				$status_icon = esc_html__( 'Coming Soon', 'uncanny-pro-toolkit' );
			} elseif ( $is_enrolled ) {
				$status_icon = esc_html__( 'Course Status', 'uncanny-pro-toolkit' );
			} elseif ( ! $is_enrolled ) {
				$status_icon = esc_html__( 'View Course Outline', 'uncanny-pro-toolkit' );
			}


			if ( $is_enrolled ) {
				$progress = learndash_course_progress( array(
					'course_id' => $course->ID,
					'array'     => true,
				) );
				if ( 'Completed' === $status ) {
					$completed                       = true;
					$completed_course[ $course->ID ] = array(
						'status_icon'  => $status_icon,
						'grid_classes' => $grid_classes,
						'percentage'   => $progress['percentage'],
						'completed'    => $completed,
					);
				} else {
					$completed = false;
					if ( absint( $progress['percentage'] ) > 0 && absint( $progress['percentage'] ) < 100 ) {
						$percentage_array[ $course->ID ] = $progress['percentage'];
					} elseif ( has_tag( 'coming-soon', $course->ID ) ) {
						$coming_soon[ $course->ID ] = array(
							'status_icon'  => $status_icon,
							'grid_classes' => $grid_classes,
						);
					} else {
						$enrolled[ $course->ID ] = array(
							'status_icon'  => $status_icon,
							'grid_classes' => $grid_classes,
							'percentage'   => $progress['percentage'],
							'completed'    => $completed,
						);
					}
				}
			} else {
				if ( has_tag( 'coming-soon', $course->ID ) ) {
					$coming_soon[ $course->ID ] = array(
						'status_icon'  => $status_icon,
						'grid_classes' => $grid_classes,
					);
				} else {
					$not_enrolled[ $course->ID ] = array(
						'status_icon'  => $status_icon,
						'grid_classes' => $grid_classes,
					);
				}

			}
		}
		if ( is_array( $percentage_array ) ) {
			arsort( $percentage_array );

			foreach ( $percentage_array as $key => $value ) {
				$course                         = get_post( $key );
				$completed                      = false;
				$status_icon                    = __( 'Course Status', 'uncanny-pro-toolkit' );
				$course_progress[ $course->ID ] = array(
					'status_icon'  => $status_icon,
					'grid_classes' => $grid_classes,
					'percentage'   => $value,
					'completed'    => $completed,
				);
			}
		}

		$grid_classes[] = 'uo-view-more';
		$view_more      = array( 'classes' => $grid_classes );

		return array(
			'course_info'     => $course_info,
			'course_progress' => $course_progress,
			'enrolled'        => $enrolled,
			'not_enrolled'    => $not_enrolled,
			'coming_soon'     => $coming_soon,
			'completed'       => $completed_course,
			'view_more'       => $view_more,
		);

	}

	/**
	 * @since 1.1.0 || Returns two arrays, Course Info and All Courses back to grid generator
	 * @since 1.4   || added enrolled_only view, so that it does not add all courses
	 *
	 * @param $courses
	 * @param $show
	 *
	 * @return array
	 */
	private static function grid_view_ignore_list( $courses, $show, $enrolled_only = 'no' ) {
		$is_enrolled  = false;
		$grid_classes = array( 'grid-course' );
		$course_info  = array();
		$all_courses  = array();

		switch ( $show ) {
			case 3:
				$grid_classes[] = 'uo-col-13';
				$grid_classes[] = 'uo-3-col';
				break;
			case 4:
				$grid_classes[] = 'uo-col-14';
				$grid_classes[] = 'uo-4-col';
				break;
			case 5:
				$grid_classes[] = 'uo-col-15';
				$grid_classes[] = 'uo-5-col';
				break;
			case 6:
				$grid_classes[] = 'uo-col-16';
				$grid_classes[] = 'uo-6-col';
				break;
			default:
				$grid_classes[] = 'uo-col-14';
				$grid_classes[] = 'uo-4-col';
				break;
		}

		foreach ( $courses as $course ) {
			$course_info[ $course->ID ] = (object) array( 'ID' => $course->ID, 'post_title' => $course->post_title );
			$status_icon                = '';
			if ( is_user_logged_in() ) {
				$user_id     = wp_get_current_user()->ID;
				$is_enrolled = sfwd_lms_has_access( $course->ID, $user_id );
			}

			$status = learndash_course_status( $course->ID );

			if ( 'Completed' === $status ) {
				$status_icon = esc_html__( 'Complete', 'uncanny-pro-toolkit' ) . ' <i class="fa fa-check-circle"></i>';
			} elseif ( has_tag( 'coming-soon', $course->ID ) ) {
				$status_icon = esc_html__( 'Coming Soon', 'uncanny-pro-toolkit' );
			} elseif ( $is_enrolled ) {
				$status_icon = esc_html__( 'Course Status', 'uncanny-pro-toolkit' );
			} elseif ( ! $is_enrolled ) {
				$status_icon = esc_html__( 'View Course Outline', 'uncanny-pro-toolkit' );
			}

			if ( $is_enrolled ) {
				$progress = learndash_course_progress( array(
					'course_id' => $course->ID,
					'array'     => true,
				) );
				if ( 'Completed' === $status ) {
					$completed                  = true;
					$all_courses[ $course->ID ] = array(
						'status_icon'  => $status_icon,
						'grid_classes' => $grid_classes,
						'percentage'   => $progress['percentage'],
						'completed'    => $completed,
					);
				} else {
					$completed = false;
					if ( absint( $progress['percentage'] ) > 0 && absint( $progress['percentage'] ) < 100 ) {
						$all_courses[ $course->ID ] = array(
							'percentage'   => $progress['percentage'],
							'grid_classes' => $grid_classes,
						);
					} elseif ( has_tag( 'coming-soon', $course->ID ) ) {
						$all_courses[ $course->ID ] = array(
							'status_icon'  => $status_icon,
							'grid_classes' => $grid_classes,
						);
					} else {
						$all_courses[ $course->ID ] = array(
							'status_icon'  => $status_icon,
							'grid_classes' => $grid_classes,
							'percentage'   => $progress['percentage'],
							'completed'    => $completed,
						);
					}
				}
			} else {
				if ( 'no' === $enrolled_only ) {
					if ( has_tag( 'coming-soon', $course->ID ) ) {
						$all_courses[ $course->ID ] = array(
							'status_icon'  => $status_icon,
							'grid_classes' => $grid_classes,
						);
					} else {
						$all_courses[ $course->ID ] = array(
							'status_icon'  => $status_icon,
							'grid_classes' => $grid_classes,
						);
					}
				}
			}
		}


		$grid_classes[] = 'uo-view-more';
		$view_more      = array( 'classes' => $grid_classes );

		return array(
			'course_info' => $course_info,
			'all_courses' => $all_courses,
			'view_more'   => $view_more,
		);

	}

	/**
	 * @since 1.0.1 || Returns a single "block" of grid with all course info
	 * @since 1.1.0 || Added language support to hardcoded Text, i.e., View Course Outline
	 *
	 * @param $atts
	 * @param $course
	 * @param $status_icon
	 * @param $grid_classes
	 * @param int $percentage
	 * @param bool $completed
	 * @param string $permalink
	 *
	 * @return string
	 */
	private static function course_grid_single( $atts, $course, $status_icon, $grid_classes, $percentage = 0, $completed = false, $permalink = 'course-page' ) {
		if ( 'course-page' === $permalink ) {
			$permalink = get_permalink( $course->ID );
		} else {
			$permalink = 'javascript:;';
		}

		$options  = get_option( 'sfwd_cpt_options' );
		$currency = null;
		if ( ! is_null( $options ) ) {
			if ( isset( $options['modules'] ) && isset( $options['modules']['sfwd-courses_options'] ) && isset( $options['modules']['sfwd-courses_options']['sfwd-courses_paypal_currency'] ) ) {
				$currency = $options['modules']['sfwd-courses_options']['sfwd-courses_paypal_currency'];
			}
		}
		if ( is_null( $currency ) ) {
			$paypal_settings = get_option( 'learndash_settings_paypal', '' );
			if ( ! empty( $paypal_settings ) ) {
				if ( ! empty( $paypal_settings['paypal_currency'] ) ) {
					$currency = $paypal_settings['paypal_currency'];
				} else {
					$currency = 'USD';
				}
			} else {
				$currency = 'USD';
			}
		}

		$course_options = get_post_meta( $course->ID, '_sfwd-courses', true );
		$price          = $course_options && isset( $course_options['sfwd-courses_course_price'] ) ? $course_options['sfwd-courses_course_price'] : esc_html__( 'Free', 'uncanny-pro-toolkit' );
		if ( '' === $price ) {
			$price .= 'Free';
		}

		if ( is_numeric( $price ) ) {
			if ( 'USD' === $currency ) {
				$currency = '$';
			}

			//Override Currency Symbol
			if ( ! empty( $atts['currency'] ) && '$' !== $atts['currency'] ) {
				$currency = $atts['currency'];
			}

			$price = sprintf( __( '%1$s %2$s', 'uncanny-pro-toolkit' ), $currency, $price );
		}
		$short_description = '';
		if ( key_exists( 'sfwd-courses_course_short_description', $course_options ) ) {
			$short_description = do_shortcode( $course_options['sfwd-courses_course_short_description'] );
		}
		ob_start();
		?>
		<div class="<?php echo implode( ' ', $grid_classes ) ?>">
			<div class="uo-border<?php if ( $completed ) {
				echo ' completed';
			} ?>">
				<a href="<?php echo $permalink; ?>">
					<?php if ( 'yes' === $atts['price'] && 'yes' === $atts['show_image'] ) { ?>
						<div id="ribbon"
						     class="price  <?php echo ! empty( $course_options['sfwd-courses_course_price'] ) ? "price_" . $currency : esc_html__( 'Free', 'uncanny-pro-toolkit' ) ?>">
							<?php esc_html_e( $price, 'uncanny-pro-toolkit' ); ?>
						</div>
					<?php } ?>
					<?php if ( 'yes' === $atts['show_image'] ) { ?>
						<div class="featured-image">
							<?php if ( has_post_thumbnail( $course->ID ) ) { ?>
								<img src="<?php echo self::resize_grid_image( $course->ID, 'uo_course_image_size' ); ?>"
								     class="uo-grid-featured-image"/>
							<?php } else { ?>
								<img
										src="<?php echo plugins_url( '/assets/frontend/img/no_image.jpg', dirname( __FILE__ ) ) ?>"
										class="uo-grid-featured-image"/>
							<?php } ?>
						</div>
						<?php
					}
					?>
					<div class="course-info-holder<?php if ( $completed ) {
						echo ' completed';
					} ?>">
						<span class="course-title"><?php echo $course->post_title; ?></span>
						<?php
						if ( is_plugin_active( 'uncanny-continuing-education-credits/uncanny-continuing-education-credits.php' ) ) {
							$points = get_post_meta( $course->ID, 'ceu_value', true );
							if ( ( 'no' === $atts['hide_credits'] ) && ! empty( $points ) && $points > 0 ) {
								?>
								<p class="cue-points">
									<?php
									echo $points;
									echo ' ';
									if ( 1 === absint( $points ) ) {
										echo get_option( 'credit_designation_label', __( 'CEU', 'uncanny-ceu' ) );
									} else {
										echo get_option( 'credit_designation_label_plural', __( 'CEUs', 'uncanny-ceu' ) );
									}
									?>
								</p>
								<?php
							}
						}
						?>
						<?php if ( ( 'no' === $atts['hide_description'] ) && $short_description ) {
							?>
							<p><?php echo $short_description ?></p>
							<?php
						} ?>
					</div>
					<div class="course-info-holder<?php if ( $completed ) {
						echo ' completed';
					} ?> bottom">
						<?php if ( 'View Course Outline' !== $status_icon && 'Coming Soon' !== $status_icon ) { ?>
							<h3 class="percentage"><?php echo $percentage ?>%</h3>
							<dd class="uo-course-progress" title="">
								<div class="course_progress" style="width: <?php echo $percentage ?>%;">
								</div>
							</dd>
							<div class="list-tag-container <?php echo sanitize_title( $status_icon ) ?>"><?php echo $status_icon; ?></div>
						<?php } elseif ( 'View Course Outline' !== $status_icon ) { ?>
							<h3 class="percentage"></h3>
							<dd class="uo-course-progress" title="" style="visibility: hidden">
								<div class="course_progress" style="width: 100%;">
								</div>
							</dd>
							<h4><?php esc_html_e( 'Coming Soon', 'uncanny-pro-toolkit' ); ?></h4>
							<div class="list-tag-container <?php echo sanitize_title( 'Coming Soon' ) ?>" style="visibility: hidden">&nbsp;</div>
						<?php } elseif ( 'View Course Outline' === $status_icon ) { ?>
							<h3 class="percentage"></h3>
							<dd class="uo-course-progress" title="" style="visibility: hidden">
								<div class="course_progress" style="width: 100%;">
								</div>
							</dd>
							<h4 class="view-course-outline">
								<?php esc_html_e( 'View Course Outline', 'uncanny-pro-toolkit' ); ?>
							</h4>
							<div class="list-tag-container <?php echo sanitize_title( 'View Course Outline' ) ?>" style="visibility: hidden">&nbsp;</div>
						<?php } ?>
					</div>
				</a>
			</div>
		</div>
		<?php

		return ob_get_clean();
	}


	/**
	 * @since 1.0.1 || Returns View More "block"
	 *
	 * @param $atts
	 * @param $class
	 * @param $category
	 * @param string $more
	 *
	 * @return string
	 */
	private static function show_view_more( $atts, $class, $category, $more = '' ) {
		$url         = 'javascript:;';
		$data_target = "#$category";
		if ( self::is_url( $more ) ) {
			$url         = $more;
			$data_target = '';
		}

		return "<div class=\"uo-view-more uo-view-more-holder " . implode( ' ', $class ) . " \">
				<a class=\"uo-view-more-anchor\" data-target=\"$data_target\" href=\"$url\">
					" . $atts['view_more_text'] . "
				</a>
			</div>";
	}

	/**
	 * @since 1.0.1 || Returns URL of the resized Image cropped as per grid specification
	 *
	 * @param $id
	 * @param $size
	 *
	 * @return mixed
	 */
	private static function resize_grid_image( $id, $size ) {
		$medium_array = image_downsize( get_post_thumbnail_id( $id ), $size );
		$medium_path  = $medium_array[0];

		return $medium_path;
	}

	/**
	 * @since 1.0.1 || Returns true if the $string is a URL
	 *
	 * @param $string
	 *
	 * @return string
	 */
	private static function is_url( $string ) {
		$domain = '[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])'; // one domain component //! IDN

		return ( preg_match( "~^(https?)://($domain?\\.)+$domain(:\\d+)?(/.*)?(\\?.*)?(#.*)?\$~i", $string, $match ) ? strtolower( $match[1] ) : '' ); //! restrict path, query and fragment characters
	}

	public static function learndash_course_grid_post_args( $post_args ) {
		foreach ( $post_args as $key => $post_arg ) {
			if ( 'sfwd-courses' === $post_arg['post_type'] ) {
				$course_short_description    = array(
					'name'      => __( 'Short Description', 'uncanny-pro-toolkit' ),
					'type'      => 'textarea',
					'help_text' => __( 'A short description of the course to show on grid.', 'uncanny-pro-toolkit' ),
				);
				$post_args[ $key ]['fields'] = array( 'course_short_description' => $course_short_description ) + $post_args[ $key ]['fields'];
			}
		}

		return $post_args;
	}

}