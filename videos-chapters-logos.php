<?php
/**
 * Plugin Name: Videos Chapters & Logos
 * Description: Plugin that displays an MP4 video with a logo and markers.
 * Version: 1.6
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
require_once plugin_dir_path( __FILE__ ) . 'functions/enqueue.php';
require_once plugin_dir_path( __FILE__ ) . 'functions/database.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/panel.php';
require_once plugin_dir_path( __FILE__ ) . 'widget/class-vidchlog-video-widget.php';
require_once plugin_dir_path( __FILE__ ) . 'include/shortcode-enqueue.php';
require_once plugin_dir_path( __FILE__ ) . 'include/hooks.php';
require_once plugin_dir_path( __FILE__ ) . 'include/shortcode-localize.php';

/**
 * Shortcode para mostrar un video.
 *
 * Uso:
 * [videos_chapters_logos id="5"]
 *
 * @param array $atts Atributos del shortcode.
 * @return string HTML del reproductor.
 */
function vidchlog_videos_chapters_logos_shortcode( $atts ) {

	$idvideo = isset( $atts['id'] ) ? absint( $atts['id'] ) : 0;
	$video   = $idvideo ? vidchlog_get_video_by_id( $idvideo ) : false;

	if ( ! $video ) {
		return '<p>' . esc_html__(
			'Video not found.',
			'videos-chapters-logos'
		) . '</p>';
	}

	$unique_dom_id = 'video_' . $idvideo . '_' . uniqid();

	$video_file  = sanitize_file_name( $video->video );
	$logo_file   = sanitize_file_name( $video->logo );
	$poster_file = sanitize_file_name( $video->poster );

	$video_path = plugin_dir_path( __FILE__ )
		. 'assets/video/'
		. $video_file;

	if ( ! file_exists( $video_path ) ) {
		return '<p>' . esc_html__(
			'Video file not found.',
			'videos-chapters-logos'
		) . '</p>';
	}

	$video_url = plugins_url(
		'assets/video/' . $video_file,
		__FILE__
	);

	$logo_url = '';

	if ( ! empty( $logo_file ) ) {

		$logo_url = plugins_url(
			'assets/img/' . $logo_file,
			__FILE__
		);

	}

	$poster_url = plugins_url(
		'assets/img/' . $poster_file,
		__FILE__
	);

	$autoplay = (bool) $video->autoplay;
	$muted    = (bool) $video->muted;
	$loop     = (bool) $video->loop_video;

	$video_attrs  = '';
	$video_attrs .= $autoplay ? ' autoplay' : '';
	$video_attrs .= $muted ? ' muted' : '';
	$video_attrs .= $loop ? ' loop' : '';

	$markers_array = vidchlog_get_markers_by_video( $idvideo );

	global $vidchlog_videos_data;

	if ( ! isset( $vidchlog_videos_data ) ) {
		$vidchlog_videos_data = array();
	}

	$escaped_markers = array();

	if ( is_array( $markers_array ) && ! empty( $markers_array ) ) {

		foreach ( $markers_array as $marker ) {

			$escaped_markers[] = array(
				'time' => intval( $marker['tiempo'] ),
				'text' => esc_js(
					sanitize_text_field(
						$marker['titulo']
					)
				),
			);
		}
	}

	$vidchlog_videos_data[] = array(
		'videoId' => $unique_dom_id,
		'logoUrl' => esc_url( $logo_url ),
		'markers' => $escaped_markers,
	);

	ob_start();
	?>

	<video
		class="video-js vjs-fluid <?php echo esc_attr( $video->estilo ); ?>"
		id="<?php echo esc_attr( $unique_dom_id ); ?>"
		poster="<?php echo esc_url( $poster_url ); ?>"
		controls<?php echo esc_attr( $video_attrs ); ?>
	>
		<source
			src="<?php echo esc_url( $video_url ); ?>"
			type="video/mp4"
		>
	</video>

	<?php

	return ob_get_clean();
}

add_shortcode(
	'videos_chapters_logos',
	'vidchlog_videos_chapters_logos_shortcode'
);





