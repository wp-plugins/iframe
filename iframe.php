<?php
/*
Plugin Name: iframe
Plugin URI: http://wordpress.org/extend/plugins/iframe/
Description: [iframe src="http://player.vimeo.com/video/819138" width="100%" height="480"] shortcode
Version: 2.4
Author: webvitaly
Author URI: http://profiles.wordpress.org/webvitaly/
License: GPLv2 or later
*/


if ( !function_exists( 'iframe_embed_shortcode' ) ) :

	function iframe_enqueue_script() {
		wp_enqueue_script( 'jquery' );
	}
	add_action('wp_enqueue_scripts', 'iframe_enqueue_script');
	
	function iframe_embed_shortcode($atts, $content = null) {
		$defaults = array(
			'src' => 'http://player.vimeo.com/video/819138',
			'width' => '100%',
			'height' => '480',
			'scrolling' => 'no',
			'class' => 'iframe-class',
			'frameborder' => '0'
		);

		foreach ($defaults as $default => $value) { // add defaults
			if (!@array_key_exists($default, $atts)) { // hide warning with "@" when no params at all
				$atts[$default] = $value;
			}
		}

		$src_cut = substr($atts["src"], 0, 35); // special case maps
		if(strpos($src_cut, 'maps.google' )){
			$atts["src"] .= '&output=embed';
		}
		$html = '';
		if( isset( $atts["same_height_as"] ) ){
			$same_height_as = $atts["same_height_as"];
		}else{
			$same_height_as = '';
		}
		
		if( $same_height_as != '' ){
			$atts["same_height_as"] = '';
			if( $same_height_as != 'content' ){ // we are setting the height of the iframe like as target element
				if( $same_height_as == 'document' || $same_height_as == 'window' ){ // remove quotes for window or document selectors
					$target_selector = $same_height_as;
				}else{
					$target_selector = '"' . $same_height_as . '"';
				}
				$html .= '
					<script>
					jQuery(document).ready(function($) {
						var target_height = $(' . $target_selector . ').height();
						$("iframe.' . $atts["class"] . '").height(target_height);
						//alert(target_height);
					});
					</script>
				';
			}else{ // set the actual height of the iframe (show all content of the iframe without scroll)
				$html .= '
					<script>
					jQuery(document).ready(function($) {
						$("iframe.' . $atts["class"] . '").bind("load", function() {
							var embed_height = $(this).contents().find("body").height();
							$(this).height(embed_height);
						});
					});
					</script>
				';
			}
		}
        $html .= "\n".'<!-- iframe plugin v.2.4 wordpress.org/extend/plugins/iframe/ -->'."\n";
		$html .= '<iframe';
        foreach ($atts as $attr => $value) {
			if( $attr != 'same_height_as' ){ // remove some attributes
				if( $value != '' ) { // adding all attributes
					$html .= ' ' . $attr . '="' . $value . '"';
				} else { // adding empty attributes
					$html .= ' ' . $attr;
				}
			}
		}
		$html .= '></iframe>';
		return $html;
	}
	add_shortcode('iframe', 'iframe_embed_shortcode');
	
endif;


function iframe_unqprfx_plugin_meta( $links, $file ) { // add 'Support' and 'Donate' links to plugin meta row
	if ( strpos( $file, 'iframe.php' ) !== false ) {
		$links = array_merge( $links, array( '<a href="http://web-profile.com.ua/wordpress/plugins/iframe/" title="Need help?">' . __('Support') . '</a>' ) );
		$links = array_merge( $links, array( '<a href="http://web-profile.com.ua/donate/" title="Support the development">' . __('Donate') . '</a>' ) );
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'iframe_unqprfx_plugin_meta', 10, 2 );