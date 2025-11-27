<?php
// Seguridad
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}
// funcion para convertir minutos a segundos 
function ve_convert_to_seconds($time) {
    if (strpos($time, ':') !== false) {
        list($minutes, $seconds) = explode(':', $time);
        return intval($minutes) * 60 + intval($seconds);
    }
    return intval($time);
}
// funcion para limpiar espacios en las marcaciones 
function parse_markers_string( $marker_string ) {
    if ( ! is_string( $marker_string ) || trim( $marker_string ) === '' ) {
        return array();
    }

    // Normalizar saltos de línea y eliminar retornos de carro
    $clean = str_replace( array( "\r\n", "\r" ), "\n", $marker_string );

    // Eliminar saltos de línea extra y espacios innecesarios
    $clean = preg_replace( '/\s*\n\s*/', ',', $clean ); 
    $clean = preg_replace( '/,\s+/', ',', $clean );   

    // Convertir en pares tiempo=nombre
    $markers = array();
    foreach ( explode( ',', $clean ) as $pair ) {
        if ( strpos( $pair, '=' ) !== false ) {
            list( $tiempo, $nombre ) = explode( '=', $pair, 2 );
            $markers[ trim( $tiempo ) ] = trim( $nombre );
        }
    }

    return $markers;
}

// Limpia saltos de línea, tabulaciones y <br> de un string

function ve_clean_shortcode_full($shortcode_text) {
    $clean = preg_replace('/[\r\n\t]+/', ' ', $shortcode_text);
    $clean = preg_replace('/\s{2,}/', ' ', $clean);
    $clean = preg_replace('/<br\s*\/?>/i', '', $clean);
    $clean = trim($clean);
    return $clean;
}

// Forzar que el primer atributo video quede en la primera línea
// eliminar espacio
function ve_force_video_inline($content) {
    return preg_replace_callback('/\[videos_chapters_logos\s+([^\]]*?)\]/s', function($matches) {
        $attrs = $matches[1];

        // Extraer el atributo video
        if (preg_match('/video\s*=\s*["\']([^"\']+)["\']/', $attrs, $video_match)) {
            $video_attr = 'video="' . $video_match[1] . '"';

            // Quitar el atributo video del resto
            $rest = preg_replace('/video\s*=\s*["\'][^"\']+["\']/', '', $attrs);

            // Limpiar saltos de línea y espacios extra del resto
            $rest = preg_replace('/\s+/', ' ', trim($rest));

            // Reconstruir shortcode
            return '[videos_chapters_logos ' . $video_attr . ' ' . $rest . ']';
        }

        return $matches[0];
    }, $content);
}

// Aplicar antes de procesar shortcodes
// add_filter('the_content', 've_force_video_inline', 5);
