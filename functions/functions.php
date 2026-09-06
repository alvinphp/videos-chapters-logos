<?php
/**
 * Este archivo tiene funciones para formatear el shortcode.
 * como manejar los tiempos, liberar espacios y saltos de lineas.
 *
 * @package videos-chapters-logos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Convierte cadenas de texto de minutos a segundos
 * esta funcion configura el temporizador
 *
 * @param {string} $time - Duración para configurar el temporizador (por ejemplo, "10s" o "1m").
 * @return {boolean} - Devuelve `true` si el temporizador se configuró correctamente.
 */
function vidchlog_convert_to_seconds( $time ) {
	if ( strpos( $time, ':' ) !== false ) {
		list($minutes, $seconds) = explode( ':', $time );
		return intval( $minutes ) * 60 + intval( $seconds );
	}
	return intval( $time );
}
