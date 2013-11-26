/**
 * Action code for the splash preview customizer control
 * 
 * @package WordPress
 * @subpackage hec.lab
 * @since lab.mo.01
 */

( function( $ ){
	
	var api = wp.customize ;
	
	console.log('splash-preview');
	
	// Selector '.lab-splash-preview'
	$( document ).ready(function(){
		
		console.log('splash-preview :: document ready');
		
		$('.lab-splash-preview .preview-button').click(function(e){
			console.log('splash-preview :: button click');
			var pageId =  wp.customize.get().lab_splash_page ;
			console.log('lab_splash_page :: ' + pageId);
			
			wp.customize.control('lab_splash_preview').previewer.targetWindow().location.replace('../');	
			
			$(this).parents('.lab-splash-preview').find('.restore-button').parent('p').show();
			
		});
		
		$('.lab-splash-preview .restore-button').click(function(e){
			console.log('restore-preview :: button click');
			wp.customize.control('lab_splash_preview').previewer.refresh();
			$(this).parent('p').hide();
		});
		
		$('.lab-splash-preview .restore-button').parent('p').hide();
			
		
	});
	
}( jQuery ) );