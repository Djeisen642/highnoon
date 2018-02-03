<div class="col-sm-4 masonry__item">
    <a href="<?php the_permalink(); ?>" class="block">
    	<?php the_post_thumbnail( 'large' ); ?>
        <div class="feature feature-1 boxed boxed--border">
            <h6 class="type--uppercase color--primary"><?php echo ucfirst( get_post_type() ); ?></h6>
            <?php the_title( '<h5>', '</h5>' ); ?>
            <p><?php the_time( get_option(' date_format' ) ); ?></p>
        </div>
    </a>
</div>