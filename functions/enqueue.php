<?php
/**
 * Encolar estilos y scripts del plugin.
 *
 * @package videos-chapters-logos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Encolar estilos necesarios en el frontend para el reproductor.
 */
function vidchlog_frontend_assets() {
	// Solo cargamos los estilos si hay una entrada o página que pueda tener el shortcode.
	if ( is_singular() ) {
		$plugin_url = plugin_dir_url( __DIR__ );

		wp_enqueue_style(
			'video-js-css',
			$plugin_url . 'assets/css/video-js.css',
			array(),
			'1.0'
		);

		wp_enqueue_style(
			'video-logo-css',
			$plugin_url . 'assets/css/videojs-logo.css',
			array( 'video-js-css' ),
			'1.0'
		);

		wp_enqueue_style(
			'video-markers-css',
			$plugin_url . 'assets/css/videojs.markers.css',
			array( 'video-js-css' ),
			'1.0'
		);
	}
}
add_action( 'wp_enqueue_scripts', 'vidchlog_frontend_assets' );
