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
/**
 * Funcion que elimina espacios extras en las marcaciones de los tiempos.
 * Convierte los saltos de lineas en coma y elimina los espacios extras.
 *
 * @param string $marker_string La cadena de texto con los marcadores de tiempo y nombre.
 * @return array Un array asociativo con tiempos como claves y nombres como valores.
 */
function vidchlog_parse_markers_string( $marker_string ) {
	if ( ! is_string( $marker_string ) || trim( $marker_string ) === '' ) {
		return array();
	}
	$clean   = str_replace( array( "\r\n", "\r" ), "\n", $marker_string );
	$clean   = preg_replace( '/\s*\n\s*/', ',', $clean );
	$clean   = preg_replace( '/,\s+/', ',', $clean );
	$markers = array();
	foreach ( explode( ',', $clean ) as $pair ) {
		if ( strpos( $pair, '=' ) !== false ) {
			list( $tiempo, $nombre )    = explode( '=', $pair, 2 );
			$markers[ trim( $tiempo ) ] = trim( $nombre );
		}
	}

	return $markers;
}

/**
 * Limpia tabulaciones saltos de linea y <br> de un string.
 * Funcion para normalizar el texto dejandolo mas limpio y consistente.
 *
 * @param string $shortcode_text El texto a limpiar.
 * @return string El texto limpio y normalizado.
 */
function vidchlog_clean_shortcode_full( $shortcode_text ) {
	$clean = preg_replace( '/[\r\n\t]+/', ' ', $shortcode_text );
	$clean = preg_replace( '/\s{2,}/', ' ', $clean );
	$clean = preg_replace( '/<br\s*\/?>/i', '', $clean );
	$clean = trim( $clean );
	return $clean;
}

/**
 * Forzar que el primer atributo del video quede en la primera linea
 * Funcion que permite siempre que el atributo video este bien formateado de primero.
 *
 * @param string $content El contenido en el que se encuentran los shortcodes a procesar.
 * @return string El contenido con el atributo "video" en primera posición.
 */
function vidchlog_force_video_inline( $content ) {
	return preg_replace_callback(
		'/\[videos_chapters_logos\s+([^\]]*?)\]/s',
		function ( $matches ) {
			$attrs = $matches[1];
			if ( preg_match( '/video\s*=\s*["\']([^"\']+)["\']/', $attrs, $video_match ) ) {
				$video_attr = 'video="' . $video_match[1] . '"';
				$rest       = preg_replace( '/video\s*=\s*["\'][^"\']+["\']/', '', $attrs );
				$rest       = preg_replace( '/\s+/', ' ', trim( $rest ) );
				return '[videos_chapters_logos ' . $video_attr . ' ' . $rest . ']';
			}

			return $matches[0];
		},
		$content
	);
}
