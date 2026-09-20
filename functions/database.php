<?php
/**
 * Funciones para crear y gestionar las tablas del plugin.
 *
 * @package Videos_Chapters_Logos
 */

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
        titulo VARCHAR(255) NULL,
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
