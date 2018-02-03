<?php $additional = get_post_meta( $post->ID, '_ebor_meta_repeat_group', true ); ?>

<div class="col-sm-4 col-md-3">
	<?php
		if( $additional ){
			
			foreach( $additional as $index => $item ){
				echo '<div class="text-block">';
				
				if( isset ( $item['_ebor_the_additional_title'] ) )
					echo '<h5>' . htmlspecialchars_decode( $item['_ebor_the_additional_title'] ) . '</h5>';
					
				if( isset ( $item['_ebor_the_additional_content'] ) )
					echo wpautop( htmlspecialchars_decode( $item['_ebor_the_additional_content'] ) );
					
				echo '</div>';
			}
		
		}
	?>
</div>