<section class="post-author">
	<div class="container">
		<div class="row">
			<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">		
				<div class="comments">
					<ul id="singlecomments" class="comments__list">
						<li class="comment even thread-even depth-1" id="comment-1">
							<div class="comment">
							
								<div class="comment__avatar">
									<?php echo get_avatar( get_the_author_meta('email'), 52 ); ?>
								</div>
								
								<div class="comment__body">
									<h5><?php esc_html_e('About The Author: ', 'stack'); echo ucfirst(get_the_author()); ?></h5>
									<?php echo wpautop(get_the_author_meta('description')); ?>
									<p><?php esc_html_e('More posts by ', 'stack'); the_author_posts_link(); ?>
								</div>
							</div>
						</li><!-- #comment-## -->
					</ul>		
				</div>			
			</div><!--end comments-->
		</div>
	</div>
</section>