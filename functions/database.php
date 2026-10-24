<?php
/**
 * Funciones para crear y gestionar las tablas del plugin.
 *
 * @package Videos_Chapters_Logos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;

/*
 * Tablas del plugin.
 */
define(
	'VIDCHLOG_TABLA_VIDEOS',
	$wpdb->prefix . 'video'
);

define(
	'VIDCHLOG_TABLA_MARCACIONES',
	$wpdb->prefix . 'marcaciones'
);

define(
	'VIDCHLOG_TABLA_STYLE',
	$wpdb->prefix . 'estilos'
);



/**
 * Crear las tablas del plugin.
 */
function vidchlog_crear_tablas() {

	global $wpdb;

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	$charset_collate   = $wpdb->get_charset_collate();
	$tabla_estilos     = VIDCHLOG_TABLA_STYLE;
	$tabla_videos      = VIDCHLOG_TABLA_VIDEOS;
	$tabla_marcaciones = VIDCHLOG_TABLA_MARCACIONES;

	/*
	 * Tabla de estilos.
	 */
	$sql_estilos = "CREATE TABLE $tabla_estilos (
	    id_estilo BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	    estilo VARCHAR(255) NULL,
	    PRIMARY KEY( id_estilo)
	) ENGINE=InnoDB $charset_collate;";

	dbDelta( $sql_estilos );
	// insertando estilos predeterminado.
	$estilos_predeterminados = array(
		'default',
		'vjs-theme-city',
		'vjs-theme-fantasy',
		'vjs-theme-forest',
		'vjs-theme-sea',
	);
	// recorriendo el array.
	foreach ( $estilos_predeterminados as $estilo ) {
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$exist = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT id_estilo
		     FROM %i
		     WHERE estilo = %s',
				$tabla_estilos,
				$estilo
			)
		);

		if ( ! $exist ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->insert(
				$tabla_estilos,
				array(
					'estilo' => $estilo,
				),
				array(
					'%s',
				)
			);
		}
	}

	/*
	 * Tabla de videos.
	 */
	$sql_videos = "CREATE TABLE $tabla_videos (
        idvideo BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        video VARCHAR(255) NOT NULL,
        logo VARCHAR(255) NOT NULL,
        poster VARCHAR(255) NOT NULL,
        autoplay TINYINT(1) NOT NULL DEFAULT 0,
        muted TINYINT(1) NOT NULL DEFAULT 0,
        loop_video TINYINT(1) NOT NULL DEFAULT 0,
        id_estilo BIGINT UNSIGNED NULL,
        PRIMARY KEY  (idvideo),
        KEY idx_id_estilo (id_estilo),
        CONSTRAINT fk_videos_estilo
        FOREIGN KEY (id_estilo)
        REFERENCES $tabla_estilos(id_estilo)
        ON DELETE SET NULL 
        ON UPDATE CASCADE
    ) ENGINE=InnoDB $charset_collate;";

	dbDelta( $sql_videos );

	/*
	 * Tabla de marcaciones.
	 */
	$sql_marcaciones = "CREATE TABLE $tabla_marcaciones (
        idmarkers BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        idvideo BIGINT UNSIGNED NOT NULL,
        tiempo INT UNSIGNED NOT NULL,
        titulo VARCHAR(255) NOT NULL,
        PRIMARY KEY  (idmarkers),
        KEY idx_idvideo (idvideo),
        CONSTRAINT fk_marcaciones_video
            FOREIGN KEY (idvideo)
            REFERENCES $tabla_videos(idvideo)
            ON DELETE CASCADE
            ON UPDATE CASCADE
    ) ENGINE=InnoDB $charset_collate;";

	dbDelta( $sql_marcaciones );
}

/**
 * Verificar si table existe si no la crea.
 *
 * @return array
 */
function vidchlog_table_exist(){
    global $wpdb;
    $tabla_videos = VIDCHLOG_TABLA_VIDEOS;
    $existe = $wpdb->get_var( "SHOW TABLES LIKE '$tabla_videos'" );

	if ( ! $existe ) {
		vidchlog_crear_tablas();
	}
}


/**
 * Seleccionar videos con sus marcaciones.
 *
 * @return array
 */
function vidchlog_seleccionar_videos() {

	global $wpdb;
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	$resultados = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT
                v.idvideo,
                v.video,
                v.logo,
                v.poster,
                v.autoplay,
                v.muted,
                v.loop_video,
                m.idmarkers,
                m.tiempo,
                m.titulo
            FROM %i AS v
            INNER JOIN %i AS m
                ON v.idvideo = m.idvideo
            ORDER BY v.idvideo ASC, m.tiempo ASC',
			VIDCHLOG_TABLA_VIDEOS,
			VIDCHLOG_TABLA_MARCACIONES
		)
	);

	return $resultados;
}


