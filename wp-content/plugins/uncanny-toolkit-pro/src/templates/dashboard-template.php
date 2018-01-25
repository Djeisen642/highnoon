<?php
/*
 * The template for displaying user courses on page by using [uo_dashboard] shortcode.
 *
 * This template can be overridden by adding absolute path of your template file by
 * using apply_filters( 'uo-dashboard-template', 'your_function_name' ) in functions.php
 * or copy this template to yourtheme/uo-plugin-pro/dashboard-template.php.
 *
 * Available Variables:
 * $user_id 		: Current User ID
 * $current_user 	: (object) Currently logged in user object
 * $user_courses 	: Array of course ID's of the current user
 * $quiz_attempts 	: Array of quiz attempts of the current user
 *
 * @author  UncannyOwl
 * @package uo-plugin-pro/src/templates
 * @version 1.0
 *
 */
$allowed_html = array(
	'a'      => array(
		'href'  => array(),
		'id'    => array(),
		'title' => array(),
		'class' => array()
	),
	'p'      => array(
		'class' => array(),
		'id'    => array()
	),
	'div'    => array(
		'class' => array(),
		'id'    => array()
	),
	'span'   => array(
		'class' => array(),
		'id'    => array()
	),
	'strong' => array(),
);

$uo_dashboard_heading = apply_filters( 'uo_dashboard_heading', 'Registered ' . LearnDash_Custom_Label::get_label( 'courses' ) );

// Add Statistics Modal Window
if ( class_exists( 'SFWD_LMS' ) ) {
	if ( !isset( $learndash_assets_loaded['scripts']['learndash_template_script_js'] ) ) {
		$filepath = SFWD_LMS::get_template( 'learndash_template_script.js', null, null, true );
		if ( !empty( $filepath ) ) {
			wp_enqueue_script( 'learndash_template_script_js', str_replace( ABSPATH, '/', $filepath ), array( 'jquery' ), LEARNDASH_VERSION, true );
			$learndash_assets_loaded['scripts']['learndash_template_script_js'] = __FUNCTION__;

			$data = array();
			$data['ajaxurl'] = admin_url('admin-ajax.php');
			$data = array( 'json' => json_encode( $data ) );
			wp_localize_script( 'learndash_template_script_js', 'sfwd_data', $data );
		}
	}
	LD_QuizPro::showModalWindow();
}
?>

<div class="expand_collapse">
	<a href="#" onClick="return flip_expand_all('#course_list');">
		<?php esc_html_e( 'Expand All', 'uncanny-pro-toolkit' ); ?>
	</a> | <a href="#" onClick="return flip_collapse_all('#course_list');">
		<?php esc_html_e( 'Collapse All', 'uncanny-pro-toolkit' ); ?>
	</a>
</div>


