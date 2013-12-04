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
		$show_profile = isset($instance['profile']) ? $instance['profile'] : false;
		$show_edit = isset($instance['edit-page']) ? $instance['edit-page'] : false;
		$show_feed = isset($instance['feed']) ? $instance['feed'] : false;
		$show_comments_feed = isset($instance['comments-feed']) ? $instance['comments-feed'] : false;
		$show_wordpress = isset($instance['wordpress']) ? $instance['wordpress'] : false;
		
		
		/**
		 * CSS configuration
		 */
		
		$css_login = is_user_logged_in() ? 'lab-meta-icon lab-meta-logout' : 'lab-meta-login' ;
		$css_register = is_user_logged_in() ? 'lab-meta-icon lab-meta-admin' : 'lab-meta-register' ;
		
		
		/**
		 * Widget output
		 */
		
		echo $before_widget;
		if ( !empty($title) )
			echo $before_title . $title . $after_title;
			
?>
			<!-- <ul id="container" class="js-masonry lab-meta-container" data-masonry-options='{ "columnWidth": 450, "itemSelector": ".lab-meta-item" }'> -->
			<ul id="container" class="lab-meta-container">
			<?php if( $show_profile & is_user_logged_in() ) : ?>
				<li class="lab-meta-item lab-meta-icon lab-meta-profile"><a href="<?php echo get_edit_user_link(); ?>" title="<?php echo esc_attr(__('Edit your profile')); ?>"><?php echo get_lab_meta_avatar(); ?></a></li>
			<?php endif; ?>
			<?php if( $show_login ) : ?>
				<li class="lab-meta-item <?php echo $css_login ?>"><?php wp_loginout(); ?></li>
			<?php endif; ?>
			<?php if( $show_register ) wp_register('<li class="lab-meta-item '.$css_register.'">','</li>'); ?>
			<?php if( $show_edit & current_user_can( 'edit_post', get_post() ) & is_singular() ) : ?>
				<li class="lab-meta-item lab-meta-icon lab-meta-edit"><a href="<?php echo get_edit_post_link(); ?>" title="<?php echo esc_attr(__('Edit this page')); ?>"></a></li>
			<?php endif; ?>
			<?php if( $show_feed ) : ?>
				<li class="lab-meta-item lab-meta-icon lab-meta-feed"><a href="<?php bloginfo('rss2_url'); ?>" title="<?php echo esc_attr(__('Syndicate this site using RSS 2.0')); ?>"><?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
			<?php endif; ?>
			<?php if( $show_comments_feed ) : ?>
				<li class="lab-meta-item lab-meta-icon lab-meta-comments-feed"><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php echo esc_attr(__('The latest comments to all posts in RSS')); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
			<?php endif; ?>
			<?php if( $show_wordpress ) : ?>
				<?php echo apply_filters( 'widget_meta_poweredby', sprintf( '<li class="lab-meta-item lab-meta-icon lab-meta-wordpress"><a href="%s" title="%s">%s</a></li>',
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
		$instance['profile'] = !empty($new_instance['profile']) ? 1 : 0;
		$instance['edit-page'] = !empty($new_instance['edit-page']) ? 1 : 0;
		$instance['feed'] = !empty($new_instance['feed']) ? 1 : 0;
		$instance['comments-feed'] = !empty($new_instance['comments-feed']) ? 1 : 0;
		$instance['wordpress'] = !empty($new_instance['wordpress']) ? 1 : 0;
		
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'register' => true, 'login' => true,  'profile' => true,  'edit-page' => true, 'feed' => true, 'comments-feed' => true, 'wordpress' => true) );
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
				<input class="checkbox" type="checkbox" <?php checked($instance['profile'], true) ?> id="<?php echo $this->get_field_id('profile'); ?>" name="<?php echo $this->get_field_name('profile'); ?>" />
				<label for="<?php echo $this->get_field_id('profile'); ?>"><?php _e('Profile page'); ?></label><br />
				<input class="checkbox" type="checkbox" <?php checked($instance['edit-page'], true) ?> id="<?php echo $this->get_field_id('edit-page'); ?>" name="<?php echo $this->get_field_name('edit-page'); ?>" />
				<label for="<?php echo $this->get_field_id('edit-page'); ?>"><?php _e('Edit link'); ?></label><br />
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

/**
 * Utility functions
 */
 
 
/**
 * Borrowed from http://codex.wordpress.org/Using_Gravatars#Checking_for_the_Existence_of_a_Gravatar
 * TODO : Implement the better version decribed here https://gist.github.com/justinph/5197810
 */
function validate_gravatar($email) {
	// Craft a potential url and test its headers
	$hash = md5(strtolower(trim($email)));
	$uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
	$headers = @get_headers($uri);
	if (!preg_match("|200|", $headers[0])) {
		$has_valid_avatar = FALSE;
	} else {
		$has_valid_avatar = TRUE;
	}
	return $has_valid_avatar;
} 
 
 
function get_lab_meta_avatar(){
	 // Detect the WP User Avatar plugin (http://wordpress.org/plugins/wp-user-avatar/) 
	 if( function_exists("get_wp_user_avatar") ){
		 return get_wp_user_avatar( null, 'original');
	 } else if(validate_gravatar(wp_get_current_user()->user_email)) {
		 return get_avatar(get_current_user_id());
	 }
}

?>