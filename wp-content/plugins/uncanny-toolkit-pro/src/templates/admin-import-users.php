<?php

namespace uncanny_learndash_toolkit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get Groups Info
$learndash_groups = false;

if ( function_exists( 'learndash_get_groups' ) ) {
	$learndash_groups = learndash_get_groups();
}

// Get Courses Info
$learndash_courses = false;

$course_filter     = array(
	'post_type'      => 'sfwd-courses',
	'posts_per_page' => 1000,
	'post_status'    => 'publish'
);

$loop = new \WP_Query( $course_filter );

if ( ! empty( $loop ) ) {
	$learndash_courses = $loop->posts;
}

if ( isset( $_GET['tab'] ) ) {
	$active_tab = $_GET['tab'];
} else {
	$active_tab = 'instructions';
}

if( 'instructions' === $active_tab){
	$template_part = dirname(__FILE__).'/admin-import-user-part-instructions.php';
}elseif('options' === $active_tab){
	$template_part = dirname(__FILE__).'/admin-import-user-part-options.php';
}elseif('emails' === $active_tab){
	$template_part = dirname(__FILE__).'/admin-import-user-part-emails.php';
}elseif( 'import_users' === $active_tab ){
	$template_part = dirname(__FILE__).'/admin-import-user-part-import-users.php';
}



?>

<div class="wrap import-learndash-users">
	<div class="uo-user-import-notice notice notice-success" id="uo_import_user_message"></div>
	<div class="uo-admin-header">
		<a href="http://www.uncannyowl.com" target="_blank"><img src="<?php echo esc_url( Config::get_admin_media( 'Uncanny-Owl-logo.png' ) ); ?>"/></a>
		<h2><?php esc_html_e( 'Import LearnDash Users', 'uncanny-learndash-toolkit' ); ?></h2>
	</div>
	<h1 class="nav-tab-wrapper">
		<a href="?page=learndash-toolkit-import-user&tab=instructions"
		   class="nav-tab <?php echo $active_tab == 'instructions' ? 'nav-tab-active' : ''; ?>">Instructions</a>
		<a href="?page=learndash-toolkit-import-user&tab=options"
		   class="nav-tab <?php echo $active_tab == 'options' ? 'nav-tab-active' : ''; ?>">Options</a>
		<a href="?page=learndash-toolkit-import-user&tab=emails"
		   class="nav-tab <?php echo $active_tab == 'emails' ? 'nav-tab-active' : ''; ?>">Email Settings</a>
		<a href="?page=learndash-toolkit-import-user&tab=import_users"
		   class="nav-tab <?php echo $active_tab == 'import_users' ? 'nav-tab-active' : ''; ?>">Import Users</a>
	</h1>

	<?php include( $template_part ); ?>

</div>