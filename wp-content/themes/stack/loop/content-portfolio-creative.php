<div class="masonry__item col-sm-12 creative-portfolio" data-masonry-filter="<?php echo esc_attr( ebor_the_terms( 'portfolio_category', ',', 'name' ) ); ?>">
    <div class="project-thumb">
        <div class="creative-title">
        	<?php the_title( '<h4><span>'. ebor_the_terms( 'portfolio_category', ', ', 'name' ) .'</span></h4><h1>', '</h1>' ); ?>
        	<p><?php the_excerpt(); ?></p>
        	<a href="<?php the_permalink(); ?>" class="btn btn--sm type--uppercase btn--primary"><span class="btn__text"><?php esc_html_e( 'View Project', 'stack' ); ?></span></a>
        </div>
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail( 'large' ); ?>
        </a>
    </div>
</div><!--end item-->