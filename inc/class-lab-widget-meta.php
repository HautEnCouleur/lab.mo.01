<?php
/**
 * Meta widget class
 *
 * Displays log in/out, RSS feed links, etc.
 *
 *
 * @package WordPress
 * @subpackage hec.lab
 * @since lab.mo.01
 */
class Lab_Widget_Meta extends WP_Widget {
	
	function __construct() {
		$widget_ops = array('classname' => 'widget_lab_meta', 'description' => __( "Log in/out, admin, feed and WordPress links") );
		parent::__construct('lab_meta', __('Meta [hec.lab]','hec.lab'), $widget_ops);
	}

	function widget( $args, $instance ) {
		
		/**
		 * Parameters initilisation & retrieval
		 */
		
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$show_register = isset($instance['register']) ? $instance['register'] : false;
		$show_login = isset($instance['login']) ? $instance['login'] : false;
		$show_feed = isset($instance['feed']) ? $instance['feed'] : false;
		$show_comments_feed = isset($instance['comments-feed']) ? $instance['comments-feed'] : false;
		$show_wordpress = isset($instance['wordpress']) ? $instance['wordpress'] : false;
		
		
		/**
		 * CSS configuration
		 */
		
		$css_login = is_user_logged_in() ? 'lab-meta-logout' : 'lab-meta-login' ;
		$css_register = is_user_logged_in() ? 'lab-meta-admin' : 'lab-meta-register' ;
		
		
		/**
		 * Widget output
		 */
		
		echo $before_widget;
		if ( !empty($title) )
			echo $before_title . $title . $after_title;
			
?>
			<ul class="lab-meta-container">
			<?php if( $show_login ) : ?>
				<li class="<?php echo $css_login ?>"><?php wp_loginout(); ?></li>
			<?php endif; ?>
			<?php if( $show_register ) wp_register('<li class="'.$css_register.'">','</li>'); ?>
			<?php if( $show_feed ) : ?>
				<li class="lab-meta-feed"><a href="<?php bloginfo('rss2_url'); ?>" title="<?php echo esc_attr(__('Syndicate this site using RSS 2.0')); ?>"><?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
			<?php endif; ?>
			<?php if( $show_comments_feed ) : ?>
				<li class="lab-meta-comments-feed"><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php echo esc_attr(__('The latest comments to all posts in RSS')); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
			<?php endif; ?>
			<?php if( $show_wordpress ) : ?>
				<?php echo apply_filters( 'widget_meta_poweredby', sprintf( '<li class="lab-meta-wordpress"><a href="%s" title="%s">%s</a></li>',
					esc_url( __( 'http://wordpress.org/' ) ),
					esc_attr__( 'Powered by WordPress, state-of-the-art semantic personal publishing platform.' ),
					_x( 'WordPress.org', 'meta widget link text' )
				) ); ?>
			<?php endif; ?>
			<?php wp_meta(); ?>
			</ul>
<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['register'] = !empty($new_instance['register']) ? 1 : 0;
		$instance['login'] = !empty($new_instance['login']) ? 1 : 0;
		$instance['feed'] = !empty($new_instance['feed']) ? 1 : 0;
		$instance['comments-feed'] = !empty($new_instance['comments-feed']) ? 1 : 0;
		$instance['wordpress'] = !empty($new_instance['wordpress']) ? 1 : 0;
		
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'register' => true, 'login' => true, 'feed' => true, 'comments-feed' => true, 'wordpress' => true) );
		$title = strip_tags($instance['title']);
?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</p>
			<p>
				<label><?php _e('Display:'); ?></label><br/>
				<input class="checkbox" type="checkbox" <?php checked($instance['register'], true) ?> id="<?php echo $this->get_field_id('register'); ?>" name="<?php echo $this->get_field_name('register'); ?>" />
				<label for="<?php echo $this->get_field_id('register'); ?>"><?php _e('Register'); ?></label><br />
				<input class="checkbox" type="checkbox" <?php checked($instance['login'], true) ?> id="<?php echo $this->get_field_id('login'); ?>" name="<?php echo $this->get_field_name('login'); ?>" />
				<label for="<?php echo $this->get_field_id('login'); ?>"><?php _e('Login / Logout'); ?></label><br />
				<input class="checkbox" type="checkbox" <?php checked($instance['feed'], true) ?> id="<?php echo $this->get_field_id('feed'); ?>" name="<?php echo $this->get_field_name('feed'); ?>" />
				<label for="<?php echo $this->get_field_id('feed'); ?>"><?php _e('RSS feed'); ?></label><br />
				<input class="checkbox" type="checkbox" <?php checked($instance['comments-feed'], true) ?> id="<?php echo $this->get_field_id('comments-feed'); ?>" name="<?php echo $this->get_field_name('comments-feed'); ?>" />
				<label for="<?php echo $this->get_field_id('comments-feed'); ?>"><?php _e('Comments RSS feed'); ?></label><br />
				<input class="checkbox" type="checkbox" <?php checked($instance['wordpress'], true) ?> id="<?php echo $this->get_field_id('wordpress'); ?>" name="<?php echo $this->get_field_name('wordpress'); ?>" />
				<label for="<?php echo $this->get_field_id('wordpress'); ?>"><?php _e('Powered by Worpress'); ?></label><br />
			</p>
<?php
	}
}
?>