<?php
/**
 * Display checkbox field.
 *
 * @author    Eric Daams
 * @package   Charitable/Admin Views/Metaboxes
 * @copyright Copyright (c) 2017, Studio 164a
 * @since     1.0.0
 * @version   1.5.0
 */

if ( ! array_key_exists( 'form_view', $view_args ) || ! $view_args['form_view']->field_has_required_args( $view_args ) ) {
    return;
}

?>
<div id="<?php echo esc_attr( $view_args['wrapper_id'] ) ?>" class="<?php echo esc_attr( $view_args['wrapper_class'] );?>">
    <input type="checkbox" id="<?php echo esc_attr( $view_args['id'] ) ?>" name="<?php echo esc_attr( $view_args['key'] ) ?>"  tabindex="<?php echo esc_attr( $view_args['tabindex'] ) ?>" <?php checked( $view_args['checked'] ) ?> />
    <?php if ( isset( $view_args['label'] ) ) : ?>
        <label for="<?php echo esc_attr( $view_args['id'] ) ?>"><?php echo $view_args['label'] ?></label>
    <?php endif ?>
</div><!-- #<?php echo $view_args['wrapper_id'] ?> -->