<div id="learndash_profile" class='learndash dashboard'>

	<div class="learndash_profile_heading clear_both">
		<span><?php esc_html_e( $uo_dashboard_heading, 'uncanny-pro-toolkit' ); ?></span>
		<span class="ld_profile_status"><?php esc_html_e( 'Status', 'uncanny-pro-toolkit' ); ?></span>
	</div>
	<div id="course_list">
		<?php
		if ( ! empty( $user_courses ) ) {
			foreach ( $user_courses as $course_id ) {
				$course      = get_post( $course_id );
				$course_link = get_permalink( $course_id );
				$progress    = learndash_course_progress( array(
					'user_id'   => $user_id,
					'course_id' => $course_id,
					'array'     => true,
				) );
				$status      = $status_icon = ( 100 === $progress['percentage'] ) ? 'completed' : 'notcompleted';
				?>
				<div id="course-<?php echo absint( $course->ID ); ?>">
					<div class="list_arrow collapse flippable"
					     onClick="return flip_expand_collapse('#course', <?php echo absint( $course->ID ); ?>);"></div>
					<h4 style="position:relative">
						<?php
						$certificateLink = learndash_get_course_certificate_link( $course->ID, $user_id );
						if ( ! empty( $certificateLink ) ) {
							$status_icon = 'certificate_icon_large';
						}
						?>
						<a class="<?php echo esc_html( $status_icon ); ?>"

						   href="<?php echo esc_url( $course_link ); ?>">
							<div class="left">
								<?php echo $course->post_title; ?>
							</div>
							<dd class="course_progress"
							    title="<?php echo wp_kses( sprintf( '%s out of %s steps completed', $progress['completed'], $progress['total'] ), $allowed_html ); ?>">
								<div class="course_progress_blue"
								     style="width: <?php echo absint( $progress['percentage'] ); ?>%;"></div>
							</dd>
							<div class="right">
								<?php echo wp_kses( sprintf( '%s%%', $progress['percentage'] ), $allowed_html ); ?>
							</div>

						</a>
						<?php
						$certificateLink = learndash_get_course_certificate_link( $course->ID, $user_id );
						if ( ! empty( $certificateLink ) ) {
							echo '<a href="'.$certificateLink.'" class="dashboard-cert-link"></a>';
						}
						?>

						<div class="flip" style="display:none;">

							<?php
							$lessons = learndash_get_course_lessons_list( $course );
							/* Show Lesson List */
							if ( ! empty( $lessons ) ) {
								$lesson_topics = array();
								$has_topics    = false;
								foreach ( $lessons as $lesson ) {
									$lesson_topics[ $lesson['post']->ID ] = learndash_topic_dots( $lesson['post']->ID, false, 'array' );
									if ( ! empty( $lesson_topics[ $lesson['post']->ID ] ) ) {
										$has_topics = true;
									}
								}
								?>
								<div id="learndash_lessons">
									<div id="lessons_list">
										<?php foreach ( $lessons as $lesson ) { ?>
											<div id="post-<?php echo absint( $lesson['post']->ID ); ?>"
											     class="<?php echo wp_kses( $lesson['sample'], $allowed_html ); ?>">
												<h4>
													<a class="<?php echo wp_kses( $lesson['status'], $allowed_html ); ?>"
													   href="<?php echo esc_url( $lesson['permalink'] ) ?>"><?php echo wp_kses( $lesson['post']->post_title, $allowed_html ); ?>
														<?php
														/* Not available message for drip feeding lessons */
														if ( ! empty( $lesson['lesson_access_from'] ) ) {
															$lesson_available_from  = ld_lesson_access_from( $lesson['post']->ID, wp_get_current_user()->ID );
															$uncanny_active_classes = get_option( 'uncanny_toolkit_active_classes', '' );

															if ( ! empty( $uncanny_active_classes ) ) {
																if ( key_exists( 'uncanny_pro_toolkit\UncannyDripLessonsByGroup', $uncanny_active_classes ) ) {
																	$uo_lesson_id = learndash_get_lesson_id( $lesson['post']->ID );
																	if ( empty( $uo_lesson_id ) ) {
																		$uo_lesson_id = $lesson['post']->ID;
																	}
																	$lesson_access_from = uncanny_pro_toolkit\UncannyDripLessonsByGroup::get_lesson_access_from( $uo_lesson_id, wp_get_current_user()->ID );
																	if ( ! empty( $lesson_access_from ) ) {
																		$lesson_available_from = $lesson_access_from;
																	}
																}
															}
															?>
															<small class="notavailable_message"> <?php echo wp_kses( sprintf( ' Available on: %s ', date( 'd-M-Y', $lesson_available_from ) ), $allowed_html ); ?> </small>
														<?php }
														?></a>
													<?php
													/* Lesson Topics */
													$topics = $lesson_topics[ $lesson['post']->ID ];
													if ( ! empty( $topics ) ) {
														?>
														<div id="learndash_lesson_topics_list">
															<div
																id="learndash_topic_dots-<?php echo absint( $lesson['post']->ID ); ?>"
																class="learndash_topic_dots type-list">
																<ul>
																	<?php
																	foreach ( $topics as $key => $topic ) {
																		$completed_class = empty( $topic->completed ) ? 'topic-notcompleted' : 'topic-completed';
																		?>
																		<li>
                                                                            <span class="topic_item">
                                                                                <a class="<?php echo wp_kses( $completed_class, $allowed_html ); ?>"
                                                                                   href="<?php echo esc_url( get_permalink( $topic->ID ) ); ?>"
                                                                                   title="<?php echo wp_kses( $topic->post_title, $allowed_html ); ?>">
                                                                                    <span><?php echo wp_kses( $topic->post_title, $allowed_html ); ?></span>
                                                                                </a>
                                                                            </span>
																		</li>
																	<?php } ?>
																</ul>
																<!--End #learndash_topic_dots-->
															</div>
														</div>
													<?php } ?>
												</h4>
												<!--End #post-->
											</div>
										<?php } ?>
										<!--End #lessons_list-->
									</div>
									<!--End #learndash_lessons-->
								</div>
							<?php } ?>
							<?php
							$quizzes = learndash_get_course_quiz_list( $course );
							if ( ! empty( $quizzes ) ) {
								?>
								<div id='learndash_quizzes'>

									<div id="quiz_list">
										<?php foreach ( $quizzes as $quiz ) { ?>
											<div id="post-<?php echo absint( $quiz['post']->ID ); ?>"
											     class="<?php echo wp_kses( $quiz['sample'], $allowed_html ); ?>">
												<h4><a class="<?php echo wp_kses( $quiz['status'], $allowed_html ); ?>"
												       href="<?php echo esc_url( $quiz['permalink'] ) ?>">[<?php echo LearnDash_Custom_Label::get_label( 'quiz' ); ?>
														] <?php echo wp_kses( $quiz['post']->post_title, $allowed_html ); ?></a>
												</h4>
											</div>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
							<?php if ( ! empty( $quiz_attempts[ $course_id ] ) ) { ?>
								<div class="learndash_profile_quizzes clear_both">
									<div class="learndash_profile_quiz_heading">
										<div
											class="quiz_title">
											<strong><?php esc_html_e( 'Results', 'uncanny-pro-toolkit' ); ?></strong>
										</div>
										<div
											class="certificate"><?php esc_html_e( 'Certificate', 'uncanny-pro-toolkit' ); ?></div>
										<div class="scores"><?php esc_html_e( 'Score', 'uncanny-pro-toolkit' ); ?></div>
										<div class="statistics"><?php _e( 'Statistics', 'uncanny-pro-toolkit' ); ?></div>
										<div
											class="quiz_date"><?php esc_html_e( 'Date', 'uncanny-pro-toolkit' ); ?></div>
									</div>
									<?php
									foreach ( $quiz_attempts[ $course_id ] as $k => $quiz_attempt ) {

//                                        if (array_key_exists('certificate', $quiz_attempt)) {
//                                            $certificate_link = $quiz_attempt['certificate']['certificate_link'];
//                                        }
										//-

										if ( ( isset( $quiz_attempt['has_graded'] ) ) && ( true === $quiz_attempt['has_graded'] ) && ( true === LD_QuizPro::quiz_attempt_has_ungraded_question( $quiz_attempt ) ) ) {
											$status = 'pending';
										} else {
											$certificateLink = @$quiz_attempt['certificate']['certificateLink'];
											$status          = empty( $quiz_attempt['pass'] ) ? 'failed' : 'passed';
										}
										//-

										$status     = empty( $quiz_attempt['pass'] ) ? 'failed' : 'passed';
										$quiz_title = ! empty( $quiz_attempt['post']->post_title ) ? $quiz_attempt['post']->post_title : $quiz_attempt['quiz_title'];
										$quiz_link  = ! empty( $quiz_attempt['post']->ID ) ? get_permalink( $quiz_attempt['post']->ID ) : '#';
										if ( ! empty( $quiz_title ) ) {
											?>
											<div class="<?php echo wp_kses( $status, $allowed_html ); ?>">
												<div class="quiz_title"><span
														class="<?php echo wp_kses( $status, $allowed_html ); ?>_icon"></span><a
														href="<?php echo esc_url( $quiz_link ); ?>"><?php echo wp_kses( $quiz_title, $allowed_html ); ?></a>
												</div>
												<div class="certificate">
													<?php if ( ! empty( $certificateLink ) ) { ?>
														<a href="<?php echo esc_url( $certificateLink ); ?>&time=<?php echo wp_kses( $quiz_attempt['time'], $allowed_html ) ?>"
														   target="_blank">
															<div class="certificate_icon"></div>
														</a>
														<?php
													} else {
														esc_html_e( '-', 'uncanny-pro-toolkit' );
													}
													?>
												</div>
												<div
													class="scores"><?php echo wp_kses( round( $quiz_attempt['percentage'], 2 ), $allowed_html ); ?>
													%
												</div>

												<div class="statistics">
													<?php
													if ( ( ( $user_id == get_current_user_id() ) || ( learndash_is_admin_user() ) || ( learndash_is_group_leader_user() ) ) && ( isset( $quiz_attempt['statistic_ref_id'] ) ) && ( ! empty( $quiz_attempt['statistic_ref_id'] ) ) ) {
														/**
														 * @since 2.3
														 * See snippet on use of this filter https://bitbucket.org/snippets/learndash/5o78q
														 */
														if ( apply_filters( 'show_user_profile_quiz_statistics',
															get_post_meta( $quiz_attempt['post']->ID, '_viewProfileStatistics', true ), $user_id, $quiz_attempt, basename( __FILE__ ) ) ) {

															?>
														<a class="user_statistic" data-statistic_nonce="<?php echo wp_create_nonce( 'statistic_nonce_' . $quiz_attempt['statistic_ref_id'] . '_' . get_current_user_id() . '_' . $user_id ); ?>" data-user_id="<?php echo $user_id ?>" data-quiz_id="<?php echo $quiz_attempt['pro_quizid'] ?>" data-ref_id="<?php echo intval( $quiz_attempt['statistic_ref_id'] ) ?>" href="#">
																<div class="statistic_icon"></div></a><?php
														}
													}
													?>
												</div>

												<div
													class="quiz_date"><?php echo wp_kses( date_i18n( 'd-M-Y', $quiz_attempt['time'] ), $allowed_html ) ?></div>
											</div>
											<?php
										}
									}
									?>
								</div>
							<?php } ?>
						</div>
						<!--End .flip -->
					</h4>
				</div>
				<?php
			}
		}
		?>
		<!--End #course-->
	</div>
	<!--End #course_list-->
</div>
<!--End #learndash_profile-->
