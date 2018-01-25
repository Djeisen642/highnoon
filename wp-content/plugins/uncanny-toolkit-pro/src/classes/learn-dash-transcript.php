<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Sample
 * @package uncanny_pro_toolkit
 */
class LearnDashTranscript extends toolkit\Config implements toolkit\RequiredFunctions {

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( __CLASS__, 'run_frontend_hooks' ) );
	}

	/**
	 * Initialize frontend actions and filters
	 */
	public static function run_frontend_hooks() {

		if ( true === self::dependants_exist() ) {

			// Enqueue Scripts for questionnaire
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'transcript_scripts' ) );

			/* ADD FILTERS ACTIONS FUNCTION */
			add_shortcode( 'uo_transcript', array( __CLASS__, 'display_course_transcript' ) );
		}

	}

	/**
	 * Description of class in Admin View
	 *
	 * @return array
	 */
	public static function get_details() {

		$class_title = esc_html__( 'Learner Transcript', 'uncanny-pro-toolkit' );

		$kb_link = 'http://www.uncannyowl.com/knowledge-base/learner-transcript/';

		/* Sample Simple Description with shortcode */
		$class_description = esc_html__( 'Add printable transcripts to the front end for your learners. This is a great way for learners to have a record of all course progress and overall standing.', 'uncanny-pro-toolkit' );

		/* Icon as fontawesome icon */
		$class_icon = '<i class="uo_icon_pro_fa uo_icon_fa fa fa-table "></i><span class="uo_pro_text">PRO</span>';
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
	 * @return array
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
				'type'        => 'text',
				'label'       => 'Logo Url',
				'option_name' => 'logo_url',
			),

			array(
				'type'        => 'text',
				'label'       => 'Primary UI Color',
				'class'       => 'uo-color-picker',
				'option_name' => 'primary_ui_color',
			),
			array(
				'type'        => 'text',
				'label'       => 'Primary Text Color',
				'class'       => 'uo-color-picker',
				'option_name' => 'primary_text_color',
			),
			array(
				'type'        => 'text',
				'label'       => 'Accent UI Color',
				'class'       => 'uo-color-picker',
				'option_name' => 'accent_ui_color',
			),
			array(
				'type'        => 'text',
				'label'       => 'Accent Text Color',
				'class'       => 'uo-color-picker',
				'option_name' => 'accent_text_color',
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

	/*
	 * Display the shortcode
	 * @param array $attributes
	 *
	 * @return string $html header and table
	 */
	public static function display_course_transcript( $attributes ) {
		if ( ! is_user_logged_in() ) {
			$html = "Login to view information";
		} else {

			$logo_url         = '';
			$feature_logo_url = self::get_settings_value( 'logo_url', __CLASS__ );
			if ( '' !== $feature_logo_url ) {
				$logo_url = $feature_logo_url;
			}

			$primary_ui_color         = '#114982';
			$feature_primary_ui_color = self::get_settings_value( 'primary_ui_color', __CLASS__ );
			if ( '' !== $feature_primary_ui_color ) {
				$primary_ui_color = $feature_primary_ui_color;
			}

			$primary_text_color         = '#114982';
			$feature_primary_text_color = self::get_settings_value( 'primary_text_color', __CLASS__ );
			if ( '' !== $feature_primary_text_color ) {
				$primary_text_color = $feature_primary_text_color;
			}

			$accent_ui_color         = '#cbd4db';
			$feature_accent_ui_color = self::get_settings_value( 'accent_ui_color', __CLASS__ );
			if ( '' !== $feature_accent_ui_color ) {
				$accent_ui_color = $feature_accent_ui_color;
			}

			$accent_text_color         = '#fff';
			$feature_accent_text_color = self::get_settings_value( 'accent_text_color', __CLASS__ );
			if ( '' !== $feature_accent_text_color ) {
				$accent_text_color = $feature_accent_text_color;
			}

			$request = shortcode_atts( array(
				'logo-url'    => '',
				'user-id'     => false,
				'date-format' => 'F j, Y'
			), $attributes );

			if ( '' !== $request['logo-url'] ) {
				$logo_url = $request['logo-url'];
			}

			$user_id     = $request['user-id'];
			$date_format = $request['date-format'];

			$html = self::generate_transcript_html( $primary_ui_color, $primary_text_color, $accent_ui_color, $accent_text_color, $logo_url, $user_id, $date_format );
		}

		return $html;
	}

	/**
	 * Generate transcript HTML Output
	 * @param string $primary_ui_color
	 * @param string $primary_text_color
	 * @param string $accent_ui_color
	 * @param string $accent_text_color
	 * @param string $logo_url
	 * @param string $user_id
	 * @param string $date_format
	 *
	 * @return string
	 */
	private static function generate_transcript_html( $primary_ui_color, $primary_text_color, $accent_ui_color, $accent_text_color, $logo_url, $user_id, $date_format ) {

		if ( absint( $user_id ) ) {
			$current_user = get_userdata( absint( $user_id ) );
		} else {
			$current_user = wp_get_current_user();
		}

		$current_user = apply_filters('uo_transcript_current_user', $current_user);

		// Collect some needed LearnDash labels
		$course_label  = \LearnDash_Custom_Label::get_label( 'course' );
		$courses_label = \LearnDash_Custom_Label::get_label( 'courses' );
		$lessons_label = \LearnDash_Custom_Label::get_label( 'lessons' );
		$quiz_label = \LearnDash_Custom_Label::get_label( 'quiz' );

		// Setup data to populate Header
		$transcript_heading                     = array();
		$transcript_heading['placeholder_text'] = '&nbsp;';

		// Heading
		$transcript_heading['text_print']       = __( 'Print', 'uncanny-pro-toolkit' );
		$transcript_heading['text_before_name'] = __( 'Transcript for ', 'uncanny-pro-toolkit' );
		$transcript_heading['first_name']       = $current_user->user_firstname;
		$transcript_heading['last_name']        = $current_user->user_lastname;
		$transcript_heading['logo_url']         = $logo_url;

		$transcript_heading['text_before_date'] = __( 'Published: ', 'uncanny-pro-toolkit' );
		$transcript_heading['current_date']     = current_time( $date_format );

		// Sub heading bar
		$transcript_heading['text_before_total_enrolled_courses']  = __( "Total Enrolled $courses_label: ", 'uncanny-pro-toolkit' );
		$transcript_heading['text_before_total_completed_courses'] = __( 'Total Completed: ', 'uncanny-pro-toolkit' );
		$transcript_heading['text_before_average_quiz_score']      = __( "Avg $quiz_label Score: ", 'uncanny-pro-toolkit' );

		// Filter
		$transcript_heading = apply_filters( 'uo_filter_transcript_heading', $transcript_heading );

		// Default amount of courses completed
		$courses_completed = 0;

		// Set up calculation for average quiz score
		$quizzes_completed                           = 0;
		$sum_course_average_percentage_quizzes_score = 0;

		// Get registered Courses
		$courses_registered = ld_get_mycourses( $current_user->ID );

		//  Build Table Data
		$table = array();

		// Create table headings
		$table_heading['text_course']           = __( $course_label, 'uncanny-pro-toolkit' );
		$table_heading['text_percent_Complete'] = __( '% Complete', 'uncanny-pro-toolkit' );
		$table_heading['text_completion_date']  = __( 'Completion Date', 'uncanny-pro-toolkit' );
		$table_heading['text_lessons_competed'] = __( "$lessons_label Completed", 'uncanny-pro-toolkit' );
		$table_heading['text_avg_quiz_score']   = __( "Avg $quiz_label Score", 'uncanny-pro-toolkit' );
		$table_heading['text_final_quiz_score'] = __( "Final $quiz_label Score", 'uncanny-pro-toolkit' );

		// Filter
		$table_heading = apply_filters( 'uo_filter_table_heading', $table_heading );

		$table['headings'] = array(
			$table_heading['text_course'],
			$table_heading['text_percent_Complete'],
			$table_heading['text_completion_date'],
			$table_heading['text_lessons_competed'],
			$table_heading['text_avg_quiz_score'],
			$table_heading['text_final_quiz_score']
		);

		$table['rows'] = array();

		$usermeta        = get_user_meta( $current_user->ID, '_sfwd-course_progress', true );
		$course_progress = empty( $usermeta ) ? false : $usermeta;

		$usermeta            = get_user_meta( $current_user->ID, '_sfwd-quizzes', true );
		$users_taken_quizzes = empty( $usermeta ) ? false : $usermeta;


		if ( $courses_registered ) {

			foreach ( $courses_registered as $course_id ) {

				//// Setup Data
				// list of all quizzes associated to the a course
				$course_quiz_list = learndash_get_course_quiz_list( $course_id, $current_user->ID );
				// list of all lessons in course with add field to the quizes in the lesson(User id is a LearnDash reference and does not apply)
				$lesson_list_with_quiz_list = self::get_lesson_list_with_quiz_list( $course_id, $current_user->ID );


				$table['rows'][ $course_id ] = array();

				// Column Title
				$table['rows'][ $course_id ][] = array( 'title', get_the_title( $course_id ) );

				// Column Course Progress
				if ( isset( $course_progress[ $course_id ]['completed'] ) && isset( $course_progress[ $course_id ]['total'] ) ) {
					$completed                     = $course_progress[ $course_id ]['completed'];
					$total                         = $course_progress[ $course_id ]['total'];
					$table['rows'][ $course_id ][] = array(
						'percent',
						self::get_percent_complete( $completed, $total )
					);
				} else {
					$table['rows'][ $course_id ][] = array( 'placeholder', $transcript_heading['placeholder_text'] );
				}

				// Column Completion Date
				$completion_date = self::get_completion_date( $current_user->ID, $course_id, $date_format );
				if ( $completion_date ) {
					$courses_completed ++;
					$table['rows'][ $course_id ][] = array( 'date', $completion_date );
				} else {
					$table['rows'][ $course_id ][] = array( 'placeholder', $transcript_heading['placeholder_text'] );
				}

				// Column Lessons Completed
				if ( $lesson_list_with_quiz_list && isset( $course_progress[ $course_id ]['lessons'] ) ) {

					$all_lessons       = count( $lesson_list_with_quiz_list );
					$lessons_completed = count( $course_progress[ $course_id ]['lessons'] );

					$table['rows'][ $course_id ][] = array(
						'lessons-completed',
						$lessons_completed . ' of ' . $all_lessons
					);
				} else {
					$table['rows'][ $course_id ][] = array( 'placeholder', $transcript_heading['placeholder_text'] );
				}

				// Column Quiz Average
				$course_quiz_average = self::get_avergae_quiz_result( $course_quiz_list, $lesson_list_with_quiz_list, $users_taken_quizzes );

				if ( $course_quiz_average ) {
					$table['rows'][ $course_id ][] = array( 'percent', $course_quiz_average );
					$quizzes_completed ++;
					$sum_course_average_percentage_quizzes_score += $course_quiz_average;
				} else {
					$table['rows'][ $course_id ][] = array( 'placeholder', $transcript_heading['placeholder_text'] );
				}

				//Column Final quiz
				$final_quiz_results = self::get_final_quiz_result( $course_quiz_list, $lesson_list_with_quiz_list, $users_taken_quizzes );
				if ( $final_quiz_results ) {
					$table['rows'][ $course_id ][] = array( 'percent', $final_quiz_results );
				} else {
					$table['rows'][ $course_id ][] = array( 'placeholder', $transcript_heading['placeholder_text'] );
				}

			}
		}


		$transcript_heading['total_enrolled_course']   = count( $courses_registered );
		$transcript_heading['total_completed_courses'] = $courses_completed;
		if ( $quizzes_completed ) {
			$transcript_heading['average_quiz_score'] = array(
				'percent',
				absint( $sum_course_average_percentage_quizzes_score / $quizzes_completed )
			);
		} else {
			$transcript_heading['average_quiz_score'] = array( 'placeholder', $transcript_heading['placeholder_text'] );
		}

		// Filter
		$table = apply_filters( 'uo_filter_transcript_table', $table );

		ob_start();
		?>
		<div id="uo-t-print">
			<div>
				<?php echo self::create_heading( $transcript_heading, $primary_ui_color, $primary_text_color, $accent_ui_color, $accent_text_color ); ?>
			</div>
			<div>
				<?php echo self::create_table( $table['headings'], $table['rows'] ); ?>
			</div>
		</div>
		<iframe name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe>
		<div style="clear: both"></div>
		<?php
		return ob_get_clean();
	}

	private static function create_heading( $transcript_heading, $primary_ui_color, $primary_text_color, $accent_ui_color, $accent_text_color ) {
		ob_start();
		?>
		<style>
			.uo-t-sub-heading-section span:nth-child(1) {
				color: <?php echo $accent_ui_color; ?>;
			}

			.uo-t-sub-heading-section span:nth-child(2) {
				margin-left: 8px;
				color: <?php echo $accent_text_color; ?>;
			}

			.primary-ui-color {
				background: <?php echo $primary_ui_color; ?>;
			}

			.primary-text-color {
				color: <?php echo $primary_text_color; ?>;
			}

			.accent-ui-color {
				background: <?php echo $accent_ui_color; ?>;
			}

			.accent_text_color {
				color: <?php echo $accent_text_color; ?>;
			}

			#uo-t-print-button {
				color: #fff8e5;
				background: #be3225;
				float: right;
				clear: both;
				margin-bottom: 25px;
			}

			.uo-t-row {
				width: 100%;
				clear: both;
				min-height: 60px;
				display: block;
				margin-bottom: 70px;
			}

			.uo-t-heading {
				width: 50%;
				text-align: left;
			}

			h1.uo-t-heading-main {
				margin-bottom: 0;
			}

			h3.uo-t-heading-sub {
				margin-top: 5px;
			}

			.uo-t-logo {
				float: right;
			}

			.uo-t-logo img {
				max-height: 80px;
				max-width: 275px;
			}

			.uo-t-heading-light {
				font-weight: 400;
			}

			.uo-t-sub-heading {
				width: 100%;
				padding: 20px;
				text-align: center;
			}

			.uo-t-sub-heading-section-pipe {
				display: inline-block;
				margin-left: 50px;
				margin-right: 50px;
			}

			.uo-t-sub-heading-section {
				display: inline-block;
			}

			h3.uo-t-sub-heading-section, h3.uo-t-sub-heading-section-pipe {
				margin-top: 0;
				margin-bottom: 0;
			}

			tr.odd.ou-t {
				background: lightgray;
			}

			/* Show per page */
			.dataTables_wrapper .dataTables_length {
				float: right;
				margin: -30px 20px 41px;
				font-size: 14px;
				font-weight: bold;
			}

			.dataTables_length select {
				padding: 7px;
				margin: 0 6px;
				font-size: 14px;
				font-weight: bold;
			}

			/* Table */
			table#uo-transcript-table {
				margin: 25px 0;
				padding: 20px 0;
				border-top: 1px solid #cbd4db;
				border-bottom: 1px solid #cbd4db;
			}

			/* Table Heads */
			table.dataTable thead th, table.dataTable thead td {
				padding: 10px 10px;
				border-bottom: none;
				text-align: center;
			}

			table.dataTable thead th:first-child {
				text-align: left;
			}

			table.dataTable thead th {
				background-image: none !important;
			}

			/* Table Foots */
			table.dataTable tfoot th, table.dataTable tfoot td {
				padding: 10px 18px;
				border-top: none;
				text-align: center;
			}

			table.dataTable tfoot th:first-child {
				text-align: center;
			}

			table.dataTable tfoot {
				display: none;
			}

			/* Table Rows */
			table.dataTable tbody td {
				text-align: center;
			}

			table.dataTable tbody td:first-child {
				text-align: left;
			}

			/* Table Rows odd*/
			table.dataTable.display tbody tr.odd > .sorting_1, table.dataTable.order-column.stripe tbody tr.odd > .sorting_1 {
				background-color: #d2dbe1;
			}

			/* Tables rows od highlighted*/
			table.dataTable.stripe tbody tr.odd, table.dataTable.display tbody tr.odd {
				background-color: #cbd4db;
			}

			/* Responsive */
			span.dtr-data {
				font-weight: bold;
				float: right;
				margin-right: 20px;
				width: 60px;
				text-align: center;
			}

			.dataTable td.child li {
				margin-bottom: 6px;
			}

			.dataTable td.child ul {
				list-style: none;
				margin: 0;
			}

			/* Pagination */
			.dataTables_wrapper .dataTables_paginate .paginate_button.disabled, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
				display: none;
			}

			a.paginate_button.current {
				background: none !important;
			}

			a.paginate_button:hover {
				background: #114982 !important;
				color: white;
				border-color: #114881 !important;
			}

			/* Printing */
			.printable-frame #uo-t-print-button {
				display: none;
			}

			.printable-frame .uo-t-heading-main {
				font-size: 25px !important;
			}

			.printable-frame .dataTables_wrapper .dataTables_length {
				display: none;
			}

			.uo-t-sub-heading-section-pipe {
				visibility: hidden;
				margin-left: 10px;
				margin-right: 10px;
			}

			.printable-frame .dataTables_info {
				display: none;
			}

			.printable-frame .dataTables_paginate {
				display: none;
			}

			@media print {
				.uo-t-sub-heading-section span,
				.uo-t-heading-main,
				.uo-t-heading-sub,
				.uo-t-heading-light {
					font-family: arial;
				}

				table.dataTable {
					page-break-inside: auto;
				}

				table.dataTable tr {
					page-break-inside: avoid;
					page-break-after: auto;
				}

				table.dataTable thead th, table.dataTable thead td {
					white-space: nowrap;
					font-size: 12px;
					font-weight: normal;
					font-family: arial;
				}

				table.dataTable tbody td {
					font-size: 12px;
					font-weight: normal;
					font-family: arial;
				}

				table.dataTable td {
					border-bottom: 1px solid #000000 !important;
				}

				thead {
					display: table-header-group;
				}

				tfoot {
					display: table-header-group;
				}

				table {
					-fs-table-paginate: paginate;
				}

				@page {
					size: landscape
				}

			}


		</style>
		<div class="">
			<button id="uo-t-print-button"><?php echo $transcript_heading['text_print']; ?></button>
		</div>
		<div class="uo-t-row">
			<div class="uo-t-logo">
				<img id="uo-t-logo" src="<?php echo $transcript_heading['logo_url']; ?>"/>
			</div>
			<div class="uo-t-heading">
				<h1 class="uo-t-heading-main">
					<span><?php echo $transcript_heading['text_before_name']; ?> </span>
					<span class="primary-text-color"><?php echo $transcript_heading['first_name']; ?> </span>
					<span class="primary-text-color"><?php echo $transcript_heading['last_name']; ?></span>
				</h1>
				<h3 class="uo-t-heading-sub">
					<span class="uo-t-heading-light"><?php echo $transcript_heading['text_before_date']; ?> </span>
					<strong><?php echo $transcript_heading['current_date']; ?> </strong>
				</h3>
			</div>
		</div>
		<div class="uo-t-row primary-ui-color">
			<div class="uo-t-sub-heading">
				<h3 class="uo-t-sub-heading-section">
					<span><?php echo $transcript_heading['text_before_total_enrolled_courses']; ?></span>
					<span><?php echo $transcript_heading['total_enrolled_course']; ?></span>
				</h3>
				<h3 class="uo-t-sub-heading-section-pipe">|</h3>
				<h3 class="uo-t-sub-heading-section">
					<span><?php echo $transcript_heading['text_before_total_completed_courses']; ?></span>
					<span><?php echo $transcript_heading['total_completed_courses']; ?></span>
				</h3>
				<h3 class="uo-t-sub-heading-section-pipe">|</h3>
				<h3 class="uo-t-sub-heading-section">
					<span><?php echo $transcript_heading['text_before_average_quiz_score']; ?></span>
					<span><?php echo $transcript_heading['average_quiz_score'][1]; ?>%</span>
				</h3>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	private static function create_table( $headings, $rows ) {

		// table element text
		$table_elements['text_show']        = __( 'Show', 'uncanny-pro-toolkit' );
		$table_elements['text_all']         = __( 'ALL', 'uncanny-pro-toolkit' );
		$table_elements['text_entries']     = __( 'entries', 'uncanny-pro-toolkit' );
		$table_elements['text_sEmptyTable'] = __( 'No data available.', 'uncanny-pro-toolkit' );
		$table_elements['text_sInfo']       = __( 'Showing _START_ to _END_ of _TOTAL_ entries', 'uncanny-pro-toolkit' );
		$table_elements['text_sInfoEmpty']  = __( 'Showing 0 to 0 of 0 entries', 'uncanny-pro-toolkit' );
		$table_elements['text_sPrevious']   = __( 'Previous', 'uncanny-pro-toolkit' );
		$table_elements['text_sNext']       = __( 'Next', 'uncanny-pro-toolkit' );

		// Filter
		$table_elements = apply_filters( 'uo_filter_table_elements', $table_elements );

		$headings_html = '';
		foreach ( $headings as $heading ) {
			$headings_html .= '<th>' . $heading . '</th>';
		}

		$rows_html = '';
		foreach ( $rows as $row ) {
			$rows_html .= '<tr class="ou-t">';
			foreach ( $row as $value ) {
				$suffix = '';
				switch ( $value[0] ) {
					case 'title':
						$data_order = strtolower( $value[1] );
						break;
					case 'percent':
						$data_order = absint( $value[1] );
						$suffix     = '%';
						break;
					case 'date':
						$data_order = strtotime( $value[1] );
						break;
					case 'lessons-completed':
						$data_order = explode( ' ', $value[1] );
						$data_order = absint( $data_order[0] );
						break;
					default:
						$data_order = 0;
				}
				$rows_html .= '<td data-order="' . $data_order . '">' . $value[1] . $suffix . '</td>';
			}
			$rows_html .= '</tr>';

		}

		$page_length = apply_filters('transcript_page_length', '10' );

		$add_length = '';
		if( $page_length != 10 && $page_length != 25){
		   $add_length = '<option value="'.$page_length.'">'.$page_length.'</option>';
        }

		ob_start();
		?>

		<table id="uo-transcript-table" class="display responsive no-wrap" cellspacing="0" width="100%"
		       data-order='[[ 0, "desc" ]]' data-page-length='<?php echo $page_length;?>'>
			<thead>
			<tr>
				<?php echo $headings_html; ?>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<?php echo $headings_html; ?>
			</tr>
			</tfoot>
			<tbody>
			<?php echo $rows_html; ?>
			</tbody>
		</table>

		<script>


			jQuery(document).ready(function () {

				jQuery.extend(jQuery.fn.dataTable.defaults, {
					searching: false,
					ordering: true,
					paging: true,
					responsive: true
				});

				var uoTable = jQuery('#uo-transcript-table').DataTable({
					"oLanguage": {
						"sLengthMenu": '<?php echo $table_elements['text_show']; ?> <select><?php echo $add_length;?><option value="10">10</option><option value="25">25</option><option value="-1"><?php echo $table_elements['text_all']; ?></option></select> <?php echo $table_elements['text_entries']; ?>',
						"sEmptyTable": "<?php echo $table_elements['text_sEmptyTable']; ?>",
						"sInfo": "<?php echo $table_elements['text_sInfo']; ?>",
						"sInfoEmpty": "<?php echo $table_elements['text_sInfoEmpty']; ?>",
						"oPaginate": {
							"sPrevious": "<?php echo $table_elements['text_sPrevious']; ?>",
							"sNext": "<?php echo $table_elements['text_sNext']; ?>"
						}
					}
				});


				jQuery('#uo-t-print-button').click(function () {
					showPrintDialog();
				});

				jQuery(document).bind("keyup", function (e) {
					if (e.ctrlKey && e.keyCode == 80) {
						showPrintDialog();
						return false;
					}
				});

				jQuery(document).bind("keydown", function (e) {
					if (e.ctrlKey && e.keyCode == 80) {
						return false;
					}
				});

				var showPrintDialog = function () {
					var button = jQuery('#uo-t-print-button');
					button.text('Loading..');

					window.frames["print_frame"].document.body.className = "printable-frame";
					window.frames["print_frame"].document.body.innerHTML = document.getElementById('uo-t-print').innerHTML;
					setTimeout(function () {
						window.frames["print_frame"].window.focus();
						window.frames["print_frame"].window.print();
						button.text('Print');
					}, 2000);
				}

			});
		</script>

		<?php
		return ob_get_clean();
	}

	/*
	 * Get percentage of completed modules vs total modules
	 * @param int $completed
	 * @param int $total
	 *
	 * @return string
	 */
	private static function get_percent_complete( $completed, $total ) {

		$percentage = $completed / $total * 100;
		$percentage = absint( $percentage );
		if ( 0 === $percentage ) {
			return '';
		} else {
			return $percentage;
		}

	}

	/*
	 * Get course completed on date with formatting
	 * @param int $user_id
	 * @param int $course_id
	 * @param string
	 *
	 * @return string
	 */
	private static function get_completion_date( $user_id, $course_id, $format ) {

		$timestamp = get_user_meta( $user_id, 'course_completed_' . $course_id, true );

		if ( '' === $timestamp ) {
			return false;
		}

		$date = gmdate( $format, $timestamp );

		return $date;

	}

	/*
	 *
	 */
	private static function get_lesson_list_with_quiz_list( $course_id, $user_id ) {

		$lesson_list = learndash_get_course_lessons_list( $course_id );

		if ( '' !== $lesson_list ) {

			foreach ( $lesson_list as $key => &$lesson ) {

				$lesson['quiz_list'] = learndash_get_lesson_quiz_list( $lesson['post']->ID, $user_id );

			}

		}

		return $lesson_list;
	}

	/*
	 *
	 */
	private static function get_avergae_quiz_result( $course_quiz_list, $lesson_list_with_quiz_list, $users_taken_quizzes ) {

		$highest_score = array();
		if ( '' !== $course_quiz_list ) {

			foreach ( $course_quiz_list as $course_quiz ) {
				$quiz_id = $course_quiz['post']->ID;
				if ( $users_taken_quizzes ) {

					foreach ( $users_taken_quizzes as $taken_quiz ) {

						if ( (int) $taken_quiz['quiz'] === $quiz_id ) {

							if ( 1 === $taken_quiz['pass'] ) {
								if ( isset( $highest_score[ $quiz_id ] ) ) {
									if ( $highest_score[ $quiz_id ] <= $taken_quiz['percentage'] ) {
										$highest_score[ $quiz_id ] = $taken_quiz['percentage'];
									}

								} else {
									$highest_score[ $quiz_id ] = $taken_quiz['percentage'];
								}

							}
						}
					}
				}
			}
		}

		if ( '' !== $lesson_list_with_quiz_list ) {

			foreach ( $lesson_list_with_quiz_list as $lesson ) {

				if ( '' !== $lesson['quiz_list'] ) {

					foreach ( $lesson['quiz_list'] as $lesson_quiz ) {

						$quiz_id = $lesson_quiz['post']->ID;

						if ( $users_taken_quizzes ) {
							foreach ( $users_taken_quizzes as $taken_quiz ) {

								if ( (int) $taken_quiz['quiz'] === $quiz_id ) {

									if ( 1 === $taken_quiz['pass'] ) {
										if ( isset( $highest_score[ $quiz_id ] ) ) {
											if ( $highest_score[ $quiz_id ] <= $taken_quiz['percentage'] ) {
												$highest_score[ $quiz_id ] = $taken_quiz['percentage'];
											}

										} else {
											$highest_score[ $quiz_id ] = $taken_quiz['percentage'];
										}

									}
								}
							}
						}
					}
				}
			}

		}

		if ( 0 !== count( $highest_score ) ) {
			$average = absint( array_sum( $highest_score ) / count( $highest_score ) );
		} else {
			$average = false;
		}


		return $average;

	}

	/*
	 *
	 */
	private static function get_final_quiz_result( $course_quiz_list, $lesson_list_with_quiz_list, $users_taken_quizzes ) {

		$last_quiz_id          = false;
		$final_quiz_percentage = false;

		// IS there any lessons
		if ( '' !== $lesson_list_with_quiz_list ) {

			foreach ( $lesson_list_with_quiz_list as $lesson ) {
				if ( '' !== $lesson['quiz_list'] ) {
					$last_quiz    = end( $lesson['quiz_list'] );
					if($last_quiz){
						$last_quiz_id = $last_quiz['post']->ID;
					}
				}
			}

		}
		if ( '' !== $course_quiz_list ) {
			$last_quiz    = end( $course_quiz_list );
			if($last_quiz){
				$last_quiz_id = $last_quiz['post']->ID;
			}
		}


		if ( $last_quiz_id ) {
			if ( $users_taken_quizzes ) {
				foreach ( $users_taken_quizzes as $taken_quiz ) {
					if ( (int) $taken_quiz['quiz'] === $last_quiz_id ) {
						if ( ! $final_quiz_percentage ) {
							$final_quiz_percentage = $taken_quiz['percentage'];
							$passed                = $taken_quiz['pass'];
						} else {
							if ( $final_quiz_percentage <= $taken_quiz['percentage'] ) {
								$final_quiz_percentage = $taken_quiz['percentage'];
								$passed                = $taken_quiz['pass'];
							}
						}
					}

				}
			}


		}

		return $final_quiz_percentage;

	}

	public static function transcript_scripts() {
		global $post;
		if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'uo_transcript' ) ) {

			// DataTable native styles
			$data_tables_style = plugins_url( basename( dirname( UO_FILE ) ) ) . '/src/assets/frontend/css/jquery.dataTables.min.css';
			wp_enqueue_style( 'datatables-styles', $data_tables_style );

			// DataTable native JS
			$data_tables_script = plugins_url( basename( dirname( UO_FILE ) ) ) . '/src/assets/frontend/js/jquery.dataTables.min.js';
			wp_enqueue_script( 'datatables-script', $data_tables_script, array( 'jquery' ), false, true );

			// DataTable native responsive JS
			$data_tables_responsive_script = plugins_url( basename( dirname( UO_FILE ) ) ) . '/src/assets/frontend/js/dataTables.responsive.min.js';
			wp_enqueue_script( 'responsive-datatables-script', $data_tables_responsive_script, array( 'jquery' ), false, true );


		}
	}
}