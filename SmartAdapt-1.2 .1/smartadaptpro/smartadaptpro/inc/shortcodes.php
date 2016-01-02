<?php

/* Registering a New TinyMCE Plugins*/

add_action( 'init', 'smartadapt_buttons' );
function smartadapt_buttons() {
	add_filter( "mce_external_plugins", "smartadapt_add_buttons" );
	add_filter( 'mce_buttons', 'smartadapt_register_buttons' );
}
function smartadapt_add_buttons( $plugin_array ) {
	$plugin_array['smartadapt'] = get_template_directory_uri() . '/admin/js/editor.plugins.js';
	return $plugin_array;
}
function smartadapt_register_buttons( $buttons ) {
	array_push( $buttons, 'htext', 'highlightedtext' );
	array_push( $buttons, 's_map', 'smartadapt_map' );
	array_push( $buttons, 's_video', 'smartadapt_video' );
	array_push( $buttons, 's_icon', 'smartadapt_icon' );
	return $buttons;
}
/**
 * Add shortcode - highlighted text
 */
class Smartadapt_PullquoteText_Shortcode{
static function init() {
	add_shortcode('smartadapt_pullquote', array(__CLASS__, 'render_shortcode'));
}
	static	function render_shortcode( $atts, $content=null) {
		$html = '<aside class="highlighted_text"><p>';
		$html .= $content;
		$html .='</aside></p>';
		return $html;
	}
}
Smartadapt_PullquoteText_Shortcode::init();


/*
 * Google maps shortcode [based on tutorial: http://www.developerdrive.com/2013/05/creating-a-google-maps-shortcode-for-wordpress/]
 */
class Smartadapt_GoogleMap_Shortcode {
	static $api_key = false;

	static function init() {
		add_shortcode('smartadapt_map', array(__CLASS__, 'render_shortcode'));
		add_action('wp_footer', array(__CLASS__, 'smartadapt_map_javascript'));
	}

	static function render_shortcode($atts) {
		extract( shortcode_atts( array(
			'api_key' => false,
			'id' => 'smartadapt-map',
			'class' => '',
			'zoom' => '18',
			'coords' => '23.339381, 15.260405',
			'type' => 'roadmap',
			'width' => '670px',
			'height' => '300px'
		), $atts ) );

		if(!self::$api_key && $api_key) {
			self::$api_key = $api_key;
		}

		$return = "";

		$map_type_id = "google.maps.MapTypeId.ROADMAP";

		switch ($type) {
			case "roadmap":
				$map_type_id = "google.maps.MapTypeId.ROADMAP";
				break;
			case "satellite":
				$map_type_id = "google.maps.MapTypeId.SATELLITE";
				break;
			case "hybrid":
				$map_type_id = "google.maps.MapTypeId.HYBRID";
				break;
			case "terrain":
				$map_type_id = "google.maps.MapTypeId.TERRAIN";
				break;
		}

		if(self::$api_key) {
			$return = '<div id="'.$id.'" class="map-area '.$class.'" style="width:'.$width.';height:'.$height.';" ></div>';

			$return .= '<script type="text/javascript">';
			$return .= 'jQuery(document).on("ready", function(){ ';
			$return .= 'var options = { center: new google.maps.LatLng('.$coords.'),';
			$return .= 'zoom: ' . $zoom . ', mapTypeId: ' . $map_type_id . ' };';
			$return .= 'var map = new google.maps.Map(document.getElementById("'.$id.'"), options);';
			$return .= 'var marker = new google.maps.Marker({ position: new google.maps.LatLng('.$coords.'), map: map });';
			$return .= '});</script>';

		} else {
			$return = "<div><p>" .__('Please specify your Google Maps API key', 'smartadapt')."</p></div>";
		}

		return $return;
	}
	static function smartadapt_map_javascript() {
		if (self::$api_key ){
		wp_enqueue_script( 'map-js',
				"https://maps.googleapis.com/maps/api/js?key=" . self::$api_key . "&sensor=true",
			"jquery"
		);
		}
	}
}

Smartadapt_GoogleMap_Shortcode::init();

/*
 * Video shortcode
 */
class Smartadapt_Video_Shortcode {

	static function init() {
		add_shortcode('smartadapt_video', array(__CLASS__, 'render_shortcode'));

	}
	static function render_shortcode($atts) {
		extract(shortcode_atts(array(
			'from' 	=> '',
			'id' 	=> '',
			'width' 	=> false,
			'height' 	=> false,
			'autoplay' 	=> ''
		), $atts));

		if ($height && !$width)
			$width = intval($height * 16 / 9);
		if (!$height && $width)
			$height = intval($width * 9 / 16);
		if (!$height && !$width){
			$height = 415;
			$width = 670;
		}
		$autoplay = ($autoplay == 'yes' ? '1' : false);

		$html = '';
		
		if($from == "vimeo")
			$html = "<div class='video-embed'><iframe frameborder='0' src='http://player.vimeo.com/video/$id?autoplay=$autoplay&amp;title=0&amp;byline=0&amp;portrait=0' width='$width' height='$height'></iframe></div>";

		else if($from == "youtube")
			$html = "<div class='video-embed'><iframe frameborder='0' src='http://www.youtube.com/embed/$id?HD=1;rel=0;showinfo=0' width='$width' height='$height'></iframe></div>";

		else if($from == "dailymotion")
			$html ="<div class='video-embed'><iframe frameborder='0' src='http://www.dailymotion.com/embed/video/$id?width=$width&amp;autoPlay={$autoplay}&amp;hideInfos=1&amp;logo=0' width='$width' height='$height'></iframe></div>";



		if (!empty($id))
			return $html;

	}

}
Smartadapt_Video_Shortcode::init();

/**
 * Add shortcode - smartadapt icons
 */
class Smartadapt_Ico_Shortcode{
static function init() {
	add_shortcode('smartadapt_icon', array(__CLASS__, 'render_shortcode'));
}
	static	function render_shortcode( $atts) {

		extract(shortcode_atts(array(
					'class' 	=> 'icon-check-sign'),  $atts));

		$html = '<span class="'.$class.'"></span>';

		return $html;
	}
}
Smartadapt_Ico_Shortcode::init();

?>