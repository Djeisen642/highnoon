<section class="space--xs blog-article-wide">
    <div class="container">
        <div class="row">
            <div class="cta cta--horizontal text-center-xs">
                <div class="col-sm-4">
                    <h4><?php the_time(get_option('date_format')); ?></h4>  
                </div>
                <div class="col-sm-5">
                    <?php the_title('<p class="lead">', '</p>'); ?>
                </div>
                <div class="col-sm-3 text-right text-center-xs">
                    <a class="btn type--uppercase" href="<?php the_permalink(); ?>">
                        <span class="btn__text">
                            <?php esc_html_e('Read Article', 'stack'); ?>
                        </span>
                    </a>
                </div>
            </div>  
        </div><!--end of row-->
    </div><!--end of container-->
</section>