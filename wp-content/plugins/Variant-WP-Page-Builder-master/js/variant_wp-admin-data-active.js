jQuery(document).ready(function() {
	
	/**
	 * Build variant into Yoast SEO analysis
	 */
	if( window.YoastSEO ){
	
		variantPageBuilderYoastExtension = function() {
		  YoastSEO.app.registerPlugin( 'variantPageBuilderYoastExtension', {status: 'ready'} );
		  YoastSEO.app.registerModification( 'content', this.readVariantContent, 'variantPageBuilderYoastExtension', 5 );
		}
		
		/**
		 * Adds the variant data text into Yoast to analyse
		 *
		 * @param data The data to modify
		 */
		variantPageBuilderYoastExtension.prototype.readVariantContent = function(data) {
			return wp_data.page_content;
		};
		
		new variantPageBuilderYoastExtension();
	
	}
	
});