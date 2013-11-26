<?php
/**
 * Customize Control Classes
 *
 * @package WordPress
 * @subpackage hec.lab
 * @since lab.mo.01
 */
 
 
class Lab_Customize_Splash_Preview_Control extends WP_Customize_Control {
    public $type = 'splash-preview';
	public $label = 'Preview splash page' ;
	public $label2 = 'Restore front page' ;
	
	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @since lab.mo.01
	 */
	public function enqueue() {
		wp_enqueue_script( 'lab-splash-preview' , get_stylesheet_directory_uri() . '/js/splash-preview.js', array( 'jquery', 'customize-controls', 'wp-color-picker' ), null);
		//wp_enqueue_style( 'lab-splash-preview' );
	}
	
    /**
	 * Render the control's content.
	 *
	 * @since lab.mo.01
	 */
	public function render_content() {
        ?>
        <label class="lab-splash-preview">
			<input type="hidden" value="<?php $this->value(); ?>" <?php $this->link(); ?> />
	        <p><a class="button preview-button"><?php echo esc_html( $this->label ); ?></a></p>
            <p><a class="button restore-button"><?php echo esc_html( $this->label2 ); ?></a></p>
        </label>
        <?php
    }
    
    
}
 
?>