<?php
/**
 * @link              http://www.itpixelz.com
 * @since             1.0.0
 * @package           wp_default_sender_email_itpixelz
 *
 * @wordpress-plugin
 * Plugin Name:       Wp Default Sender Email by IT Pixelz
 * Plugin URI:        http://www.itpixelz.com
 * Description:       Get rid of default email from like this wordpress@domain.com, use your own brand name!
 * Version:           1.2
 * Author:            IT Pixelz
 * Author URI:        http://www.itpixelz.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/



include('admin-page.php');
add_filter('wp_mail_from', 'wdsei_new_mail_from');
add_filter('wp_mail_from_name', 'wdsei_new_mail_from_name');
add_action('activated_plugin', 'wdsei_plugin_activated');
add_action( 'admin_enqueue_scripts', 'wdsei_load_wp_admin_style' );
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wdsei_add_action_links' );


function wdsei_new_mail_from($old)
{
    $wdsei_options =  get_option('wdsei_itpixelz_options');
    return $wdsei_options['sender_mail'];
}

function wdsei_new_mail_from_name($old)
{
    $wdsei_options =  get_option('wdsei_itpixelz_options');
    return  $wdsei_options['sender_name'];
}

function wdsei_plugin_activated($plugin)
{
    if ($plugin == plugin_basename(__FILE__)) {
        exit(wp_redirect(admin_url('options-general.php?page=wp-default-sender-email-itpixelz')));
    }
}


function wdsei_load_wp_admin_style($hook) {
        // return if not plugin settings page, no need of this css then
        if($hook != 'settings_page_wp-default-sender-email-itpixelz') {
                return;
        }
        wp_enqueue_style( 'custom_wp_admin_css', plugins_url('admin.css', __FILE__) );
}


function wdsei_add_action_links ( $links ) {
 $mylinks = array(
 '<a href="' . admin_url( 'options-general.php?page=wp-default-sender-email-itpixelz' ) . '">Settings</a>',
 );
return array_merge( $links, $mylinks );
}