/**
 * Obtener todos los videos.
 *
 * @return array
 */
function vidchlog_get_video() {

	global $wpdb;
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	$results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT
				v.idvideo,
				v.video,
				v.logo,
				v.poster,
				v.autoplay,
				v.muted,
				v.loop_video,
				v.id_estilo,
				e.estilo
			FROM %i AS v
			LEFT JOIN %i AS e
				ON v.id_estilo = e.id_estilo
			ORDER BY v.idvideo ASC',
			VIDCHLOG_TABLA_VIDEOS,
			VIDCHLOG_TABLA_STYLE
		)
	);

	return $results;
}


/**
 * Obtener un video por su ID.
 *
 * @param int $idvideo ID del video.
 * @return object|null
 */
function vidchlog_get_video_by_id( $idvideo ) {

	global $wpdb;
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	$resultado = $wpdb->get_row(
		$wpdb->prepare(
			'SELECT
				v.idvideo,
				v.video,
				v.logo,
				v.poster,
				v.autoplay,
				v.muted,
				v.loop_video,
				v.id_estilo,
				e.estilo
			FROM %i AS v
			LEFT JOIN %i AS e
				ON v.id_estilo = e.id_estilo
			WHERE v.idvideo = %d',
			VIDCHLOG_TABLA_VIDEOS,
			VIDCHLOG_TABLA_STYLE,
			$idvideo
		)
	);

	return $resultado;
}


/**
 * Obtener las marcaciones de un video.
 *
 * @param int $idvideo ID del video.
 * @return array
 */
function vidchlog_get_markers_by_video( $idvideo ) {

	global $wpdb;
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	return $wpdb->get_results(
		$wpdb->prepare(
			'SELECT
                idmarkers,
                idvideo,
                tiempo,
                titulo
            FROM %i
            WHERE idvideo = %d
            ORDER BY tiempo ASC',
			VIDCHLOG_TABLA_MARCACIONES,
			$idvideo
		),
		ARRAY_A
	);
}


/**
 * Eliminar un video.
 *
 * Las marcaciones relacionadas se eliminan
 * automáticamente mediante ON DELETE CASCADE.
 *
 * @param int $idvideo ID del video.
 * @return int|false
 */
function vidchlog_delete_video( $idvideo ) {

	global $wpdb;
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	return $wpdb->delete(
		VIDCHLOG_TABLA_VIDEOS,
		array(
			'idvideo' => $idvideo,
		),
		array( '%d' )
	);
}


/**
 * Procesar eliminación del video.
 */
function vidchlog_procesar_delete_video() {

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die(
			'No tienes permisos para eliminar videos.'
		);
	}

	$idvideo = isset( $_GET['id'] )
		? absint( $_GET['id'] )
		: 0;

	if ( ! $idvideo ) {
		wp_die(
			'ID de video no válido.'
		);
	}

	check_admin_referer(
		'vidchlog_delete_video_' . $idvideo
	);

	vidchlog_delete_video( $idvideo );

	wp_safe_redirect(
		admin_url( 'admin.php?page=admin_videos' )
	);

	exit;
}

/**
 * Obtener los estilos.
 *
 * @return array
 */

/**
 * Procesar la eliminación de un video mediante AJAX.
 */
function vidchlog_eliminar_video_ajax() {

	// Verificar permisos.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error(
			array(
				'message' => 'No tienes permisos para eliminar videos.',
			),
			403
		);
	}

	// Verificar el nonce.
	check_ajax_referer(
		'vidchlog_eliminar_video',
		'nonce'
	);

	// Obtener el ID del video.
	$idvideo = isset( $_POST['idvideo'] )
		? absint( $_POST['idvideo'] )
		: 0;

	if ( ! $idvideo ) {
		wp_send_json_error(
			array(
				'message' => 'El ID del video no es válido.',
			),
			400
		);
	}

	// Comprobar que el video existe.
	$video = vidchlog_get_video_by_id( $idvideo );

	if ( ! $video ) {
		wp_send_json_error(
			array(
				'message' => 'El video no existe.',
			),
			404
		);
	}

	// Eliminar el video usando la función existente.
	$eliminado = vidchlog_delete_video( $idvideo );

	if ( false === $eliminado || 0 === $eliminado ) {
		wp_send_json_error(
			array(
				'message' => 'No se pudo eliminar el video.',
			),
			500
		);
	}

	wp_send_json_success(
		array(
			'message' => 'Video eliminado correctamente.',
			'idvideo' => $idvideo,
		)
	);
}

add_action(
	'wp_ajax_vidchlog_eliminar_video',
	'vidchlog_eliminar_video_ajax'
);

