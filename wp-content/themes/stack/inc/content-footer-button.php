<?php if( get_option('stack_footer_button_url') ) : ?>
<a href="<?php echo esc_url(get_option('stack_footer_button_url')); ?>" class="btn type--uppercase stack-footer-button" target="<?php echo esc_attr(get_option('stack_footer_button_target')); ?>"><span class="btn__text"><?php echo wp_kses_post(get_option('stack_footer_button_text', 'Contact Us')); ?></span></a>
<?php endif; ?>