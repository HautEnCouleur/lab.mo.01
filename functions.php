<?php
/**
 * 
 * 
 * @package WordPress
 * @subpackage hec.lab
 * @since lab.mo.01
 */
 
$content_width = 1040;

global $is_customizer_preview ;
$is_customizer_preview = false ;

function lab_is_customizer_preview(){
	global $is_customizer_preview ;
	return $is_customizer_preview;
}

function lab_set_customizer_preview($val){
	global $is_customizer_preview ;
	$is_customizer_preview = $val ;
}

function lab_is_splash(){
	if( !lab_is_customizer_preview() && (get_option('lab_enable_splash') == 1) ){
		$home_path = trim( parse_url( home_url(), PHP_URL_PATH ), '/');
		$current_path = trim( $_SERVER['REQUEST_URI'], '/') ;
		return $home_path == $current_path ;
	} else {
		return false;
	}
}

function lab_get_home_url(){
	$nId = get_option("page_on_front");
	$sLink = get_permalink($nId);
	
	if($sLink){
		return $sLink;
	} else{
		return home_url('/');
	}
	
}

function lab_setup_theme(){
	
	show_admin_bar( !lab_is_splash() );
	
}
add_action('after_setup_theme','lab_setup_theme');

function lab_widgets_init(){
	
	unregister_widget('WP_Widget_Meta');
	require_once get_stylesheet_directory() . '/inc/class-lab-widget-meta.php';
	register_widget('Lab_Widget_Meta');
	
}
add_action('widgets_init','lab_widgets_init');


function lab_plugins_init(){
	
	// Visual composer extensions (js_composer)
	if (class_exists('WPBakeryVisualComposerAbstract')) {
		require_once get_stylesheet_directory() . '/inc/js_composer/vc-config.php';
		lab_vc_init();
	}
	
}
add_action('widgets_init','lab_plugins_init',100);


/**
 * Adding new options to the theme customizer screen
 *
 * @since lab.mo.01
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 * @return void
 */
function lab_customize_register( $wp_customize ) {
	
	// cf. http://codex.wordpress.org/Theme_Customization_API
	// cf. http://codex.wordpress.org/Plugin_API/Action_Reference/customize_register
	
	require_once get_stylesheet_directory() . '/inc/class-lab-customize-controls.php';
	
	
	$wp_customize->add_setting( 'lab_enable_splash' , array(
    	'default'     => false,
    	'capability' => 'manage_options',
        'type'       => 'option',
        'transport'   => 'postMessage',
	) );
	
	$wp_customize->add_control('lab_enable_splash', array(
        'settings' => 'lab_enable_splash',
        'label'    => __('Enable splash page', 'hec.lab' ),
        'section'  => 'static_front_page',
        'type'     => 'checkbox',
    ));
	
	$wp_customize->add_setting( 'lab_splash_page', array(
		'type'       => 'option',
		'capability' => 'manage_options',
        'transport'   => 'postMessage',
	) );

	$wp_customize->add_control( 'lab_splash_page', array(
		'settings' => 'lab_splash_page',
        'label'      => __( 'Splash page' , 'hec.lab' ),
		'section'    => 'static_front_page',
		'type'       => 'dropdown-pages',
	) );

	$wp_customize->add_setting( 'lab_splash_preview', array(
		'capability' => 'manage_options',
        'transport'   => 'postMessage',
	) );
	
	$wp_customize->add_control(  new Lab_Customize_Splash_Preview_Control( $wp_customize, 'lab_splash_preview', array(
		'settings' => 'lab_splash_preview',
        'label'      => __( 'Preview splash page' , 'hec.lab' ),
		'label2'      => __( 'Restore front page' , 'hec.lab' ),
		'section'    => 'static_front_page',
	) ) );

	lab_set_customizer_preview($wp_customize->is_preview());
	
}
add_action( 'customize_register', 'lab_customize_register' );

/**
 * Enqueue Javascript postMessage handlers for the Customizer.
 *
 * Binds JavaScript handlers to make the Customizer preview
 * reload changes asynchronously.
 *
 * @since lab.mo.01
 *
 * @return void
 */
function lab_customize_preview_js() {
	wp_enqueue_script( 'lab-customizer', get_stylesheet_directory_uri() . '/js/theme-customizer.js', array( 'jquery','customize-preview' ), null  );
}
add_action( 'customize_preview_init', 'lab_customize_preview_js' );	


function lab_customize_update_options() {
	update_option('lab_enable_splash',get_option('lab_enable_splash'));
	update_option('lab_splash_page',get_option('lab_splash_page'));
	
	// TODO : options cleaning button
}
//add_action( 'customize_preview_init', 'lab_customize_update_options' );	


		
function lab_splash_page_hook($query) {
	
	//lab_debug_set(get_option('lab_enable_splash','not found'));
	
	if( (get_option('lab_enable_splash') == 1) && !lab_is_customizer_preview() ){
		
		if ( lab_is_splash() && $query->is_main_query() ) {
	        $query->set( 'page_id', get_option('lab_splash_page') );
	    }
	    
    }
}
add_action( 'pre_get_posts', 'lab_splash_page_hook' );

/**
 * Retrieve the permalink for current page or page ID.
 *
 * Respects page_on_front. Use this one.
 *
 * @since 1.5.0
 *
 * @param int|object $post Optional. Post ID or object.
 * @param bool $leavename Optional, defaults to false. Whether to keep page name.
 * @param bool $sample Optional, defaults to false. Is it a sample permalink.
 * @return string
 */
function lab_get_page_link( $url = '', $id = 0 ) {
	if ( is_object($id) && isset($id->filter) && 'sample' == $id->filter ) {
		$post = $id;
		$sample = true;
	} else {
		$post = get_post($id);
		$sample = false;
	}

	if ( empty($post->ID) )
		return false;
		
	return _get_page_link( $post, false, $sample );
}
if( get_option('lab_enable_splash') == 1 ){
	add_filter( 'page_link', 'lab_get_page_link', 100, 2 );
}



function lab_home_canonical($path){
	if( (get_option('lab_enable_splash') == 1) && (trim($path,'/') == "home") ){
		return false;
	}
}
add_filter('redirect_canonical', 'lab_home_canonical');

?>