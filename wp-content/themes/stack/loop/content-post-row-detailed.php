<li>
	<?php 
		the_title('<a href="'. get_permalink() .'"><h4>', '</h4><span>'. ebor_the_terms('category', ', ', 'name') .'</span></a>');
		the_excerpt();
	?>
</li>