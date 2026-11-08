<?php
/**
 * Eliminar Videos Chapters & Logos.
 *
 * @package Videos_Chapters_Logos
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

$tablas = array(
	$wpdb->prefix . 'marcaciones',
	$wpdb->prefix . 'video',
	$wpdb->prefix . 'estilos',
);

foreach ( $tablas as $tabla ) {
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.NoCaching -- Required to remove plugin tables during uninstall.
	$wpdb->query(
		$wpdb->prepare(
			'DROP TABLE IF EXISTS %i',
			$tabla
		)
	);
}
