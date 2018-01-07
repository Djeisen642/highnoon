<?php 
/**
 * Displays the checkbox to allow donors to remain anonymous on the Easy Digital Downloads checkout form.
 *
 * Override this template by copying it to yourtheme/charitable/charitable-anonymous/easy-digital-downloads/checkout-form-field.php
 *
 * @author  Studio 164a
 * @package Charitable Anonymous/Templates/Easy Digital Downloads
 * @since   1.1.0
 * @version 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

$ticked = isset( $_POST[ 'anonymous_donation' ] ) && $_POST[ 'anonymous_donation' ];
?>
<style>
#charitable-anonymous-checkbox-wrap label { display: inline-block; width: auto; }
</style>
<p id="charitable-anonymous-checkbox-wrap">
    <label class="edd-label" for="charitable-anonymous"><?php _e( 'I would like to make my donation anonymously.', 'charitable-anonymous' ) ?></label>
    <input type="checkbox" name="anonymous_donation" id="charitable-anonymous" value="1" <?php checked( $ticked ) ?> />
</p>