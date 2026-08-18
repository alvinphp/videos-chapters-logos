<?php
/**
 * Encola los estilos del plugin.
 */
function vidchlog_enqueue_assets() {

	$plugin_url = plugin_dir_url( dirname( __FILE__ ) );

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

add_action( 'wp_enqueue_scripts', 'vidchlog_enqueue_assets' );
