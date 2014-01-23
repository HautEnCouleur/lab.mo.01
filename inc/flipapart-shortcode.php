<?php

// See soundcloud-shortcode.php for inspiration ^^

/* TODO : Register oEmbed provider
   -------------------------------------------------------------------------- */
// wp_oembed_add_provider('#https?://(beta\.)?flipapart\.com/.*#i', 'http://beta.flipapart.com/battle/embeded', true);


/* Register SoundCloud shortcode
   -------------------------------------------------------------------------- */

add_shortcode("flipapart", "flipapart_shortcode");


/**
 * SoundCloud shortcode handler
 * @param  {string|array}  $atts     The attributes passed to the shortcode like [flipapart attr1="value" /].
 *                                   Is an empty string when no arguments are given.
 * @param  {string}        $content  The content between non-self closing [flipapart]…[/flipapart] tags.
 * @return {string}                  Widget embed code HTML
 */
function flipapart_shortcode($atts, $content = null) {

	// Parsing content
	$url_content = parse_url(trim($content));
	$path = explode("/",$url_content['path']);
	$embebed_id = $path[2];

	// Custom shortcode options
	$shortcode_options = array_merge(array('id' => $embebed_id), is_array($atts) ? $atts : array());
	
	// Turn shortcode option "param" (param=value&param2=value) into array
	$shortcode_params = array();
	if (isset($shortcode_options['params'])) {
	parse_str(html_entity_decode($shortcode_options['params']), $shortcode_params);
	}
	$shortcode_options['params'] = $shortcode_params;
	
	// Needs to be an array
	if (!isset($plugin_options['params'])) { $plugin_options['params'] = array(); }
	
	// plugin options < shortcode options
	$options = array_merge(
	$plugin_options,
	$shortcode_options
	);
	
	// plugin params < shortcode params
	$options['params'] = array_merge(
	$plugin_options['params'],
	$shortcode_options['params']
	);
	
	// The "url" option is required
	if (!isset($options['id'])) {
	return 'NO BATTLE ID';
	} else {
	$options['id'] = trim($options['id']);
	}
	
	// Both "width" and "height" need to be integers
	if (isset($options['width']) && !preg_match('/^\d+$/', $options['width'])) {
	// set to 0 so oEmbed will use the default 100% and WordPress themes will leave it alone
	$options['width'] = 0;
	}
	if (isset($options['height']) && !preg_match('/^\d+$/', $options['height'])) { unset($options['height']); }
	
	return flipapart_iframe_widget($options);

}

/**
 * Iframe widget embed code
 * @param  {array}   $options  Parameters
 * @return {string}            Iframe embed code
 */
function flipapart_iframe_widget($options) {
	
  // Merge in "url" value
  $options['params'] = array_merge(array(
  'battle_id' => $options['id']
  ), $options['params']);

  // Build URL
  $url = 'http://beta.flipapart.com/battle/embeded/?' . http_build_query($options['params']);
  // Set default width if not defined
  $width = isset($options['width']) && $options['width'] !== 0 ? $options['width'] : 'auto';
  // Set default height if not defined
  $height = isset($options['height']) && $options['height'] !== 0 ? $options['height'] : '300';

  return sprintf('<iframe width="%s" height="%s" scrolling="no" frameborder="no" src="%s"></iframe>', $width, $height, $url);
}

?>
