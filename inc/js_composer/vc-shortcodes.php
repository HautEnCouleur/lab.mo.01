<?php
/**
 * Custom shortcodes for the Visual Composer plugin
 *
 * @package WordPress
 * @subpackage hec.lab
 * @since lab.mo.01
 */


// Just in case the class was not loaded yet
//require_once get_stylesheet_directory() . '/inc/class-lab-widget-meta.php';

class WPBakeryShortCode_Lab_Wp_meta extends WPBakeryShortCode {

    /*
     * Returns the HTML code for the shortcode
     *
     * WARNING : If you use the template directory feature for shortcodes of the Visual composer,
     * you need to "display" the output (echo) instead of returning it (which is done here)
     *
     * @param $atts - shortcode attributes
     * @param @content - shortcode content
     *
     * @access protected
     *
     * @return string
     */
     protected function content($atts, $content = null) {
		$output = $title = $content = $el_class = '';
		extract( shortcode_atts( array(
		    'title' => __('Meta'),
            'el_position' => '',
		    'options' => '',//'register,login,feed,comments-feed,wordpress',
		    'el_class' => ''
		), $atts ) );
		
		/*
		array(  __('Register', 'hec.lab') => 'register', 
				__('Login / Logout', 'hec.lab') => 'login', 
				__('RSS Feed', 'hec.lab') => 'feed', 
				__('Comments RSS feed', 'hec.lab') => 'comments-feed', 
				__('Powered by Wordpress', 'hec.lab') => 'wordpress' 
		),

		*/
		
		$options_array = explode(",", $options);
		if (in_array("register", $options_array)) $atts['register'] = true;
		if (in_array("login", $options_array)) $atts['login'] = true;
		if (in_array("feed", $options_array)) $atts['feed'] = true;
		if (in_array("comments-feed", $options_array)) $atts['comments-feed'] = true;
		if (in_array("wordpress", $options_array)) $atts['wordpress'] = true;
		
		$el_class = $this->getExtraClass($el_class);
		$css_class =  apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_wp_meta wpb_content_element'.$el_class, $this->settings['base']);
        
		$output = '<div class="'.$css_class.'">';
		$type = 'Lab_Widget_Meta';
		$args = array();
		
		ob_start();
		the_widget( $type, $atts, $args );
		$output .= ob_get_clean();
		
		$output .= '</div>' . $this->endBlockComment('vc_wp_meta') . "\n";
		//$output = $this->startRow($el_position) . $output . $this->endRow($el_position);
        
        
        // WARNING : Code inside the function and not in template file => we need to "return" the output here
		return $output;
	}
}

function lab_shortcode_widget_meta_init(){
	vc_map( array(
	  'name' => 'WP ' . __('Meta [hec.lab]'),
	  'base' => 'lab_wp_meta',
	  'icon' => 'icon-wpb-wp',
	  'category' => __('WordPress Widgets', 'js_composer'),
	  'class' => 'wpb_vc_wp_widget',
	  'params' => array(
	    array(
	      'type' => 'textfield',
	      'heading' => __('Widget title', 'js_composer'),
	      'param_name' => 'title',
	      'description' => __('What text use as a widget title. Leave blank to REMOVE the title.', 'hec.lab')
	    ),
	    array(
	      'type' => 'checkbox',
	      'heading' => __('Content', 'hec.lab'),
	      'param_name' => 'options',
		  'value' => array(__('Register', 'hec.lab') => 'register', 
	      					__('Login / Logout', 'hec.lab') => 'login', 
	      					__('RSS Feed', 'hec.lab') => 'feed', 
	      					__('Comments RSS feed', 'hec.lab') => 'comments-feed', 
	      					__('Powered by Wordpress', 'hec.lab') => 'wordpress' 
		  			),
		  'admin_label' => true,
	      'description' => __('Select the content of the meta widget.', 'hec.lab')
	    
	    ),
	    array(
	      'type' => 'textfield',
	      'heading' => __('Extra class name', 'js_composer'),
	      'param_name' => 'el_class',
	      'description' => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer')
	    )
	  )
	) );
}

add_shortcode( 'lab_wp_meta', 'lab_shortcode_widget_meta' );



?>
