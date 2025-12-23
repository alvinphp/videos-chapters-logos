<?php
/**
 * Plugin Name: Videos Chapters & Logos
 * Description: Plugin that displays an MP4 video with a logo and markers.
 * Version: 1.0
 * Author: alvingil
 * Text Domain: videos-chapters-logos
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package videos-chapters-logos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'functions/functions.php';

// quitar espacio del shortcode general.
add_filter(
	'the_content',
	function ( $content ) {
		return preg_replace_callback(
			'/\[videos_chapters_logos.*?\]/s',
			function ( $matches ) {
				return vidchlog_clean_shortcode_full( $matches[0] );
			},
			$content
		);
	}
);

add_filter(
	'the_content',
	function ( $content ) {
		$content = preg_replace( '/<p>\s*(\[videos_chapters_logos[^\]]*\])\s*<\/p>/i', '$1', $content );
		return $content;
	}
);

add_filter( 'the_content', 'vidchlog_force_video_inline', 5 );

/**
 * Shortcode para insertar un video MP4 con opciones de personalización.
 *
 * Este shortcode genera el HTML necesario para insertar un video MP4 en una página
 * de WordPress, permitiendo a los usuarios personalizar si el video está en loop,
 * si tiene los controles, y si está en mute. También acepta una URL de video.
 *
 * @param array $atts Atributos pasados al shortcode. Los atributos disponibles son:
 *                    - url: La URL del video MP4.
 *                    - loop: Si el video debe repetirse en bucle (opcional).
 *                    - muted: Si el video debe estar en silencio (opcional).
 *                    - controls: Si el video debe mostrar los controles (opcional).
 * @return string El HTML para mostrar el video con los atributos especificados.
 */
function vidchlog_videos_chapters_logos_shortcode( $atts ) {
	static $video_count = 0;
	++$video_count;

	wp_enqueue_script(
		'videoextendJs',
		plugins_url( 'assets/js/jquery.video-extend.js', __FILE__ ),
		array( 'jquery' ),
		'1.0',
		true
	);

	$atts = shortcode_atts(
		array(
			'video'    => 'Sintel.mp4',
			'logo'     => 'example_logo.png',
			'poster'   => 'poster.png',
			'width'    => '640',
			'height'   => '360',
			'autoplay' => 'false',
			'loop'     => 'false',
			'markers'  => '',
			'id'       => '',
		),
		$atts
	);

	$marcadores = vidchlog_parse_markers_string( $atts['markers'] );

	$atts['video']    = sanitize_file_name( $atts['video'] );
	$atts['logo']     = sanitize_file_name( $atts['logo'] );
	$atts['poster']   = sanitize_file_name( $atts['poster'] );
	$atts['width']    = intval( $atts['width'] );
	$atts['height']   = intval( $atts['height'] );
	$atts['autoplay'] = filter_var( $atts['autoplay'], FILTER_VALIDATE_BOOLEAN );
	$atts['loop']     = filter_var( $atts['loop'], FILTER_VALIDATE_BOOLEAN );

	if ( ! file_exists( plugin_dir_path( __FILE__ ) . 'assets/video/' . $atts['video'] ) ) {
		return '<p>' . __( 'Video not found.', 'videos-chapters-logos' ) . '</p>';
	}

	if ( empty( $atts['id'] ) ) {
		$atts['id'] = 'video_' . $video_count . '_' . uniqid();
	}

	$video_url  = plugins_url( 'assets/video/' . $atts['video'], __FILE__ );
	$logo_url   = plugins_url( 'assets/img/' . $atts['logo'], __FILE__ );
	$poster_url = plugins_url( 'assets/img/' . $atts['poster'], __FILE__ );

	$autoplay = $atts['autoplay'];
	$loop     = $atts['loop'];
	$muted    = $autoplay;

	$video_attrs  = '';
	$video_attrs .= $autoplay ? ' autoplay' : '';
	$video_attrs .= $muted ? ' muted' : '';
	$video_attrs .= $loop ? ' loop' : '';

	wp_enqueue_script(
		'video-extends-init',
		plugins_url( 'assets/js/video-extends-init.js', __FILE__ ),
		array( 'jquery', 'videoextendJs' ),
		'1.0',
		true
	);

	$markers_array = array();
	if ( ! empty( $atts['markers'] ) ) {
		$markers_list = explode( ',', $atts['markers'] );
		foreach ( $markers_list as $marker ) {
			if ( strpos( $marker, '=' ) !== false ) {
				list($time, $text) = explode( '=', $marker, 2 );
				$markers_array[]   = array(
					'time' => vidchlog_convert_to_seconds( trim( $time ) ),
					'text' => trim( $text ),
				);
			}
		}
	}

	if ( empty( $markers_array ) ) {
		$markers_array = array(
			array(
				'time' => vidchlog_convert_to_seconds( '0:39' ),
				'text' => __( 'Chapter 1', 'videos-chapters-logos' ),
			),
			array(
				'time' => vidchlog_convert_to_seconds( '5:50' ),
				'text' => __( 'Chapter 2', 'videos-chapters-logos' ),
			),
			array(
				'time' => vidchlog_convert_to_seconds( '7:50' ),
				'text' => __( 'Chapter 3', 'videos-chapters-logos' ),
			),
			array(
				'time' => vidchlog_convert_to_seconds( '11:17' ),
				'text' => __( 'Chapter 4', 'videos-chapters-logos' ),
			),
		);
	}

	global $vidchlog_videos_data;
	$escaped_markers = array();
	if ( is_array( $markers_array ) && ! empty( $markers_array ) ) {
		foreach ( $markers_array as $marker ) {
			$escaped_markers[] = array(
				'time' => intval( $marker['time'] ),
				'text' => esc_js( sanitize_text_field( $marker['text'] ) ),
			);
		}
	}

	$vidchlog_videos_data[] = array(
		'videoId' => $atts['id'],
		'logoUrl' => esc_url( $logo_url ),
		'markers' => $escaped_markers,
	);

	ob_start(); ?>
<div class="container">
	<video class="video-custom" 
			id="<?php echo esc_attr( $atts['id'] ); ?>"
			width="<?php echo esc_attr( $atts['width'] ); ?>" 
			height="<?php echo esc_attr( $atts['height'] ); ?>"
			poster="<?php echo esc_url( $poster_url ); ?>"
			controls <?php echo esc_attr( $video_attrs ); ?>>
			<source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4">
	</video>
</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'videos_chapters_logos', 'vidchlog_videos_chapters_logos_shortcode' );

add_action(
	'wp_footer',
	function () {
		global $vidchlog_videos_data;
		if ( ! empty( $vidchlog_videos_data ) ) {
			wp_localize_script( 'video-extends-init', 'vidchlog_videoExtendsData', $vidchlog_videos_data );
		}
	}
);