/**
 * Función para obtener los estilos.
 *
 * @return array Lista de estilos de la base de datos.
 */
function vidchlog_get_style() {

	global $wpdb;
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	$estilos = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT id_estilo, estilo
			FROM %i
			ORDER BY id_estilo ASC',
			VIDCHLOG_TABLA_STYLE
		)
	);

	return $estilos;
}

/**
 * Actualiza la información del video en la base de datos.
 *
 * @param int    $idvideo  ID del video a actualizar.
 * @param string $video    Ruta o URL del archivo de video.
 * @param string $logo     Ruta o URL del archivo de logo.
 * @param string $poster   Ruta o URL de la imagen de póster.
 * @param int    $autoplay Estado de reproducción automática (0 o 1).
 * @param int    $muted    Estado de silencio (0 o 1).
 * @param int    $loop     Estado de reproducción en bucle (0 o 1).
 * @param int    $estilo   ID del estilo seleccionado.
 * @return int|false.
 */
function vidchlog_update_video_db(
	$idvideo,
	$video,
	$logo,
	$poster,
	$autoplay,
	$muted,
	$loop,
	$estilo
) {

	global $wpdb;
    // @codingStandardsIgnoreLine
	return $wpdb->update(
		VIDCHLOG_TABLA_VIDEOS,
		array(
			'video'      => $video,
			'logo'       => $logo,
			'poster'     => $poster,
			'autoplay'   => $autoplay,
			'muted'      => $muted,
			'loop_video' => $loop,
			'id_estilo'  => $estilo,

		),
		array(
			'idvideo' => $idvideo,
		),
		array(
			'%s',
			'%s',
			'%s',
			'%d',
			'%d',
			'%d',
			'%d',
		),
		array(
			'%d',
		)
	);
}

/**
 * Inserta una nueva marcación (capítulo) para un video.
 *
 * @param int    $idvideo ID del video asociado.
 * @param int    $tiempo  Tiempo en segundos de la marcación.
 * @param string $titulo  Título o descripción de la marcación.
 * @return int|false Número de filas insertadas, o false en caso de error.
 */
function vidchlog_insert_marker( $idvideo, $tiempo, $titulo ) {
	global $wpdb;
    // @codingStandardsIgnoreLine
	return $wpdb->insert(
		VIDCHLOG_TABLA_MARCACIONES,
		array(
			'idvideo' => $idvideo,
			'tiempo'  => $tiempo,
			'titulo'  => $titulo,
		),
		array(
			'%d',
			'%d',
			'%s',
		)
	);
}

/**
 * Metodo para eliminar markers.
 *
 * @param int $idmarkers ID de la marcación o capítulo a actualizar/eliminar.
 * @return void|bool
 */
function vidchlog_delete_marker_by_id( $idmarkers ) {
	global $wpdb;
    // @codingStandardsIgnoreLine
	return $wpdb->delete(
		VIDCHLOG_TABLA_MARCACIONES,
		array(
			'idmarkers' => $idmarkers,
		),
		array(
			'%d',
		)
	);
}

/**
 * Eliminar markers.
 *
 * @param int $idvideo ID del video.
 * @return int|false
 */
function vidchlog_delete_markers_by_video( $idvideo ) {
	global $wpdb;
    // @codingStandardsIgnoreLine
	return $wpdb->delete(
		VIDCHLOG_TABLA_MARCACIONES,
		array(
			'idvideo' => $idvideo,
		),
		array(
			'%d',
		)
	);
}

/**
 * Función para procesar las actualizaciones.
 *
 * @return void.
 */
