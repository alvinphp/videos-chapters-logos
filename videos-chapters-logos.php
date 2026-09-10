<?php
/**
 * Plugin Name: Videos Chapters & Logos
 * Description: Plugin that displays an MP4 video with a logo and markers.
 * Version: 1.3
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


/*
 * Cargar archivos del plugin.
 */
require_once plugin_dir_path( __FILE__ ) . 'functions/functions.php';
require_once plugin_dir_path( __FILE__ ) . 'functions/enqueue.php';
require_once plugin_dir_path( __FILE__ ) . 'functions/database.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/panel.php';
require_once plugin_dir_path( __FILE__ ) . 'widget/class-vidchlog-video-widget.php';

/*
 * Crear las tablas al activar el plugin.
 */
register_activation_hook(
	__FILE__,
	'vidchlog_crear_tablas'
);


/*
 * Procesar eliminación de videos.
 */
add_action(
	'admin_post_vidchlog_delete_video',
	'vidchlog_procesar_delete_video'
);

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
	// variable contadora para el id unico.
	static $video_count = 0;
	++$video_count;

	/*
	 * Cargar los js del las marcaciones y logo.
	 */
	// =================================================================================
	wp_enqueue_script(
		'videoJs',
		plugins_url( 'assets/js/video.min.js', __FILE__ ),
		array(),
		'1.0',
		true
	);
	wp_enqueue_script(
		'videologo',
		plugins_url( 'assets/js/videojs-logo.min.js', __FILE__ ),
		array( 'videoJs' ),
		'1.0',
		true
	);
	wp_enqueue_script(
		'videomarker',
		plugins_url( 'assets/js/videojs-markers.js', __FILE__ ),
		array( 'jquery', 'videoJs' ),
		'1.0',
		true
	);
	wp_enqueue_script(
		'video-extends-init',
		plugins_url( 'assets/js/video-extends-init.js', __FILE__ ),
		array( 'videoJs', 'videologo', 'videomarker' ),
		'1.0',
		true
	);
	// ==============================================================================

	/*
	 * obteniendo id para el shortcode
	 */
	$atts = shortcode_atts(
		array(
			'id'     => '',
			'unique' => 'default',
		),
		$atts,
		'videos_chapters_logos'
	);

	/*
	 * Obtener el ID del video.
	 */
	$idvideo = absint( $atts['id'] );
	// id unico.
	$unique = sanitize_html_class( $atts['unique'] );

	if ( ! $idvideo ) {
		return '<p>' . esc_html__(
			'Video no válido.',
			'videos-chapters-logos'
		) . '</p>';
	}

	/*
	 * Obtener el video desde la base de datos.
	 */
	$video = vidchlog_get_video_by_id( $idvideo );

	if ( ! $video ) {
		return '<p>' . esc_html__(
			'Video no encontrado.',
			'videos-chapters-logos'
		) . '</p>';
	}

	/*
	 * Sanitizar nombres de archivos.
	 */
	$unique_dom_id = 'video_' . $idvideo . '_' . $video_count . '_' . uniqid();
	$video_file    = sanitize_file_name( $video->video );
	$logo_file     = sanitize_file_name( $video->logo );
	$poster_file   = sanitize_file_name( $video->poster );

	/*
	 * Ruta física del archivo de video.
	 */
	$video_path = plugin_dir_path( __FILE__ )
		. 'assets/video/'
		. $video_file;

	/*
	 * Verificar que el video exista.
	 */
	if ( ! file_exists( $video_path ) ) {
		return '<p>' . esc_html__(
			'Video not found.',
			'videos-chapters-logos'
		) . '</p>';
	}

	/*
	 * Crear URLs.
	 */
	$video_url = plugins_url(
		'assets/video/' . $video_file,
		__FILE__
	);

	$logo_url = plugins_url(
		'assets/img/' . $logo_file,
		__FILE__
	);

	$poster_url = plugins_url(
		'assets/img/' . $poster_file,
		__FILE__
	);

	/*
	 * Obtener configuración desde la base de datos.
	 */
	$autoplay = (bool) $video->autoplay;
	$muted    = (bool) $video->muted;
	$loop     = (bool) $video->loop_video;

	/*
	 * Crear atributos del elemento <video>.
	 */
	$video_attrs  = '';
	$video_attrs .= $autoplay ? ' autoplay' : '';
	$video_attrs .= $muted ? ' muted' : '';
	$video_attrs .= $loop ? ' loop' : '';

	/*
	 * Obtener las marcaciones del video.
	 */
	$markers_array = vidchlog_get_markers_by_video( $idvideo );

	/*
	 * Preparar las marcaciones para JavaScript.
	 */
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

	/*
	 * Guardar los datos del video
	 * para enviarlos a JavaScript.
	 */
	$vidchlog_videos_data[] = array(
		'videoId' => $unique_dom_id,
		'logoUrl' => esc_url( $logo_url ),
		'markers' => $escaped_markers,
	);

	/*
	 * Generar HTML del reproductor.
	 */
	ob_start();
	?>

	<video
		class="video-js vjs-fluid"
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


/*
 * Registrar el shortcode.
 */
add_shortcode(
	'videos_chapters_logos',
	'vidchlog_videos_chapters_logos_shortcode'
);


/*
 * Pasar los datos de PHP a JavaScript.
 */
add_action(
	'wp_footer',
	function () {

		global $vidchlog_videos_data;

		if ( ! empty( $vidchlog_videos_data ) ) {

			wp_localize_script(
				'video-extends-init',
				'vidchlog_videoExtendsData',
				$vidchlog_videos_data
			);
		}
	}
);