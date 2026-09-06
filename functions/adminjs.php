<?php
/**
 * Encolar JavaScript para el área de administración.
 *
 * @package Videos_Chapters_Logos
 */

/**
 * Js para el area de administrador.
 *
 * @param string $hook Hook de la página actual.
 */
function vidchlog_cargar_admin_js( $hook ) {
	if ( 'toplevel_page_videos-chapters-logos' !== $hook ) {
		return;
	}


	wp_enqueue_script(
		'vidchlog-admin-script',
		plugin_dir_url( __FILE__ ) . '../assets/js/admin.js',
		array( 'jquery' ),
		'1.0',
		time(),
		true
	);
}

add_action( 'admin_enqueue_scripts', 'vidchlog_cargar_admin_js' );
