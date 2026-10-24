<?php
/**
 * Assets del shortcode [videos_chapters_logos].
 *
 * @package videos-chapters-logos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Encolar los scripts del plugin.
 *
 * @return void
 */
function vidchlog_enqueue_shortcode() {

	// Subimos un nivel desde /include/ para llegar a la raíz del plugin.
	$plugin_url = plugin_dir_url( dirname( __FILE__ ) );

	wp_enqueue_script(
		'videoJs',
		$plugin_url . 'assets/js/video.min.js',
		array(),
		'1.0',
		true
	);

	wp_enqueue_script(
		'videologo',
		$plugin_url . 'assets/js/videojs-logo.min.js',
		array( 'videoJs' ),
		'1.0',
		true
	);

	wp_enqueue_script(
		'videomarker',
		$plugin_url . 'assets/js/videojs-markers.js',
		array( 'jquery', 'videoJs' ),
		'1.0',
		true
	);

	wp_enqueue_script(
		'video-extends-init',
		$plugin_url . 'assets/js/video-extends-init.js',
		array( 'videoJs', 'videologo', 'videomarker' ),
		'1.0',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'vidchlog_enqueue_shortcode' );