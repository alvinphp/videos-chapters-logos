<?php
/*
Plugin Name: Videos Chapters & Logos
Description: Plugin that displays an MP4 video with a logo and markers.
Version: 1.0
Author: alvingil
Text Domain: videos-chapters-logos
Domain Path: /languages
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// MP4 Chapters & Logos
// mp4-chapters-logos

// Seguridad
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

/***************************************************************************
                              MENU Y SUBMENU
****************************************************************************/
function video_extends_admin_menu() {
    // Agregar el menú principal
    add_menu_page(
        __( 'Videos Chapters & Logos', 'videos-chapters-logos' ),         
        __( 'Videos Chapters & Logos', 'videos-chapters-logos' ),          
        'manage_options',                               
        'video-extends',                                
        'welcome',              
        'dashicons-media-video',                        
        1                                        
    );

    // Agregar un submenú para la documentación
    add_submenu_page(
        'video-extends',                                 
        __( 'Video Extends Documentation', 'videos-chapters-logos' ),  
        __( 'Documentation', 'videos-chapters-logos' ),         
        'manage_options',                                
        'videos-chapters-logos-documentation',                   
        'redirect_documentation'        
    );
}

function redirect_documentation() {
    
    include(plugin_dir_path(__FILE__) . 'includes/documentation.php');
}

function welcome() {
    echo "**************************************************************";
    echo "<h1>" . esc_html__( 'Enjoy Videos Chapters & Logos!', 'videos-chapters-logos' ) . "</h1>";
    echo "**************************************************************";
    echo "<br>";
    echo "<h1>";
    echo "😊😄😄😄😄😄😄😄😄😄😄😄😄😄😄😄😄😄😄😄😄😄😄";
    echo "</h1>";
}

add_action( 'admin_menu', 'video_extends_admin_menu' );

