<?php
/**
 * Encolar JavaScript para el área de administración.
 *
 * @package Videos_Chapters_Logos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Carga los scripts del administrador.
 *
 * @param string $hook Hook de la página actual.
 */
function vidchlog_cargar_admin_js( $hook ) {

	$paginas_validas = array(
		'toplevel_page_videos-chapters-logos',
		'videos-chapters-logos_page_admin_videos',
		'admin_page_admin_videos',
	);

	if ( ! in_array( $hook, $paginas_validas, true ) ) {
		return;
	}

	$url_base = plugin_dir_url( __FILE__ ) . '../assets/js/';
	$version  = '1.1.0';

	// JavaScript principal del administrador.
	wp_enqueue_script(
		'vidchlog-admin-script',
		$url_base . 'admin.js',
		array( 'jquery' ),
		$version,
		true
	);

	// Alternar campos del formulario.
	wp_enqueue_script(
		'vidchlog-admin-toggle',
		$url_base . 'checkbox-toggle.js',
		array(),
		$version,
		true
	);
	// AJAX para eliminar videos.
	wp_enqueue_script(
		'vidchlog-eliminar-video',
		$url_base . 'eliminar-video.js',
		array( 'jquery', 'datatables-script' ),
		$version,
		true
	);

	wp_localize_script(
		'vidchlog-eliminar-video',
		'vidchlogAjax',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'vidchlog_eliminar_video' ),
		)
	);
}

add_action( 'admin_enqueue_scripts', 'vidchlog_cargar_admin_js' );
