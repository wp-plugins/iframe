<?php
/*
Plugin Name: Iframe
Plugin URI: http://web-profile.com.ua/wordpress/plugins/iframe/
Description: Plugin shows iframe with [iframe src="http://player.vimeo.com/video/3261363" width="100%" height="480"] shortcode.
Version: 1.6.0
Author: webvitaly
Author Email: webvitaly(at)gmail.com
Author URI: http://web-profile.com.ua/wordpress/
*/

if ( !function_exists( 'iframe_embed_shortcode' ) ) :

	function iframe_enqueue_script() {
		wp_enqueue_script( 'jquery' );
	}
	add_action('wp_enqueue_scripts', 'iframe_enqueue_script');
				
	function iframe_embed_shortcode($atts, $content = null) {
		extract(shortcode_atts(array(
			'width' => '100%',
			'height' => '480',
			'src' => '',
			'frameborder' => '0',
			'scrolling' => 'no',
			'marginheight' => '0',
			'marginwidth' => '0',
			'allowtransparency' => 'true',
			'id' => '',
			'class' => 'iframe-class',
			'same_height_as' => ''
		), $atts));
		$src_cut = substr($src, 0, 35);
		if(strpos($src_cut, 'maps.google' )){
			$google_map_fix = '&output=embed';
		}else{
			$google_map_fix = '';
		}
		$return = '';
		if( $same_height_as != '' ){
			if( $same_height_as != 'content' ){ // we are setting the height of the iframe like as target element
				$return .= '
					<script>
					jQuery(document).ready(function($) {
						var target_height = $("'.$same_height_as.'").height();
						$("iframe.'.$class.'").height(target_height);
					});
					</script>
				';
			}else{ // set the actual height of the iframe (show all content of the iframe without scroll)
				$return .= '
					<script>
					jQuery(document).ready(function($) {
						$("iframe.'.$class.'").bind("load", function() {
							var embed_height = $(this).contents().find("body").height();
							$(this).height(embed_height);
						});
					});
					</script>
				';
			}
		}
		if( $id != '' ){
			$id_text = 'id="'.$id.'" ';
		}else{
			$id_text = '';
		}
		$return .= '<iframe '.$id_text.'class="'.$class.'" width="'.$width.'" height="'.$height.'" src="'.$src.$google_map_fix.'" frameborder="'.$frameborder.'" scrolling="'.$scrolling.'" marginheight="'.$marginheight.'" marginwidth="'.$marginwidth.'" allowtransparency="'.$allowtransparency.'"></iframe>';
		// &amp;output=embed
		return $return;
	}
	add_shortcode('iframe', 'iframe_embed_shortcode');
	
endif;