/****************************************************************************
 * **************************************************************************
****************************************************************************/
// Función para cargar los estilos CSS del plugin
function video_extends_enqueue_styles() {
    // Encolar el archivo CSS para que se cargue en el frontend
    wp_enqueue_style( 'video-extends-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
}

// Enganchamos la función a wp_enqueue_scripts para el frontend
add_action( 'wp_enqueue_scripts', 'video_extends_enqueue_styles' );
add_action( 'admin_enqueue_scripts', 'video_extends_enqueue_styles');

// funciones
require_once plugin_dir_path(__FILE__) . 'functions/functions.php';
    // quitar espacio del shortcode general 
   
    add_filter('the_content', function($content) {
    // Busca todos los shortcodes [video_extends ...] y los limpia
    return preg_replace_callback('/\[videos_chapters_logos.*?\]/s', function($matches) {
        return ve_clean_shortcode_full($matches[0]);
    }, $content);
});
    add_filter('the_content', function($content) {
    $content = preg_replace('/<p>\s*(\[videos_chapters_logos[^\]]*\])\s*<\/p>/i', '$1', $content);
    return $content;
});
    // filtros para llamar la funcion de eliminar espacio
    add_filter('the_content', 've_force_video_inline', 5);

// Shortcode con parámetros [mp4_chapters_logos="Sintel.mp4" width="800" height="450" autoplay="true" loop="true"]
function videos_chapters_logos_shortcode($atts) {

     // id dinamicos
    static $video_count = 0; // Contador estático
    $video_count++; // Incrementa cada vez que se llama el shortcode

    // cargar dentro para no sobrecargar de script
    wp_enqueue_script(
        'videoextendJs',
        plugins_url('assets/js/jquery.video-extend.js', __FILE__),
        array('jquery'),
        '1.0',
        true
    );
   // Parámetros del shortcode con valores por defecto
    $atts = shortcode_atts(array(
        'video'  => 'Sintel.mp4',         // Nombre del video o URL completa
        'logo'   => 'example_logo.png',   // Logo sobre el video
        'poster'   => 'poster.png',   // Logo sobre el video
        'width'  => '640', 
        'height' => '360',
        'autoplay' => 'false',
        'loop' => 'false',
        'markers' => '' ,
        'id'     => ''
    ), $atts);
      
    // funcion para limpiar espacios
    $marcadores = parse_markers_string( $atts['markers'] );

    // Sanitizar atributos
    $atts['video']  = sanitize_file_name( $atts['video'] );
    $atts['logo']   = sanitize_file_name( $atts['logo'] );
    $atts['poster'] = sanitize_file_name( $atts['poster'] );
    $atts['width']  = intval( $atts['width'] );
    $atts['height'] = intval( $atts['height'] );
    $atts['autoplay'] = filter_var($atts['autoplay'], FILTER_VALIDATE_BOOLEAN);
    $atts['loop']     = filter_var($atts['loop'], FILTER_VALIDATE_BOOLEAN);
    
    // validar si exsite video
    if (!file_exists(plugin_dir_path(__FILE__) . 'assets/video/' . $atts['video'])) {
       return '<p>' . __('Video not found.', 'videos-chapters-logos') . '</p>';

    }

    // Si no hay ID definido, creamos uno dinámico
    
    if (empty($atts['id'])) {
         $atts['id'] = 'video_' . $video_count . '_' . uniqid();
    }

    // Construir URLs de los archivos dentro del plugin
    $video_url = plugins_url('assets/video/' . $atts['video'], __FILE__);
    $logo_url  = plugins_url('assets/img/' . $atts['logo'], __FILE__);
    $poster_url = plugins_url('assets/img/' . $atts['poster'], __FILE__);
    // validando controles
    $autoplay = filter_var($atts['autoplay'], FILTER_VALIDATE_BOOLEAN); // convierte 'true'/'false' a booleano
    $loop     = filter_var($atts['loop'], FILTER_VALIDATE_BOOLEAN);
    $autoplay = isset($atts['autoplay']) && $atts['autoplay'] === true;
    $loop     = isset($atts['loop']) && $atts['loop'] === true;
    $muted    = $autoplay;

    $video_attrs = '';
    $video_attrs .= $autoplay ? ' autoplay' : '';
    $video_attrs .= $muted   ? ' muted'    : '';
    $video_attrs .= $loop    ? ' loop'     : '';

    // pansado parametros dinamicos al js
    wp_enqueue_script('video-extends-init', plugins_url('assets/js/video-extends-init.js', __FILE__), array('jquery', 'videoextendJs'), '1.0', true);

    // marcadores a array y filtrar espacios en markers

    $markers_array = array();

    if (!empty($atts['markers'])) {
    $markers_list = explode(',', $atts['markers']); // separar por comas
    foreach ($markers_list as $marker) {
        if (strpos($marker, '=') !== false) {
            list($time, $text) = explode('=', $marker, 2);
            $markers_array[] = array(
                'time' => ve_convert_to_seconds(trim($time)),
                'text' => trim($text)
             );
          }
       }
    }

    // usamos market por defectos
    if (empty($markers_array)) {
    $markers_array = array(
        array('time' => ve_convert_to_seconds('0:39'), 'text' => __('Chapter 1', 'videos-chapters-logos')),
        array('time' => ve_convert_to_seconds('5:50'), 'text' => __('Chapter 2', 'videos-chapters-logos')),
        array('time' => ve_convert_to_seconds('7:50'), 'text' => __('Chapter 3', 'videos-chapters-logos')),
        array('time' => ve_convert_to_seconds('11:17'), 'text' => __('Chapter 4', 'videos-chapters-logos')),
    );
}


    global $videos_data;
    $videos_data [] = array(
        'videoId' => $atts['id'],
        'logoUrl' => $logo_url,
        'markers' => $markers_array
    );
   
    // Insertar HTML
    ob_start(); ?>
     <div class="container">
        <video class="video-custom" id="<?php echo esc_attr($atts['id']);  ?>" width="<?php echo esc_attr($atts['width']);?>" height="<?php echo esc_attr($atts['height']); ?>" poster="<?php echo esc_url( $poster_url); ?>" controls  <?php echo esc_attr( $video_attrs); ?> >
            <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
        </video>
    </div>
   <?php
  return ob_get_clean();
}
add_shortcode('videos_chapters_logos', 'videos_chapters_logos_shortcode');

// Pasar datos al JS una sola vez en footer
add_action('wp_footer', function() {
    global $videos_data;
    if (!empty($videos_data)) {
         wp_localize_script('video-extends-init', 'videoExtendsData', $videos_data);
    }
});


