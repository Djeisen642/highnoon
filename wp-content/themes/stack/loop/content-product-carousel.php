<?php global $product; ?>

<li class="col-sm-4">
    <div class="product">
    
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('large'); ?>
        </a>
        
        <a class="block" href="<?php the_permalink(); ?>">
            <div>
                <?php the_title('<h5>', '</h5><span> '. ebor_the_terms('product_cat', ', ', 'name') .'</span>'); ?>
                <?php get_template_part('inc/content-product', 'rating'); ?>
                <?php get_template_part('inc/content-product', 'excerpt'); ?>
            </div>
            <?php if( isset($product) ) : ?>
            <div>
                <span class="h4 inline-block"><?php echo wp_kses_post($product->get_price_html()); ?></span>
            </div>
            <?php endif; ?>
        </a>
        
    </div>
</li>