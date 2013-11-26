<?php
/**
 * Configuration code for the Visual Composer plugin
 * 
 * @package WordPress
 * @subpackage hec.lab
 * @since lab.mo.01
 */
 
// Includes
require_once get_stylesheet_directory() . '/inc/js_composer/vc-shortcodes.php';

function lab_vc_init(){
	
	
	/**
	 * Remove unwanted content element
	 */
	 
	vc_remove_element('vc_wp_meta');
	
	/**
	 * Activate new content elements (based on shortcodes)
	 */
	
	lab_shortcode_widget_meta_init();
	
}

?>