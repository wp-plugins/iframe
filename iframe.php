<?php

/*
Plugin Name: Iframe
Plugin URI: http://web-profile.com.ua/wordpress/plugins/iframe/
Description: Plugin shows iframe with [iframe width="640" height="480" src="http://player.vimeo.com/video/22262152?title=0"] shortcode.
Version: 1.0.0
Author: webvitaly
Author Email: webvitaly(at)gmail.com
Author URI: http://web-profile.com.ua/
*/


if ( !function_exists( 'iframe_embed_sortcode' ) ) {
	function iframe_embed_sortcode($atts, $content = null) {
		extract(shortcode_atts(array(
			'width' => '640',
			'height' => '480',
			'src' => '',
			'frameborder' => '0',
			'scrolling' => 'no',
			'marginheight' => '0',
			'marginwidth' => '0'
		), $atts));
		return '<iframe width="'.$width.'" height="'.$height.'" frameborder="'.$frameborder.'" scrolling="'.$scrolling.'" marginheight="'.$marginheight.'" marginwidth="'.$marginwidth.'" src="'.$src.'&amp;output=embed" ></iframe>';
	}
	add_shortcode('iframe', 'iframe_embed_sortcode');
}
