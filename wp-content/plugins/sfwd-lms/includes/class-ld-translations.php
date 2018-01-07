<?php
/*
WordPress language functions
get_available_languages()
$languages = get_available_languages();

*/

if (!class_exists('Learndash_Translations')) {
	class Learndash_Translations {

		private $project_slug = '';
		private $available_translations = array();
		private $installed_translations = array();

		static private $project_slugs = array();
		static private $translations_dir = '';
		static private $options_key = 'ld-translations';
		
		function __construct( $project_slug = '' ) {
			if ( !empty( $project_slug ) ) {
				$this->project_slug = $project_slug;
			}
		}

		static public function register_project_slug( $project_slug = '' ) {
			if ( ( !empty( $project_slug ) ) && ( !isset( self::$project_slugs[$project_slug] ) ) ) {
				self::$project_slugs[$project_slug] = $project_slug;
			}
		}

		static public function get_last_update() {
			$ld_translations = get_option( self::$options_key );
			if ( isset( $ld_translations['last_check'] ) ) {
				return $ld_translations['last_check'];
			}
		}

		static public function get_language_directory( $relative_to_home = true ) {
			$WP_LANG_DIR_tmp = str_replace('\\', '/', WP_LANG_DIR );
			$ABSPATH_tmp = str_replace('\\', '/', ABSPATH );
			
			self::$translations_dir = $WP_LANG_DIR_tmp . '/plugins/';
			if ( !file_exists( self::$translations_dir ) ) {
				wp_mkdir_p( self::$translations_dir );
			}

			if ( $relative_to_home !== true ) {
				return self::$translations_dir;
			} else {
				return self::$translations_dir = str_replace( $ABSPATH_tmp, '/', self::$translations_dir );			
			}
		}
		
		static public function is_language_directory_writable() {
			$translations_dir = self::get_language_directory( false );
			if ( is_writable( $translations_dir ) ) {
				return true;
			}
		}

		static public function project_has_available_translations( $project_slug = '' ) {
			$ld_translations = get_option( self::$options_key );
			if ( isset( $ld_translations['translation_sets'][$project_slug] ) ) {
				return true;
			}
		}

		static public function project_get_available_translations( $project_slug = '', $locale = '' ) {
			$ld_translations = get_option( self::$options_key );
			if ( isset( $ld_translations['translation_sets'][$project_slug] ) ) {
				if ( !empty( $locale ) ) {
					foreach( $ld_translations['translation_sets'][$project_slug] as $translation_set ) {
						if ( $translation_set['wp_locale'] == $locale ) {
							return $translation_set;
						}
					}
				} 
				return $ld_translations['translation_sets'][$project_slug];
			}
		}

		static public function get_action_url( $action = '', $project = '', $locale = '' ) {
			if ( !empty( $action ) ) {
				$action_url = remove_query_arg( array( 'action', 'project', 'locale', 'ld-translation-nonce' ) );
				
				$nonce_key = 'ld-translation-'. $action;
				$action_url = add_query_arg( array( 'action' => $action ), $action_url );
				
				if ( !empty( $project ) ) {
					$nonce_key .= '-'. $project;
					$action_url = add_query_arg( array( 'project' => $project ), $action_url );
				}
				if ( !empty( $locale ) ) {
					$nonce_key .= '-'. $locale;
					$action_url = add_query_arg( array( 'locale' => $locale ), $action_url );
				}
				
				$action_nonce = wp_create_nonce( $nonce_key );
				$action_url = add_query_arg( array( 'ld-translation-nonce' => $action_nonce ), $action_url );
				
				return $action_url;
			}
		}

		static public function install_translation( $project = '', $locale = '' ) {
			$reply_data = array();
			
			if ( ( !empty( $project ) ) && ( !empty( $locale ) ) ) { 
	
				if ( Learndash_Translations::is_language_directory_writable() ) {
					$translation_set = Learndash_Translations::project_get_available_translations( $project, $locale );
			
					if ( ( isset( $translation_set['links'] ) ) && ( !empty( $translation_set['links'] ) ) ) {
						foreach( $translation_set['links'] as $link_key => $link_url ) {	
							$url_args = apply_filters('learndash_translations_url_args', array() );

							$dest_filename = Learndash_Translations::get_language_directory( false ) . $project .'-'. $locale .'.'. $link_key;
							if ( file_exists( $dest_filename ) ) {
								unlink( $dest_filename );
							} 
					
							$response = wp_remote_get( $link_url, $url_args );
							if ( ( is_array( $response ) ) && ( wp_remote_retrieve_response_code( $response ) == '200' ) ) {
								$response_body = wp_remote_retrieve_body( $response );
								if ( !empty( $response_body ) ) {
									$fp = fopen( $dest_filename, 'w+' );
									if ( $fp !== false ) {
										fwrite( $fp, $response_body );
										fclose( $fp );
										$reply_data['status'] = true;
										$reply_data['message'] = '<p>'. __( 'Translation installed', 'learndash' ). '</p>';
									}
								}
							}
						}
					}
				}
			}
			
			return $reply_data;
		}
		
		static public function update_translation( $project = '', $locale = '' ) {
			$reply_data = array();
		
			if ( ( !empty( $project ) ) && ( !empty( $locale ) ) ) { 
	
				if ( Learndash_Translations::is_language_directory_writable() ) {
					$translation_set = Learndash_Translations::project_get_available_translations( $project, $locale );
			
					if ( ( isset( $translation_set['links'] ) ) && ( !empty( $translation_set['links'] ) ) ) {
						foreach( $translation_set['links'] as $link_key => $link_url ) {	
							$url_args = apply_filters('learndash_translations_url_args', array() );

							$dest_filename = Learndash_Translations::get_language_directory( false ) . $project .'-'. $locale .'.'. $link_key;
							if ( file_exists( $dest_filename ) ) {
								unlink( $dest_filename );
							} 
					
							$response = wp_remote_get( $link_url, $url_args );
							if ( ( is_array( $response ) ) && ( wp_remote_retrieve_response_code( $response ) == '200' ) ) {
								$response_body = wp_remote_retrieve_body( $response );
								if ( !empty( $response_body ) ) {
									$fp = fopen( $dest_filename, 'w+' );
									if ( $fp !== false ) {
										fwrite( $fp, $response_body );
										fclose( $fp );
										$reply_data['status'] = true;
										$reply_data['message'] = '<p>'. __( 'Translation updated', 'learndash' ) .'</p>';
									}
								}
							}
						}
					}
					
				} 
			}
			
			return $reply_data;
		}

		static public function remove_translation( $project = '', $locale = '' ) {
			$reply_data = array();
		
			if ( ( !empty( $project ) ) && ( !empty( $locale ) ) ) { 
	
				if ( Learndash_Translations::is_language_directory_writable() ) {
					$translation_set = Learndash_Translations::project_get_available_translations( $project, $locale );
			
					if ( ( isset( $translation_set['links'] ) ) && ( !empty( $translation_set['links'] ) ) ) {
						foreach( $translation_set['links'] as $link_key => $link_url ) {	
							$url_args = apply_filters('learndash_translations_url_args', array() );

							$dest_filename = Learndash_Translations::get_language_directory( false ) . $project .'-'. $locale .'.'. $link_key;
							if ( file_exists( $dest_filename ) ) {
								unlink( $dest_filename );
								$reply_data['status'] = true;
								$reply_data['message'] = '<p>'. __( 'Translation removed', 'learndash' ) .'</p>';
							} 
						}
					}
					
				} 
			}
		
			return $reply_data;
		}
		
		static public function refresh_translations() {
			$ld_translations = get_option( self::$options_key );
			$ld_translations['last_check'] = 0;
			update_option( self::$options_key, $ld_translations );
			self::get_available_translations();
		}

		function show_meta_box( ) {
			if ( !empty( $this->project_slug ) ) {
				$this->installed_translations = $this->get_installed_translations( );
				$this->available_translations = self::get_available_translations( $this->project_slug );
				?>
				<div id="wrap-ld-translations-<?php echo $this->project_slug ?>" class="wrap wrap-ld-translations">
					<?php
						if ( ( !empty( $this->available_translations ) ) || ( !empty( $this->installed_translations ) ) ) {
							$this->show_installed_translations( );
							$this->show_available_translations( );
						} else {
							?><p><?php _e('No translations available for this plugin.', 'learndash'); ?></p><?php
						}
					?>
				</div>
				<?php
			}
		}

		static public function get_available_translations( $project = '' ) {
			if ( !empty( self::$project_slugs ) ) {
				$ld_translations = get_option( self::$options_key, null );
				if ( !isset( $ld_translations['last_check'] ) ) {
					$ld_translations['last_check'] = time() - ( LEARNDASH_TRANSLATIONS_URL_CACHE + 1 );
				}
			
				$time_diff = abs( time() - intval( $ld_translations['last_check'] ) );
				
				if ( $time_diff > LEARNDASH_TRANSLATIONS_URL_CACHE ) {
					
					$project_slugs = implode(',', array_keys( self::$project_slugs ) );
			
					$url = add_query_arg(
						array(
							'ldlms-glotpress' 	=> 1,
							'action'			=> 'translation_sets',
							'project'			=> $project_slugs,
						),
						LEARNDASH_TRANSLATIONS_URL_BASE 
					);
					$url_args = apply_filters('learndash_translations_url_args', array() );
					if ( empty( $url_args ) ) $url_args = null; 
					$response = wp_remote_get( $url, $url_args );
					if ( ( is_array( $response ) ) && ( wp_remote_retrieve_response_code( $response ) == '200' ) ) {
						$response_body = wp_remote_retrieve_body( $response );

						if ( !empty( $response_body ) ) {
							$ld_translation_sets = json_decode( $response_body, true );

							$ld_translation = array(
								'last_check' 		=> time(),
								'translation_sets' 	=> $ld_translation_sets
							);
							update_option( self::$options_key, $ld_translation );
						}
					}
				} 
				
				if ( !empty( $project ) ) {
			
					if ( ( isset( $ld_translations['translation_sets'][$project] ) ) && ( !empty( $ld_translations['translation_sets'][$project] ) ) ) {
						return $ld_translations['translation_sets'][$project];
					}
				}
			}
		} 

		function show_installed_translations( ) {
			?>
			<h4><?php _e('Installed Translations', 'learndash') ?></h4>
			<p><?php echo sprintf( __('All translations are stored into the directory: %s', 'learndash' ), '<code>'. esc_attr('<site root>') . str_replace( ABSPATH, '/', $this->get_language_directory() ) . $this->project_slug .'-xx_XX.mo</code>') ?></p>
			<table class="ld-installed-translations wp-list-table widefat fixed striped posts">
				<tr>
					<th class="column-locale"><?php _e('Locale', 'learndash' ); ?></th>
					<th class="column-title"><?php _e('Name / Native', 'learndash' ); ?></th>
					<th class="column-actions-local"><?php _e('Download', 'learndash' ); ?></th>
					<th class="column-action-remote"><?php _e('Actions', 'learndash' ); ?></th>
				</tr>
				<?php 
					if ( ( is_array( $this->available_translations ) ) && ( !empty( $this->available_translations ) ) 
					  && ( is_array( $this->installed_translations ) ) && ( !empty( $this->installed_translations ) ) ) {
						foreach( $this->available_translations as $idx => $translation_set ) {
							$translation_locale = $translation_set['wp_locale'];
							if ( isset( $this->installed_translations[$translation_locale] ) ) {
								$installed_set = $this->installed_translations[$translation_locale];
								$this->show_installed_translation_row( $translation_locale, $translation_set, $installed_set );
							}
						}

						foreach( $this->installed_translations as $installed_locale => $installed_set ) {
							$install_matched = false;
							foreach( $this->available_translations as $idx => $translation_set ) {
								$translation_locale = $translation_set['wp_locale'];
								if ( $translation_locale == $installed_locale ) {
									$install_matched = true;
									break;
								}
							}
					
							if ( !$install_matched ) {
								$this->show_installed_translation_row( $installed_locale, null, $installed_set );
							}
						}
					} else {
						?>
						<tr>
							<td colspan="4"><?php _e('No Translations installed', 'leardash'); ?></td>
						</tr>
						<?php 
					} 
				?>
			</table>
			<?php
		}

		function show_installed_translation_row( $locale = '', $translation_set = null, $installed_set = null ) {
			if ( !empty( $locale ) ) {
				?>
				<tr>
					<td class="column-locale"><?php echo $locale ?></td>
					<td class="column-title"><?php 
						if ( !is_null($translation_set ) ) {
							echo $translation_set['english_name'] .'/'. $translation_set['native_name'];
						} else {
							_e( 'Not from LearnDash', 'learndash' );
						}
				
						?></td>
					<td class="column-actions-local">
						<?php
						if ( isset( $installed_set['mo'] ) ) {
							?>
							<a id="learndash-translations-mo-file-<?php echo $locale ?>" class="button button-secondary learndash-translations-mo-file" href="<?php echo $this->get_language_directory() .'/'. $installed_set['mo'] ?>" title="<?php _e('Download MO File from your server.', 'learndash' ); ?>"><span class="dashicons dashicons-download"></span><?php esc_html_e( 'MO', 'learndash' ); ?></a>
							<?php
						}
		
						if ( isset( $installed_set['po'] ) ) {
							?>
							<a id="learndash-translations-po-file-<?php echo $locale ?>" class="button button-secondary learndash-translations-po-file" href="<?php echo $this->get_language_directory() .'/'. $installed_set['po'] ?>" title="<?php _e('Download PO File from your server.', 'learndash' ); ?>"><span class="dashicons dashicons-download"></span><?php esc_html_e( 'PO', 'learndash' ); ?></a>
					
							<?php
						}
						?>
					</td>
					<td class="column-actions-remote">
						<?php
							if ( !is_null( $translation_set ) ) {
								$last_updated_time = learndash_get_timestamp_from_date_string( $translation_set['last_modified_gmt'] );
								if ( ( $installed_set['mo_mtime'] < $last_updated_time ) && ( $installed_set['po_mtime'] < $last_updated_time ) ) {
									_e('Up to date', 'learndash');
								} else {
									?>
									<a href="<?php echo self::get_action_url( 'update', $this->project_slug, $locale ); ?>" class="button button-primary learndash-translations-update" title="<?php esc_html_e('Update trnaslation from LearnDash', 'learndash' ); ?>"><?php esc_html_e( 'Update', 'learndash' ); ?></a>
									<?php
								}
							}
							
							?><a id="learndash-translations-<?php echo $this->project_slug ?>-<?php echo $locale ?>-remove" class="button button-secondary learndash-translations-remove" href="<?php echo self::get_action_url( 'remove', $this->project_slug, $locale ); ?>" title="<?php esc_html_e('Remove translation frm server', 'learndash' ) ?>"><span class="dashicons dashicons-trash"></span></a><?php
							
						?>
					</td>
				</tr>
				<?php
			}
		}

		function show_available_translations( ) {
			$wp_languages = get_available_languages();
			if ( empty( $wp_languages ) ) $wp_languages = array();

			// Taken from options-general.php. 
			if ( ! is_multisite() && defined( 'WPLANG' ) && '' !== WPLANG && 'en_US' !== WPLANG && ! in_array( WPLANG, $languages ) ) {
				$wp_languages[] = WPLANG;
			}
			
			$wp_locale = get_locale();
			if ( ( !empty( $wp_locale ) ) && ( !in_array( $wp_locale, $wp_languages ) ) ) {
				$wp_languages[] = $wp_locale;
			}

			if ( ( is_array( $this->available_translations ) ) && ( !empty( $this->available_translations ) ) ) {
			
				$available_translations = array();
				$available_translations['recommended'] = array();
				$available_translations['available'] = array();
			
				// First we split into buckets based on languages installed to WP.
				foreach( $this->available_translations as $translation_set ) {
					if ( !isset( $this->installed_translations[$translation_set['wp_locale']] ) ) {
						if ( in_array( $translation_set['wp_locale'], $wp_languages ) === true ) {
							$available_translations['recommended'][$translation_set['wp_locale']] = $translation_set;
						} else {
							$available_translations['available'][$translation_set['wp_locale']] = $translation_set;
						}
					}
				}

				if ( ( !empty( $available_translations['recommended'] ) ) || ( !empty( $available_translations['available'] ) ) ) {
			
					?>
					<div id="learndash-translations-available">
						<h4><?php _e('Available Translations', 'learndash' ); ?></h4>
						<select id="ld-translation-install-locale-<?php echo $this->project_slug ?>" class="ld-translation-install-locale" data-project="<?php echo $this->project_slug ?>">
							<option value=""><?php _e('-- Install Translation --', 'learndash' ); ?></option>
							<?php 
					
							$show_opt_group = false;
							if ( ( !empty( $available_translations['recommended'] ) ) && ( !empty( $available_translations['available'] ) ) ) {
								$show_opt_group = true;
							}
					
							if ( !empty( $available_translations['recommended'] ) ) {
								if ( $show_opt_group ) {
									?><optgroup label="<?php echo esc_attr( 'Recommended', 'learndash' ) ?>"><?php
								}
								foreach( $available_translations['recommended'] as $translation_set ) {
									?><option value="<?php echo self::get_action_url( 'install', $this->project_slug, $translation_set['wp_locale'] ); ?>"><?php echo $translation_set['english_name'] .' / '. $translation_set['native_name'] .' ('. $translation_set['wp_locale'].')' ?></option><?php
								}
								if ( $show_opt_group ) {
									?></optgroup><?php
								}
							}
					
							if ( !empty( $available_translations['available'] ) ) {
								if ( $show_opt_group ) {
									?><optgroup label="<?php echo esc_attr( 'Available', 'learndash' ) ?>"><?php
								}
								foreach( $available_translations['available'] as $translation_set ) {
									?><option value="<?php echo self::get_action_url( 'install', $this->project_slug, $translation_set['wp_locale'] ); ?>"><?php echo $translation_set['english_name'] .' / '. $translation_set['native_name'] .' ('. $translation_set['wp_locale'].')' ?></option><?php
								}
								if ( $show_opt_group ) {
									?></optgroup><?php
								}
							}
						?>
						</select> 
						<a id="learndash-translation-install-<?php echo $this->project_slug ?>" class="button button-primary learndash-translations-install" href="#"><?php esc_html_e( 'Install', 'learndash' ); ?></a>
					</div>
					<?php
				}
			}
		}

		function get_installed_translations( ) {
			$translation_files = array();
	
			if ( !empty( $this->project_slug ) ) {
	
				$languages_plugins_dir = WP_LANG_DIR . '/plugins/'; 
				$languages_plugins_dir_mo = $languages_plugins_dir . $this->project_slug .'-*.mo';
	
				$mo_files = glob( $languages_plugins_dir_mo );
				if ( !empty( $mo_files ) ) {
					foreach( $mo_files as $mo_file ) {
						$mo_file = basename( $mo_file );
						$mo_file_local = str_replace( array( $this->project_slug . '-', '.mo' ), '', $mo_file );
						if ( !empty( $mo_file_local ) ) {

							if ( !isset( $translation_files[$mo_file_local] ) ) {
								$translation_files[$mo_file_local] = array();
								$translation_files[$mo_file_local]['mo'] = $mo_file;
								$translation_files[$mo_file_local]['mo_mtime'] = filemtime( $languages_plugins_dir . $mo_file );

								$po_file = str_replace('.mo', '.po', $mo_file );						
								$languages_plugins_dir_po = $languages_plugins_dir . $po_file;
								if ( file_exists( $languages_plugins_dir_po ) ) {
									$translation_files[$mo_file_local]['po'] = $po_file;
									$translation_files[$mo_file_local]['po_mtime'] = filemtime( $languages_plugins_dir . $po_file );
								} 
							}
						}
					}
				}
			}
	
			return $translation_files;
		}
		
		// End of functions
	}
}


