<?php 

/**
 * Register Menu Locations For The Theme
 * 
 * @since 1.0.0
 * @author tommusrhodus
 */
if(!( function_exists('ebor_register_nav_menus') )){
	function ebor_register_nav_menus() {
		register_nav_menus( 
			array(
				'primary'  => esc_html__( 'Standard Navigation', 'stack' ),
				'sidebar'  => esc_html__( 'Slide-In Sidebar Navigation', 'stack' ),
				'vertical'  => esc_html__( 'Vertical Menu Navigation', 'stack' ),
				'footer'  => esc_html__( 'Footer Navigation', 'stack' ),
				'top-bar'  => esc_html__( 'Top Bar (Sub Header) Navigation', 'stack' )
			) 
		);
	}
	add_action( 'init', 'ebor_register_nav_menus' );
}

if(!( function_exists('ebor_register_sidebars') )){
	function ebor_register_sidebars() {
	
		register_sidebar( 
			array(
				'id' => 'primary',
				'name' => esc_html__( 'Blog Sidebar', 'stack' ),
				'description' => esc_html__( 'Widgets to be displayed in the blog sidebar.', 'stack' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s sidebar__widget">',
				'after_widget' => '</div>',
				'before_title' => '<h5>',
				'after_title' => '</h5>'
			) 
		);
		
		register_sidebar( 
			array(
				'id' => 'page',
				'name' => esc_html__( 'Page Sidebar', 'stack' ),
				'description' => esc_html__( 'Widgets to be displayed in the page sidebar.', 'stack' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s sidebar__widget">',
				'after_widget' => '</div>',
				'before_title' => '<h5>',
				'after_title' => '</h5>'
			) 
		);
		
		register_sidebar( 
			array(
				'id' => 'shop',
				'name' => esc_html__( 'Shop Sidebar', 'stack' ),
				'description' => esc_html__( 'Widgets to be displayed in the shop sidebar.', 'stack' ),
				'before_widget' => '<div id="%1$s" class="col-md-4 col-sm-6 widget %2$s sidebar__widget">',
				'after_widget' => '</div>',
				'before_title' => '<h5>',
				'after_title' => '</h5>'
			) 
		);
		
		register_sidebar(
			array(
				'id' => 'footer1',
				'name' => esc_html__( 'Footer Column 1', 'stack' ),
				'description' => esc_html__( 'If this is set, your footer will be 1 column', 'stack' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s sidebar__element">',
				'after_widget' => '</div>',
				'before_title' => '<h6 class="type--uppercase">',
				'after_title' => '</h6>'
			)
		);
		
		register_sidebar(
			array(
				'id' => 'footer2',
				'name' => esc_html__( 'Footer Column 2', 'stack' ),
				'description' => esc_html__( 'If this & column 1 are set, your footer will be 2 columns.', 'stack' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s sidebar__element">',
				'after_widget' => '</div>',
				'before_title' => '<h6 class="type--uppercase">',
				'after_title' => '</h6>'
			)
		);
		
		register_sidebar(
			array(
				'id' => 'footer3',
				'name' => esc_html__( 'Footer Column 3', 'stack' ),
				'description' => esc_html__( 'If this & column 1 & column 2 are set, your footer will be 3 columns.', 'stack' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s sidebar__element">',
				'after_widget' => '</div>',
				'before_title' => '<h6 class="type--uppercase">',
				'after_title' => '</h6>'
			)
		);
		
		register_sidebar(
			array(
				'id' => 'footer4',
				'name' => esc_html__( 'Footer Column 4', 'stack' ),
				'description' => esc_html__( 'If this & column 1 & column 2 & column 3 are set, your footer will be 4 columns.', 'stack' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s sidebar__element">',
				'after_widget' => '</div>',
				'before_title' => '<h6 class="type--uppercase">',
				'after_title' => '</h6>'
			)
		);
		
	}
	add_action( 'widgets_init', 'ebor_register_sidebars' );
}

/**
 * Medium rare nav walker.
 * 
 * This nav walker is for themes by tommusrhodus and medium rare.
 * 
 * @since 1.0.0
 * @author tommusrhodus
 */
if(!( class_exists('ebor_medium_rare_bootstrap_navwalker') )){
	class ebor_medium_rare_bootstrap_navwalker extends Walker_Nav_Menu {
		
		public function __construct(){
			global $dropdown_method;
			$dropdown_method = get_option('stack_dropdown_method', 'dropdowns--click');
		}
		
		/**
		 * @see Walker::start_lvl()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int $depth Depth of page. Used for padding.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			$output .= '<div class="dropdown__container"><div class="container"><div class="row"><div class="dropdown__content '. get_option('stack_dropdown_width', 'col-md-2') .' col-sm-4"><ul class="menu-vertical">';
		}
		
		public function end_lvl( &$output, $depth = 0, $args = array() ) {
			$output .= '</ul></div></div></div></div>';
		}
	
		/**
		 * @see Walker::start_el()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param int $current_page Menu item ID.
		 * @param object $args
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			global $dropdown_method;

			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

			/**
			 * Dividers, Headers or Disabled
			 * =============================
			 * Determine whether the item is a Divider, Header, Disabled or regular
			 * menu item. To prevent errors we use the strcasecmp() function to so a
			 * comparison that is not case sensitive. The strcasecmp() function returns
			 * a 0 if the strings are equal.
			 */
			if ( strcasecmp( $item->attr_title, 'divider' ) == 0 && $depth === 1 ) {
				$output .= $indent . '<li role="presentation" class="divider">';
			} else if ( strcasecmp( $item->title, 'divider') == 0 && $depth === 1 ) {
				$output .= $indent . '<li role="presentation" class="divider">';
			} else if ( strcasecmp( $item->attr_title, 'dropdown-header') == 0 && $depth === 1 ) {
				$output .= $indent . '<li role="presentation" class="dropdown-header">' . esc_attr( $item->title );
			} else if ( strcasecmp($item->attr_title, 'disabled' ) == 0 ) {
				$output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_attr( $item->title ) . '</a>';
			} else {
	
				$class_names = $value = '';
	
				$classes = empty( $item->classes ) ? array() : (array) $item->classes;
				$classes[] = 'menu-item-' . $item->ID;
	
				$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
	
				if ( $args->has_children && $depth == 0 || $args->has_children && $depth == 1 ){
					$class_names .= ' dropdown';
					if(!( 'dropdowns--click' == $dropdown_method )){
						$class_names .= ' dropdown--hover';
					}
				}
	
				if ( in_array( 'current-menu-item', $classes ) )
					$class_names .= ' active';
	
				$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
	
				$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
				$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
				
				if(!( $item->object == 'mega_menu' ))
					$output .= $indent . '<li' . $id . $value . $class_names .'>';
	
				$atts = array();
				$atts['target'] = ! empty( $item->target )	? $item->target	: '';
				$atts['rel']    = ! empty( $item->xfn )		? $item->xfn	: '';
	
				// If item has_children add atts to a.
				if ( $args->has_children && $depth === 0 ) {
					$atts['href'] = ! empty( $item->url ) ? $item->url : '';
				} else {
					$atts['href'] = ! empty( $item->url ) ? $item->url : '';
				}
	
				$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
	
				$attributes = '';
				foreach ( $atts as $attr => $value ) {
					if ( ! empty( $value ) ) {
						$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
						$attributes .= ' ' . $attr . '="' . $value . '"';
					}
				}
				
				if(!( $item->object == 'mega_menu' )){
					$item_output = $args->before;
					
					if(!( 'dropdowns--click' == $dropdown_method )){
						
						$item_output .= ( $args->has_children ) ? '<a'. $attributes .' class="dropdown__trigger"><span>' : '<a'. $attributes .'>';
						
					} else {
						
						$item_output .= ( $args->has_children ) ? '<span class="dropdown__trigger">' : '<a'. $attributes .'>';
						
					}
					
					$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
					
					if(!( 'dropdowns--click' == $dropdown_method )){
						
						$item_output .= ( $args->has_children ) ? '</span></a>' : '</a>';
						
					} else {
						
						$item_output .= ( $args->has_children ) ? '</span>' : '</a>';
						
					}
					
					$item_output .= $args->after;
				}
				
				/**
				 * Check if menu item object is a mega menu object.
				 * If it is, display the mega menu content.
				 * Otherwise render elements as normal
				 */
				if( $item->object == 'mega_menu' ) {
					
					$mega_menu_class = '6';
					$dropdown_class = '12';
					
					if(!( '' == get_post_meta($item->object_id, '_ebor_menu_width', 1) )){
						$dropdown_class	= get_post_meta($item->object_id, '_ebor_menu_width', 1);
					}
					
					if(!( '' == get_post_meta($item->object_id, '_ebor_menu_2', 1) )){
						$mega_menu_class = '3';	
					}
					
					if(!( '' == get_post_meta($item->object_id, '_ebor_menu_3', 1) )){
						$mega_menu_class = '2';	
					}

					$output .= '<li class="dropdown '. $item->classes[0].'">';
					
					if( !( 'dropdowns--click' == $dropdown_method ) && $url = get_post_meta($item->object_id, '_ebor_top_level_url', 1) ){
							
						$output .= '<a href="'. esc_url($url) .'" class="dropdown__trigger"><span>'. $item->title .'</span></a>';
							
					} else {
						
						$output .= '<span class="dropdown__trigger">'. $item->title .'</span>';	
					}
							
							
					$output .= '<div class="dropdown__container"><div class="container"><div class="row">
							<div class="dropdown__content dropdown__content--lg col-sm-'. $dropdown_class .'">
								<div class="pos-absolute col-md-5 imagebg hidden-sm hidden-xs" data-overlay="4">
									<div class="background-image-holder">'. get_the_post_thumbnail($item->object_id) .'</div>
									<div class="container pos-vertical-center">
										<div class="row">
											<div class="col-md-10 col-md-offset-1">'. do_shortcode(get_post_field('post_content', $item->object_id)) .'</div>
										</div><!--end of row-->
									</div>
								</div>
					';
					
					if(!( '' == get_post_meta($item->object_id, '_ebor_menu_1', 1) )){
						
						$menu_post = wp_get_nav_menu_items(get_post_meta($item->object_id, '_ebor_menu_1', 1));
						
						$output .= '<div class="col-md-'. $mega_menu_class .' col-md-offset-6 col-sm-4"><ul class="menu-vertical">';
                                
                        foreach( $menu_post as $menu_item ){
                        	$before = ( '#' == $menu_item->url ) ? '<h5>' : '<a href="'. $menu_item->url .'">';
                        	$after = ( '#' == $menu_item->url ) ? '</h5>' : '</a>';
                        	
                        	$output .= '<li class="'. implode(' ', $menu_item->classes) .'">'. $before . $menu_item->title . $after .'</li>';	
                        }
                        
                        $output .= '</ul></div>';	
						
					}
					
					if(!( '' == get_post_meta($item->object_id, '_ebor_menu_2', 1) )){
						
						$menu_post = wp_get_nav_menu_items(get_post_meta($item->object_id, '_ebor_menu_2', 1));
						
						$output .= '<div class="col-md-'. $mega_menu_class .' col-sm-4"><ul class="menu-vertical">';
					            
					    foreach( $menu_post as $menu_item ){
					    	$before = ( '#' == $menu_item->url ) ? '<h5>' : '<a href="'. $menu_item->url .'">';
					    	$after = ( '#' == $menu_item->url ) ? '</h5>' : '</a>';
					    	
					    	$output .= '<li class="'. implode(' ', $menu_item->classes) .'">'. $before . $menu_item->title . $after .'</li>';	
					    }
					    
					    $output .= '</ul></div>';	
						
					}
					
					if(!( '' == get_post_meta($item->object_id, '_ebor_menu_3', 1) )){
						
						$menu_post = wp_get_nav_menu_items(get_post_meta($item->object_id, '_ebor_menu_3', 1));
						
						$output .= '<div class="col-md-'. $mega_menu_class .' col-sm-4"><ul class="menu-vertical">';
					            
					    foreach( $menu_post as $menu_item ){
					    	$before = ( '#' == $menu_item->url ) ? '<h5>' : '<a href="'. $menu_item->url .'">';
					    	$after = ( '#' == $menu_item->url ) ? '</h5>' : '</a>';
					    	
					    	$output .= '<li class="'. implode(' ', $menu_item->classes) .'">'. $before . $menu_item->title . $after .'</li>';	
					    }
					    
					    $output .= '</ul></div>';	
						
					}

					$output .= '</div><!--end dropdown content--></div></div></div></li>';
					
				} else {
					
					$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
					
				}
	
			}
		}
	
		/**
		 * Traverse elements to create list from elements.
		 *
		 * Display one element if the element doesn't have any children otherwise,
		 * display the element and its children. Will only traverse up to the max
		 * depth and no ignore elements under that depth.
		 *
		 * This method shouldn't be called directly, use the walk() method instead.
		 *
		 * @see Walker::start_el()
		 * @since 2.5.0
		 *
		 * @param object $element Data object
		 * @param array $children_elements List of elements to continue traversing.
		 * @param int $max_depth Max depth to traverse.
		 * @param int $depth Depth of current element.
		 * @param array $args
		 * @param string $output Passed by reference. Used to append additional content.
		 * @return null Null on failure with no changes to parameters.
		 */
		public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
	        if ( ! $element )
	            return;
	
	        $id_field = $this->db_fields['id'];
	
	        // Display this element.
	        if ( is_object( $args[0] ) )
	           $args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
	
	        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	    }
	
		/**
		 * Menu Fallback
		 * =============
		 * If this function is assigned to the wp_nav_menu's fallback_cb variable
		 * and a manu has not been assigned to the theme location in the WordPress
		 * menu manager the function with display nothing to a non-logged in user,
		 * and will add a link to the WordPress menu manager if logged in as an admin.
		 *
		 * @param array $args passed from the wp_nav_menu function.
		 *
		 */
		public static function fallback( $args ) {
			if ( current_user_can( 'manage_options' ) ) {
	
				extract( $args );
	
				$fb_output = null;
	
				if ( $container ) {
					$fb_output = '<' . $container;
	
					if ( $container_id )
						$fb_output .= ' id="' . $container_id . '"';
	
					if ( $container_class )
						$fb_output .= ' class="' . $container_class . '"';
	
					$fb_output .= '>';
				}
	
				$fb_output .= '<ul';
	
				if ( $menu_id )
					$fb_output .= ' id="' . $menu_id . '"';
	
				if ( $menu_class )
					$fb_output .= ' class="' . $menu_class . '"';
	
				$fb_output .= '>';
				$fb_output .= '<li><a href="' . esc_url(admin_url( 'nav-menus.php' )) . '">Add a menu</a></li>';
				$fb_output .= '</ul>';
	
				if ( $container )
					$fb_output .= '</' . $container . '>';
	
				echo htmlspecialchars_decode($fb_output);
			}
		}
	}
}