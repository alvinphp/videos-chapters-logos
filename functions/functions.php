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
 * Funcion para convertir de segundos a minutos.
 *
 * @param int|string $time tiempo en minutos.
 * @return mixed
 */
function vidchlog_convert_to_minute_seconds( $time ) {
	$horas             = floor( $time / 60 );
	$minutos_restantes = $time % 60;
	$formato           = sprintf( '%d:%02d', $horas, $minutos_restantes );
	return $formato;
}

/**
 * Procesa la subida o gestión de un video en el sistema.
 *
 * @param array $archivo Datos del archivo recibido (ej. $_FILES['video']).
 * @return int|false ID del registro.
 */
function vidchlog_subir_video( $archivo ) {
	if ( empty( $archivo['name'] ) ) {
		return false;
	}

	$nombre = sanitize_file_name( $archivo['name'] );

	$directorio = plugin_dir_path( __DIR__ ) . 'assets/video/';

	if ( ! file_exists( $directorio ) ) {
		wp_mkdir_p( $directorio );
	}

	$destino = $directorio . $nombre;

	if ( move_uploaded_file( $archivo['tmp_name'], $destino ) ) {
		return $nombre;
	}
		return false;
}

/**
 * Procesa la subida del logo a la carpeta img del plugin.
 *
 * @param array $archivo Datos del archivo recibido (ej. $_FILES['logo']).
 * @return int|false ID del registro.
 */
function vidchlog_subir_logo( $archivo ) {
	if ( empty( $archivo['name'] ) ) {
		return false;
	}

	$nombre = sanitize_file_name( $archivo['name'] );

	$directorio = plugin_dir_path( __DIR__ ) . 'assets/img/';

	if ( ! file_exists( $directorio ) ) {
		wp_mkdir_p( $directorio );
	}

	$destino = $directorio . $nombre;

	if ( move_uploaded_file( $archivo['tmp_name'], $destino ) ) {
		return $nombre;
	}
		return false;
}
/**
 * Procesa la subida del poster a la carpeta img del plugin.
 *
 * @param array $archivo Datos del archivo recibido (ej. $_FILES['poster']).
 * @return int|false ID del registro.
 */
function vidchlog_subir_poster( $archivo ) {
	if ( empty( $archivo['name'] ) ) {
		return false;
	}

	$nombre = sanitize_file_name( $archivo['name'] );

	$directorio = plugin_dir_path( __DIR__ ) . 'assets/img/';

	if ( ! file_exists( $directorio ) ) {
		wp_mkdir_p( $directorio );
	}

	$destino = $directorio . $nombre;

	if ( move_uploaded_file( $archivo['tmp_name'], $destino ) ) {
		return $nombre;
	}
		return false;
}