/*
add_action( 'wp_ajax_learndash_translation_update', function() {
	$reply_data = array( );
	
	$project = $locale = '';
	if ( ( isset( $_POST['project'] ) ) && ( !empty( $_POST['project'] ) ) ) {
		$project = esc_attr( $_POST['project'] );
	}
	if ( ( isset( $_POST['locale'] ) ) && ( !empty( $_POST['locale'] ) ) ) {
		$locale = esc_attr( $_POST['locale'] );
	}
	
	if ( ( !empty( $project ) ) && ( !empty( $locale ) ) ) { 
	
		if ( Learndash_Translations::is_language_directory_writable() ) {
			$translation_set = Learndash_Translations::project_get_available_translations( $project, $locale );
			
			if ( ( isset( $translation_set['links'] ) ) && ( !empty( $translation_set['links'] ) ) ) {
				foreach( $translation_set['links'] as $link_key => $link_url ) {	
					$url_args = apply_filters('learndash_translations_url_args', array() );

					$dest_filename = Learndash_Translations::get_language_directory( false ) . $project .'-'. $locale .'.'. $link_key;
					if ( file_exists( $dest_filename ) ) {
						if ( is_writeable( $dest_filename ) ) {
						}
					} else 
					
					$response = wp_remote_get( $link_url, $url_args );
					if ( ( is_array( $response ) ) && ( wp_remote_retrieve_response_code( $response ) == '200' ) ) {
						$response_body = wp_remote_retrieve_body( $response );
						if ( !empty( $response_body ) ) {
							$fp = fopen( $dest_filename, 'w+' );
							if ( $fp !== false ) {
								fwrite( $fp, $response_body );
								fclose( $fp );
								$reply_data['status'] = true;
							}
						}
					}
				}
			}
		
		} else {
			$reply_data['status'] = false;
			$reply_data['message'] = __( 'Languages directory not writable.', 'learndash' );
		}
	}
		
	echo json_encode( $reply_data );

	wp_die(); // this is required to terminate immediately and return a proper response			
	
});
*/
/*
add_action( 'wp_ajax_learndash_translation_install', function() {
	$reply_data = array( );
	
	$project = $locale = '';
	if ( ( isset( $_POST['project'] ) ) && ( !empty( $_POST['project'] ) ) ) {
		$project = esc_attr( $_POST['project'] );
	}
	if ( ( isset( $_POST['locale'] ) ) && ( !empty( $_POST['locale'] ) ) ) {
		$locale = esc_attr( $_POST['locale'] );
	}
	
	if ( ( !empty( $project ) ) && ( !empty( $locale ) ) ) { 
	
		if ( Learndash_Translations::is_language_directory_writable() ) {
			$translation_set = Learndash_Translations::project_get_available_translations( $project, $locale );
			
			if ( ( isset( $translation_set['links'] ) ) && ( !empty( $translation_set['links'] ) ) ) {
				foreach( $translation_set['links'] as $link_key => $link_url ) {	
					$url_args = apply_filters('learndash_translations_url_args', array() );

					$dest_filename = Learndash_Translations::get_language_directory( false ) . $project .'-'. $locale .'.'. $link_key;
					if ( file_exists( $dest_filename ) ) {
						if ( is_writeable( $dest_filename ) ) {
						}
					} else 
					
					$response = wp_remote_get( $link_url, $url_args );
					if ( ( is_array( $response ) ) && ( wp_remote_retrieve_response_code( $response ) == '200' ) ) {
						$response_body = wp_remote_retrieve_body( $response );
						if ( !empty( $response_body ) ) {
							$fp = fopen( $dest_filename, 'w+' );
							if ( $fp !== false ) {
								fwrite( $fp, $response_body );
								fclose( $fp );
								$reply_data['status'] = true;
							}
						}
					}
				}
			}
		
		} else {
			$reply_data['status'] = false;
			$reply_data['message'] = __( 'Languages directory not writable.', 'learndash' );
		}
	}
		
	echo json_encode( $reply_data );

	wp_die(); // this is required to terminate immediately and return a proper response			
	
});
*/