<?php
get_header();
?>
    <style>
        .post-pagination-wrap,
        .post-pagination,
        .post-prev,
        .post-next {
            display: none !important;
        }
    </style>
    <div id="main-content">
        <div id="group-main" class="page type-page status-publish hentry">
            <div class="entry-content">
                <div id="left-area" class="content-area clr">
                    <main id="content" class="site-content clr" role="main">
						<?php while ( have_posts() ) : the_post(); ?>
							<?php
							// lets check for key
							$group_key = crypt( $post->ID, 'uncanny-group' );
							//Fixing $_GET string from . (dot) & space to _ ( underscore )
							$group_key = str_replace( array( ' ', '.', '[', '-' ), '_', $group_key );
							if ( ! isset( $_GET[ $group_key ] ) ) {
								?>
                                <p><?php echo esc_html__( 'This page can only be used by organizations with a valid group ID. The URL used to reach this page is not valid. Please contact your organization to obtain the correct registration URL.', 'uncanny-pro-toolkit' ) ?></p>
								<?php
								if ( current_user_can( 'manage_options' ) ) {
									printf( '<h2>' . __( 'Shown to admins only.', 'uncanny-pro-toolkit' ) . '</h2>' . '<p>' . __( 'The sign up link for this group is:', 'uncanny-pro-toolkit' ) . ' <br /><a href="%1$s" >%1$s</a></p>',
										get_permalink( get_the_ID() ) . '?gid=' . get_the_ID() . '&' . $group_key
									);
								}
							} else {
								?>
                                <article <?php post_class() ?>>
									<?php
									if ( ! is_user_logged_in() ) {
										if ( ! isset( $_REQUEST['registered'] ) ) {
											the_content();
											if ( ! has_shortcode( $post->post_content, 'gravityform' ) && ! has_shortcode( $post->post_content, 'theme-my-login' ) ) {
												\uncanny_pro_toolkit\LearnDashGroupSignUp::groups_register_form();

											}
										} else { ?>
											<?php
											$frontEndLogin = \uncanny_learndash_toolkit\Config::get_settings_value( 'uo_frontendloginplus_needs_verifcation', 'FrontendLoginPlus' );
											if ( ! empty( $frontEndLogin ) && 'on' === $frontEndLogin ) { ?>
                                                <p><?php echo esc_html__( 'Thank you for registering. Your account needs to be approved by site administrator.', 'uncanny-pro-toolkit' ) ?></p>
											<?php } else { ?>
                                                <p><?php echo esc_html__( 'Congratulations! You are now registered on this site. You will receive an email shortly with login details.', 'uncanny-pro-toolkit' ) ?></p>
											<?php } ?>
										<?php }

									} elseif ( is_user_logged_in() && isset( $_REQUEST['registered'] ) ) { ?>
                                        <p><?php echo esc_html__( 'Congratulations! You are now registered on this site. You will receive an email shortly with login details.', 'uncanny-pro-toolkit' ) ?></p>
									<?php } else {
										\uncanny_pro_toolkit\LearnDashGroupSignUp::groups_login_form();
										\uncanny_pro_toolkit\LearnDashGroupSignUp::check_group_membership();
									}
									?>
                                </article><!-- .entry -->
								<?php
							}
						endwhile; ?>
                    </main>
                </div>
                <div id="right-area" class="sidebar">
					<?php
					if ( isset( $_GET[ $group_key ] ) ) {
						echo do_shortcode( '[uo_group_organization]' );
					}
					if ( ! is_user_logged_in() ) {
						echo do_shortcode( '[uo_group_login]' );
					}
					?>
                </div>
            </div>
        </div><!-- .container -->
    </div>
<?php get_footer();