function vidchlog_procesar_update_video() {
	// verificamos los permisos.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die(
			'No tienes permisos para actualizar videos.'
		);
	}

	$idvideo = isset( $_POST['idvideo'] )
		? absint( $_POST['idvideo'] )
		: 0;

	if ( ! $idvideo ) {
		wp_die(
			'ID de video no válido.'
		);
	}

	check_admin_referer(
		'vidchlog_update_video',
		'vidchlog_update_nonce'
	);

	/*
	 * Obtener el video actual.
	 */
	$video_actual = vidchlog_get_video_by_id( $idvideo );

	if ( ! $video_actual ) {
		wp_die(
			'El video no existe.'
		);
	}

	/*
	 * Obtener configuración del video que viene del form.
	 */
	$autoplay = isset( $_POST['vidchlog_autoplay'] ) ? 1 : 0;
	$muted    = isset( $_POST['vidchlog_muted'] ) ? 1 : 0;
	$loop     = isset( $_POST['vidchlog_loop'] ) ? 1 : 0;

	$estilo = isset( $_POST['estilo'] )
		? absint( $_POST['estilo'] )
		: 0;

	/*
	 * Mantener el video actual si no se sube uno nuevo.
	 */
	$video = $video_actual->video;

	if (
		isset( $_FILES['archivo_video'] )
		&& ! empty( $_FILES['archivo_video']['name'] )
	) {
	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Se valida la estructura del archivo antes de subirlo.
		$video_nuevo = vidchlog_subir_video( $_FILES['archivo_video'] );

		if ( false === $video_nuevo ) {
			wp_die( 'No se pudo subir el nuevo video.' );
		}

		$video = $video_nuevo;
	}

	/*
	 * Mantener el logo actual si no se sube uno nuevo.
	 */
	$logo = $video_actual->logo;

	if (
		isset( $_FILES['archivo_logo'] )
		&& ! empty( $_FILES['archivo_logo']['name'] )
	) {
	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Se valida la estructura del archivo antes de subirlo.
		$logo_nuevo = vidchlog_subir_logo( $_FILES['archivo_logo'] );

		if ( false === $logo_nuevo ) {
			wp_die( 'No se pudo subir el nuevo logo.' );
		}

		$logo = $logo_nuevo;
	}

	/*
	 * Mantener el poster actual si no se sube uno nuevo.
	 */
	$poster = $video_actual->poster;

	if (
		isset( $_FILES['archivo_poster'] )
		&& ! empty( $_FILES['archivo_poster']['name'] )
	) {
	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Se valida la estructura del archivo antes de subirlo.
		$poster_nuevo = vidchlog_subir_poster( $_FILES['archivo_poster'] );

		if ( false === $poster_nuevo ) {
			wp_die( 'No se pudo subir el nuevo poster.' );
		}

		$poster = $poster_nuevo;
	}

	/*
	 * Obtener las marcaciones enviadas por el formulario.
	 */
	$tiempo = isset( $_POST['marker_time'] )
		&& is_array( $_POST['marker_time'] )
		? array_map(
			'sanitize_text_field',
			wp_unslash( $_POST['marker_time'] )
		)
		: array();

	$titulo = isset( $_POST['marker_title'] )
		&& is_array( $_POST['marker_title'] )
		? array_map(
			'sanitize_text_field',
			wp_unslash( $_POST['marker_title'] )
		)
		: array();

	/*
	 * Validar las marcaciones.
	 */
	$marcaciones_correctas = true;

	/*
	 * Los dos arrays deben tener la misma cantidad
	 * de elementos.
	 */
	if ( count( $tiempo ) !== count( $titulo ) ) {
		$marcaciones_correctas = false;
	}

	/*
	 * Validar cada marcación.
	 */
	if ( $marcaciones_correctas ) {

		foreach ( $tiempo as $indice => $tiempo_actual ) {
			/*
			 * Validar formato del tiempo.
			 *
			 * Ejemplos válidos:
			 * 00:10
			 * 01:25
			 * 10:30
			 */
			if ( ! preg_match( '/^\d{1,2}:[0-5]\d$/', $tiempo_actual ) ) {
				$marcaciones_correctas = false;
				break;
			}

			/*
			 * Obtener el título correspondiente.
			 */
			$titulo_marcacion = $titulo[ $indice ] ?? '';

			/*
			 * Validar que el título no esté vacío.
			 */
			if ( empty( trim( $titulo_marcacion ) ) ) {
				$marcaciones_correctas = false;
				break;
			}
		}
	}

	/*
	 * Si las marcaciones son inválidas,
	 * no continuar con la actualización.
	 */
	if ( ! $marcaciones_correctas ) {
		wp_die(
			'Las marcaciones enviadas no son válidas.'
		);
	}

	/*
	 * Actualizar los datos principales del video.
	 */
	vidchlog_update_video_db(
		$idvideo,
		$video,
		$logo,
		$poster,
		$autoplay,
		$muted,
		$loop,
		$estilo
	);

	/*
	 * Eliminar las marcaciones anteriores.
	 */
	vidchlog_delete_markers_by_video( $idvideo );

	/*
	 * Insertar las nuevas marcaciones.
	 */
	foreach ( $tiempo as $indice => $tiempo_actual ) {
		/*
		 * Convertir el tiempo de mm:ss a segundos.
		 */
		$tiempo_segundo = vidchlog_convert_to_seconds(
			$tiempo_actual
		);

		/*
		 * Obtener el título correspondiente.
		 */
		$titulo_marcacion = $titulo[ $indice ];

		/*
		 * Insertar la marcación.
		 */
		vidchlog_insert_marker(
			$idvideo,
			$tiempo_segundo,
			$titulo_marcacion
		);
	}

	/*
	 * Regresar al listado de videos.
	 */
	wp_safe_redirect(
		admin_url( 'admin.php?page=admin_videos' )
	);

	exit;
}
