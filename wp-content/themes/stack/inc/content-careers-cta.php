<?php
$preset = '<h2>Sound like the job for you?</h2>
<p class="lead">
    Got what it takes to work with us? Great! Send us a link to your resumé or portfolio to become part of our talent pool.
</p>
<a class="btn btn--primary type--uppercase" href="#purchase-template">Drop your resumé here</a>';
?>

<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 stack-careers-cta">
                <div class="cta text-center">
                    <?php echo wp_kses_post(get_option('stack_careers_cta', $preset)); ?>
                </div>
            </div>
        </div><!--end of row-->
    </div><!--end of container-->
</section>