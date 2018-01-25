<?php

namespace uncanny_pro_toolkit;

use uncanny_learndash_toolkit as toolkit;

class Boot extends toolkit\Config {

	/**
	 * class constructor
	 */
	public function __construct() {

		global $uncanny_pro_toolkit;

		if ( ! isset( $uncanny_pro_toolkit ) ) {
			$uncanny_pro_toolkit = new \stdClass();
		}

		// We need to check if spl auto loading is available when activating plugin
		// Plugin will not activate if SPL extension is not enabled by throwing error
		if ( ! extension_loaded( 'SPL' ) ) {
			$spl_error = esc_html__( 'Please contact your hosting company to update to php version 5.3+ and enable spl extensions.', 'uncanny-pro-toolkit' );
			trigger_error( $spl_error, E_USER_ERROR );
		}

		spl_autoload_register( array( __CLASS__, 'auto_loader' ) );


		// Class Details:  Add Class to Admin Menu page
		$classes = self::get_active_classes();


		if ( $classes ) {
			foreach ( self::get_active_classes() as $class ) {

				// Some wp installs remove slashes during db calls, being extra safe when comparing DB vs php values
				if ( strpos( $class, '\\' ) === false ) {
					$class = str_replace( 'pro_toolkit', 'pro_toolkit\\', $class );
				}

				$class_namespace = explode( '\\', $class );

				if ( class_exists( $class ) && __NAMESPACE__ === $class_namespace[0] ) {
					new $class;
				}

			}
		}

		/* Licensing */

		// URL of store powering the plugin
		define( 'UO_STORE_URL', 'https://www.uncannyowl.com/' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

		// Store download name/title
		define( 'UO_ITEM_NAME', 'Uncanny LearnDash Toolkit Pro' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

		// the name of the settings page for the license input to be displayed
		define( 'UO_LICENSE_PAGE', 'uncanny-pro-license-activation' );

		// include updater
		$updater = self::get_include( 'EDD_SL_Plugin_Updater.php', __FILE__ );
		include_once( $updater );

		add_action( 'admin_init', array( __CLASS__, 'uo_plugin_updater' ), 0 );
		add_action( 'admin_menu', array( __CLASS__, 'uo_license_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'uo_activate_license' ) );
		add_action( 'admin_init', array( __CLASS__, 'uo_deactivate_license' ) );
		add_action( 'admin_notices', array( __CLASS__, 'uo_admin_notices') );

	}

	public static function uo_plugin_updater() {

		// retrieve our license key from the DB
		$license_key = trim( get_option( 'uo_license_key' ) );

		// setup the updater
		$uo_updater = new EDD_SL_Plugin_Updater( UO_STORE_URL, UO_FILE, array(

			'version'   => UNCANNY_TOOLKIT_PRO_VERSION,     // current version number
			'license'   => $license_key,                    // license key (used get_option above to retrieve from DB)
			'item_name' => UO_ITEM_NAME,                    // name of this plugin
			'author'    => 'Uncanny Owl',                   // author of this plugin
			'beta'		=> false

		) );

	}

	// Licence options page
	public static function uo_license_menu() {
		add_submenu_page( 'uncanny-learnDash-toolkit', 'Uncanny Pro License Activation', 'License Activation', 'manage_options', UO_LICENSE_PAGE, array(
			__CLASS__,
			'uo_license_page'
		) );
	}

	public static function uo_license_page() {
		$license = get_option( 'uo_license_key' );
		$status  = get_option( 'uo_license_status' );
		$error   = '';

		if ( 'invalid' === $status ) {
			$error = 'Sorry, that is an invalid license key.';
		}

		?>
		<div class="wrap">
		<div class="uo-admin-header">

			<a href="http://www.uncannyowl.com" target="_blank">
				<img src="<?php echo esc_url( self::get_admin_media( 'Uncanny-Owl-logo.png' ) ); ?>"/>
			</a>

			<hr class="uo-underline">

			<h2><?php esc_html_e( 'Thanks for using the Uncanny LearnDash Toolkit Pro!', 'uncanny-learndash-toolkit' ); ?></h2>

		</div>

		<form method="post" action="options.php">
			<p style="color: #ff6b00;font-weight: bold; font-size: 14px;"><?php echo $error; ?></p>
			<?php settings_fields( 'uo_license' ); ?>

			<table class="form-table">
				<tbody>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e( 'License Key' ); ?>
					</th>
					<td>
						<input id="uo_license_key" name="uo_license_key" type="text" class="regular-text"
						       value="<?php esc_attr_e( $license ); ?>" <?php if ( $status !== false && $status == 'valid' ) { echo 'disabled'; }?>/>
						<label class="description" for="uo_license_key"><?php _e( 'Enter your license key', 'uncanny-learndash-toolkit' ); ?></label>
					</td>
				</tr>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e( 'Activate License' ); ?>
						</th>
						<td>
							<?php if ( $status !== false && $status == 'valid' ) { ?>
								<span style="color: #29c129; font-weight:bold; line-height: 27px;padding-right: 20px;">Your License is active. </span>
								<?php wp_nonce_field( 'uo_nonce', 'uo_nonce' ); ?>
								<input type="submit" class="button-secondary" name="uo_license_deactivate"
								       value="<?php _e( 'Deactivate License', 'uncanny-learndash-toolkit' ); ?>"/>
							<?php } else {
								wp_nonce_field( 'uo_nonce', 'uo_nonce' ); ?>
								<input style="background: #29c129; border-color: #29c129!important; text-decoration: none; color: white; font-size: 17px; padding: 8px 0; width: 170px; line-height: 0;" type="submit" class="button-secondary" name="uo_license_activate"
									value="<?php _e( 'Activate License', 'uncanny-learndash-toolkit' ); ?>"/>
							<?php } ?>
						</td>
					</tr>
				</tbody>
			</table>
		</form>

		<?php
	}

	/************************************
	 * this illustrates how to activate
	 * a license key
	 *************************************/

	public static function uo_activate_license() {

		// listen for our activate button to be clicked
		if ( isset( $_POST['uo_license_activate'] ) ) {

			// run a quick security check
			if ( ! check_admin_referer( 'uo_nonce', 'uo_nonce' ) ) {
				return;
			} // get out if we didn't click the Activate button


            // Save license key
		    $license = $_POST['uo_license_key'];
		    update_option('uo_license_key', $license);

			// retrieve the license from the database
			$license = trim( get_option( 'uo_license_key' ) );


			// data to send in our API request
			$api_params = array(
				'edd_action' => 'activate_license',
				'license'    => $license,
				'item_name'  => urlencode( UO_ITEM_NAME ), // the name of our product in uo
				'url'        => home_url()
			);

			// Call the custom API.
			$response = wp_remote_post( UO_STORE_URL, array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params
			) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.' );
				}

			} else {

				$license_data = json_decode( wp_remote_retrieve_body( $response ) );

				if ( false === $license_data->success ) {

					switch( $license_data->error ) {

						case 'expired' :

							$message = sprintf(
								__( 'Your license key expired on %s.' ),
								date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
							);
							break;

						case 'revoked' :

							$message = __( 'Your license key has been disabled.' );
							break;

						case 'missing' :

							$message = __( 'Invalid license.' );
							break;

						case 'invalid' :
						case 'site_inactive' :

							$message = __( 'Your license is not active for this URL.' );
							break;

						case 'item_name_mismatch' :

							$message = sprintf( __( 'This appears to be an invalid license key for %s.' ), UO_ITEM_NAME );
							break;

						case 'no_activations_left':

							$message = __( 'Your license key has reached its activation limit.' );
							break;

						default :

							$message = __( 'An error occurred, please try again.' );
							break;
					}

				}

			}



			// Check if anything passed on a message constituting a failure
			if ( ! empty( $message ) ) {
				$base_url = admin_url( 'admin.php?page=' . UO_LICENSE_PAGE );
				$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

				wp_redirect( $redirect );
				exit();
			}

			// $license_data->license will be either "valid" or "invalid"

			update_option( 'uo_license_status', $license_data->license );

			wp_redirect( admin_url( 'admin.php?page=' . UO_LICENSE_PAGE ) );
			exit();

		}
	}


	/***********************************************
	 * Illustrates how to deactivate a license key.
	 * This will descrease the site count
	 ***********************************************/

	public static function uo_deactivate_license() {

		// listen for our activate button to be clicked
		if ( isset( $_POST['uo_license_deactivate'] ) ) {

			// run a quick security check
			if ( ! check_admin_referer( 'uo_nonce', 'uo_nonce' ) ) {
				return;
			} // get out if we didn't click the Activate button

			// retrieve the license from the database
			$license = trim( get_option( 'uo_license_key' ) );


			// data to send in our API request
			$api_params = array(
				'edd_action' => 'deactivate_license',
				'license'    => $license,
				'item_name'  => urlencode( UO_ITEM_NAME ), // the name of our product in uo
				'url'        => home_url()
			);

			// Call the custom API.
			$response = wp_remote_post( UO_STORE_URL, array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params
			) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.' );
				}

				$base_url = admin_url( 'admin.php?page=' . UO_LICENSE_PAGE );
				$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

				wp_redirect( $redirect );
				exit();
			}

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if ( $license_data->license == 'deactivated' ) {
				delete_option( 'uo_license_status' );
			}

			wp_redirect( admin_url( 'admin.php?page=' . UO_LICENSE_PAGE ) );
			exit();

		}
	}


