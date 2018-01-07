<?php 
	$title = get_bloginfo('title');
	$protocols = array('http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet', 'skype'); 
?>

<ul class="social-list list-inline list--hover stack-header-social">
	<?php 
		for( $i = 1; $i < 11; $i++ ){
			if( $url = get_option("header_social_url_$i") ) {
				
				$icon = get_option("header_social_icon_$i");
				
				echo '<li>
					      <a href="' . esc_url($url, $protocols) . '" title="'. $title . ' ' . ucfirst(str_replace('socicon-', '', $icon)) .' '. esc_attr__('Page', 'stack') .'" target="_blank">
						      <i class="socicon icon--xs ' . esc_attr($icon) . '"></i>
					      </a>
					  </li>';
			}
		} 
	?>
</ul>