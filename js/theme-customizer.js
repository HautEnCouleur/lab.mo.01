/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 * Things like site title and description changes.
 * 
 * @package WordPress
 * @subpackage hec.lab
 * @since lab.mo.01
 */

(function( exports, $ ){
			
	var api = parent.wp.customize ;
	
	// Custom controls initilaization
	
	console.log('theme-customizer');
	
	// Control visibility for default controls
		
	wp.customize('lab_enable_splash', function( value ){
		value.bind(function( to ){
			api.control('lab_splash_page').container.toggle(to);
			api.control('lab_splash_preview').container.toggle(to);
		});
	});
	
	if(!api.get().lab_enable_splash){
		api.control('lab_splash_page').container.hide();
		api.control('lab_splash_preview').container.hide();
	}
					
})( wp, jQuery );