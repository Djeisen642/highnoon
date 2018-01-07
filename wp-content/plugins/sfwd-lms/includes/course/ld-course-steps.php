<?php

if (!class_exists('Learndash_Course_Steps' ) ) {
	class Learndash_Course_Steps {

		function get_course_steps( $course_id = 0 ) {

			$steps = array();
			$steps['h'] = array();
			$steps['l'] = array();
			$steps['ids'] = array();
		
			if ( !empty( $course_id ) ) {
		
				$course_settings = learndash_get_setting( $course_id );
				if ( !is_array( $course_settings ) ) {
					if ( !empty( $course_settings ) ) 
						$course_settings = array( $course_settings );
					else
						$course_settings = array();
				}
				$lesson_settings = sfwd_lms_get_post_options( 'sfwd-lessons' );
				//error_log('lesson_options<pre>'. print_r($lesson_options, true) .'<pre>');
		
				if ( ( !isset( $course_settings['course_lesson_order'] ) ) || ( empty( $course_settings['course_lesson_order'] ) ) ) {
					if ( ( isset( $lesson_settings['order'] ) ) && ( !empty( $lesson_settings['order'] ) ) ) {
						$course_settings['course_lesson_order'] = $lesson_settings['order'];
					}
				}

				if ( ( !isset( $course_settings['course_lesson_orderby'] ) ) || ( empty( $course_settings['course_lesson_orderby'] ) ) ) {
					if ( ( isset( $lesson_settings['orderby'] ) ) && ( !empty( $lesson_settings['orderby'] ) ) ) {
						$course_settings['course_lesson_orderby'] = $lesson_settings['orderby'];
					}
				}
		
				if ( ( !isset( $course_settings['course_lesson_per_page'] ) ) || ( empty( $course_settings['course_lesson_per_page'] ) ) ) {
					if ( ( isset( $lesson_settings['posts_per_page'] ) ) && ( !empty( $lesson_settings['posts_per_page'] ) ) ) {
						$course_settings['course_lesson_per_page'] = $lesson_settings['posts_per_page'];
					}
				}
		
		
				// Course > Lessons
				$lesson_steps_query_args = array(
					'post_type' 		=> 'sfwd-lessons',
					'posts_per_page' 	=> 	-1,
					'post_status' 		=> 	'publish',
					'fields'			=>	'ids',
					'orderby' 			=> 	$course_settings['course_lesson_orderby'], 
					'order' 			=> 	$course_settings['course_lesson_order'],
					'meta_query' 		=> 	array(
						array(
							'key'     	=> 'course_id',
							'value'   	=> intval( $course_id ),
							'compare' 	=> '=',
							'type'		=>	'NUMERIC'
						)
					)
				);

				$lesson_steps_query = new WP_Query( $lesson_steps_query_args );
				if ( ( $lesson_steps_query instanceof WP_Query ) && ( property_exists( $lesson_steps_query, 'posts' ) ) ) {

					foreach( $lesson_steps_query->posts as $lesson_id ) {
						$steps['h']['sfwd-lessons'][$lesson_id] = array();
						$steps['h']['sfwd-lessons'][$lesson_id]['sfwd-topic'] = array();
						$steps['h']['sfwd-lessons'][$lesson_id]['sfwd-quiz'] = array();
						$steps['l']['sfwd-lessons:'. $lesson_id] = $lesson_id; 
						$steps['ids'][$lesson_id] = $lesson_id;
				
						// Course > Lesson > Topics
						$topic_steps_query_args = array(
							'post_type' 		=> 'sfwd-topic',
							'posts_per_page' 	=> 	-1,
							'post_status' 		=> 	'publish',
							'fields'			=>	'ids',
							'orderby' 			=> 	$course_settings['course_lesson_orderby'], 
							'order' 			=> 	$course_settings['course_lesson_order'],
							'meta_query' 		=> 	array(
								array(
									'key'     	=> 'course_id',
									'value'   	=> intval( $course_id ),
									'compare' 	=> '=',
									'type'		=>	'NUMERIC'
								),
								array(
									'key'     	=> 'lesson_id',
									'value'   	=> intval( $lesson_id ),
									'compare' 	=> '=',
									'type'		=>	'NUMERIC'
								),
								'relation' => 'AND',
							)
						);
				
						$topic_steps_query = new WP_Query( $topic_steps_query_args );
						if ( ( $topic_steps_query instanceof WP_Query ) && ( property_exists( $topic_steps_query, 'posts' ) ) ) {
							foreach( $topic_steps_query->posts as $topic_id ) {
								$steps['h']['sfwd-lessons'][$lesson_id]['sfwd-topic'][$topic_id] = array();
								$steps['h']['sfwd-lessons'][$lesson_id]['sfwd-topic'][$topic_id]['sfwd-quiz'] = array();
								$steps['l']['sfwd-lessons:'. $lesson_id .':sfwd-topic:'. $topic_id] = $topic_id; 
								$steps['ids'][$topic_id] = $topic_id;
								
								// Course > Lesson > Topic > Quizzes
								$topic_quiz_steps_query_args = array(
									'post_type' 		=> 'sfwd-quiz',
									'posts_per_page' 	=> 	-1,
									'post_status' 		=> 	'publish',
									'fields'			=>	'ids',
									'orderby' 			=> 	$course_settings['course_lesson_orderby'], 
									'order' 			=> 	$course_settings['course_lesson_order'],
									'meta_query' 		=> 	array(
										array(
											'key'     	=> 'course_id',
											'value'   	=> intval( $course_id ),
											'compare' 	=> '=',
											'type'		=>	'NUMERIC'
										),
										array(
											'key'     	=> 'lesson_id',
											'value'   	=> intval( $topic_id ),
											'compare' 	=> '=',
											'type'		=>	'NUMERIC'
										),
										'relation' => 'AND',
									)
								);
						
								$topic_quiz_steps_query = new WP_Query( $topic_quiz_steps_query_args );
								if ( ( $topic_quiz_steps_query instanceof WP_Query ) && ( property_exists( $topic_quiz_steps_query, 'posts' ) ) ) {
									foreach( $topic_quiz_steps_query->posts as $quiz_id ) {
										$steps['h']['sfwd-lessons'][$lesson_id]['sfwd-topic'][$topic_id]['sfwd-quiz'][$quiz_id] = array();
										$steps['l']['sfwd-lessons:'. $lesson_id .':sfwd-topic:'. $topic_id .':sfwd-quiz:'. $quiz_id] = $quiz_id; 
										$steps['ids'][$quiz_id] = $quiz_id;
									}
								}
							}
						}
				
				
						// Course > Lesson > Quizzes
						$lesson_quiz_steps_query_args = array(
							'post_type' 		=> 'sfwd-quiz',
							'posts_per_page' 	=> 	-1,
							'post_status' 		=> 	'publish',
							'fields'			=>	'ids',
							'orderby' 			=> 	$course_settings['course_lesson_orderby'], 
							'order' 			=> 	$course_settings['course_lesson_order'],
							'meta_query' 		=> 	array(
								array(
									'key'     	=> 'course_id',
									'value'   	=> intval( $course_id ),
									'compare' 	=> '=',
									'type'		=>	'NUMERIC'
								),
								array(
									'key'     	=> 'lesson_id',
									'value'   	=> intval( $lesson_id ),
									'compare' 	=> '=',
									'type'		=>	'NUMERIC'
								),
								'relation' => 'AND',
							)
						);
						$lesson_quiz_steps_query = new WP_Query( $lesson_quiz_steps_query_args );
						if ( ( $lesson_quiz_steps_query instanceof WP_Query ) && ( property_exists( $lesson_quiz_steps_query, 'posts' ) ) ) {
							foreach( $lesson_quiz_steps_query->posts as $quiz_id ) {
								$steps['h']['sfwd-lessons'][$lesson_id]['sfwd-quiz'][$quiz_id] = array();
								$steps['l']['sfwd-lessons:'. $lesson_id .':sfwd-quiz:'. $quiz_id] = $quiz_id; 
								$steps['ids'][$quiz_id] = $quiz_id;
							}
						}						
					}
				}
		
		
				$quiz_steps_query_args = array(
					'post_type' 		=> 'sfwd-quiz',
					'posts_per_page' 	=> 	-1,
					'post_status' 		=> 	'publish',
					'fields'			=>	'ids',
					'meta_query' 		=> 	array(
						array(
							'key'     	=> 'course_id',
							'value'   	=> intval( $course_id ),
							'compare' 	=> '=',
							'type'		=>	'NUMERIC'
						),
						array(
							'key'     	=> 'lesson_id',
							'value'   	=> 0,
							'compare' 	=> '=',
							'type'		=>	'NUMERIC'
						),
						'relation' => 'AND',
					)
				);
				$quiz_steps_query = new WP_Query( $quiz_steps_query_args );
				if ( ( $quiz_steps_query instanceof WP_Query ) && ( property_exists( $quiz_steps_query, 'posts' ) ) ) {
					foreach( $quiz_steps_query->posts as $quiz_id ) {
						$steps['h']['sfwd-quiz'][$quiz_id] = array();
						$steps['l']['sfwd-quiz:'. $quiz_id] = $quiz_id; 
					}
				}
			}
			//error_log('steps<pre>'. print_r($steps, true) .'</pre>');
	
			return $steps;
	
		}
		