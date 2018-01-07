<li class="imagebg col-md-4 col-sm-6 col-xs-12" data-overlay="1">
    <div class="background-image-holder">
        <?php the_post_thumbnail('full'); ?>
    </div>
    <div class="pos-vertical-center">
        <div class="row">
            <div class="col-sm-12">
                <?php the_title('<h4>', '</h4>'); ?>
                <a href="<?php the_permalink(); ?>"><?php esc_html_e('Explore Gallery', 'stack'); ?></a>
            </div>
        </div><!--end of row-->
    </div><!--end of container-->
</li>