	/************************************
	 * this illustrates how to check if
	 * a license key is still valid
	 * the updater does this for you,
	 * so this is only needed if you
	 * want to do something custom
	 *************************************/

	public static function uo_check_license() {

		global $wp_version;

		$license = trim( get_option( 'uo_license_key' ) );

		$api_params = array(
			'edd_action' => 'check_license',
			'license'    => $license,
			'item_name'  => urlencode( UO_ITEM_NAME ),
			'url'        => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( UO_STORE_URL, array(
			'timeout'   => 15,
			'sslverify' => false,
			'body'      => $api_params
		) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( $license_data->license == 'valid' ) {
			echo 'valid';
			exit;
			// this license is still valid
		} else {
			echo 'invalid';
			exit;
			// this license is no longer valid
		}
	}

	/**
	 * This is a means of catching errors from the activation method above and displaying it to the customer
	 */
	public static function uo_admin_notices() {

	    if(  isset($_GET['page']) && 'uncanny-pro-license-activation' == $_GET['page'] ){

		    if ( isset( $_GET['sl_activation'] ) && isset( $_GET['sl_activation'] )&& ! empty( $_GET['message'] ) ) {

                switch( $_GET['sl_activation'] ) {

                    case 'false':
                        $message = urldecode( $_GET['message'] );
                        ?>
                        <div class="error">
                            <p><?php echo $message; ?></p>
                        </div>
                        <?php
                        break;

                    case 'true':
                    default:
                        // Developers can put a custom success message here for when activation is successful if they way.
                        break;

                }
		    }
		}
	}


	/**
	 *
	 *
	 * @static
	 *
	 * @param $class
	 */
	private static function auto_loader( $class ) {

		// Remove Class's namespace eg: my_namespace/MyClassName to MyClassName
		$class = str_replace( 'uncanny_pro_toolkit', '', $class );
		$class = str_replace( '\\', '', $class );

		// First Character of class name to lowercase eg: MyClassName to myClassName
		$class_to_filename = lcfirst( $class );

		// Split class name on upper case letter eg: myClassName to array( 'my', 'Class', 'Name')
		$split_class_to_filename = preg_split( '#([A-Z][^A-Z]*)#', $class_to_filename, null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );

		if ( 1 <= count( $split_class_to_filename ) ) {
			// Split class name to hyphenated name eg: array( 'my', 'Class', 'Name') to my-Class-Name
			$class_to_filename = implode( '-', $split_class_to_filename );
		}
		// Create file name that will be loaded from the classes directory eg: my-Class-Name to my-class-name.php
		$file_name = 'classes/' . strtolower( $class_to_filename ) . '.php';
		if ( file_exists( dirname( __FILE__ ) . '/' . $file_name ) ) {
			include_once $file_name;
		}

	}
}





