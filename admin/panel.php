<?php
/**
 * Panel principal del plugin Videos Chapters & Logos.
 *
 * @package Videos_Chapters_Logos
 */

/**
 * Agrega el menú del plugin al panel de administración.
 */
function vidchlog_agregar_menu() {
	add_menu_page(
		'Videos Chapters & Logos',
		'Videos Chapters',
		'manage_options',
		'videos-chapters-logos',
		'vidchlog_pagina_principal',
		'dashicons-video-alt3',
		25
	);

	add_submenu_page(
		null,
		'Videos guardados',
		'Videos guardados',
		'manage_options',
		'admin_videos',
		'vidchlog_pagina_videos'
	);

	add_submenu_page(
		null,
		'Regresar',
		'Regresar',
		'manage_options',
		'panel',
		'vidchlog_lista_videos'
	);
}

add_action( 'admin_menu', 'vidchlog_agregar_menu' );

/**
 * Carga los scripts JavaScript del administrador.
 */
require_once __DIR__ . '/../functions/adminjs.php';

/**
 * Muestra la página de videos guardados.
 */
function vidchlog_pagina_videos() {
	require_once plugin_dir_path( __FILE__ ) . 'admin-videos.php';
}

/**
 * Muestra el panel principal de videos.
 */
function vidchlog_lista_videos() {
	require_once plugin_dir_path( __FILE__ ) . 'panel.php';
}

/**
 * Contenido de la página principal.
 */
function vidchlog_pagina_principal() {
	require_once plugin_dir_path( __FILE__ ) . '../functions/functions.php';

	if (
		isset( $_POST['vidchlog_guardar_video'], $_POST['vidchlog_nonce'] )
		&& wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['vidchlog_nonce'] ) ),
			'vidchlog_guardar_video'
		)
	) {
		global $wpdb;

		$tabla_videos      = $wpdb->prefix . 'video';
		$tabla_marcaciones = $wpdb->prefix . 'marcaciones';

		$video = isset( $_FILES['archivo_video'] )
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Se valida la estructura y se sanitiza el nombre del archivo.
			? $_FILES['archivo_video']
			: array();

		if ( ! is_array( $video ) ) {
			$video = array();
		}

		$nombre_video = isset( $video['name'] )
			? sanitize_file_name( $video['name'] )
			: '';

		$logo = isset( $_FILES['archivo_logo'] )
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Se valida la estructura y se sanitiza el nombre del archivo.
			? $_FILES['archivo_logo']
			: array();

		if ( ! is_array( $logo ) ) {
			$logo = array();
		}

		$nombre_logo = isset( $logo['name'] )
			? sanitize_file_name( $logo['name'] )
			: '';

		$poster = isset( $_FILES['archivo_poster'] )
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Se valida la estructura y se sanitiza el nombre del archivo.
			? $_FILES['archivo_poster']
			: array();

		if ( ! is_array( $poster ) ) {
			$poster = array();
		}

		$nombre_poster = isset( $poster['name'] )
			? sanitize_file_name( $poster['name'] )
			: '';

		$autoplay   = isset( $_POST['vidchlog_autoplay'] ) ? 1 : 0;
		$muted      = isset( $_POST['vidchlog_muted'] ) ? 1 : 0;
		$loop_video = isset( $_POST['vidchlog_loop'] ) ? 1 : 0;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Inserción necesaria en la tabla propia del plugin.
		$consulta = $wpdb->insert(
			$tabla_videos,
			array(
				'video'      => $nombre_video,
				'logo'       => $nombre_logo,
				'poster'     => $nombre_poster,
				'autoplay'   => $autoplay,
				'muted'      => $muted,
				'loop_video' => $loop_video,
			),
			array(
				'%s',
				'%s',
				'%s',
				'%d',
				'%d',
				'%d',
			)
		);

		$id_video = $wpdb->insert_id;

		$tiempo = isset( $_POST['marker_time'] ) && is_array( $_POST['marker_time'] )
			? array_map(
				'sanitize_text_field',
				wp_unslash( $_POST['marker_time'] )
			)
			: array();

		$titulo = isset( $_POST['marker_title'] ) && is_array( $_POST['marker_title'] )
			? array_map(
				'sanitize_text_field',
				wp_unslash( $_POST['marker_title'] )
			)
			: array();

		$marcaciones_correctas = true;

		if ( count( $tiempo ) !== count( $titulo ) ) {
			$marcaciones_correctas = false;
		} else {
			foreach ( $tiempo as $indice => $tiempo_actual ) {

				if ( ! preg_match( '/^\d{1,2}:[0-5]\d$/', $tiempo_actual ) ) {
					$marcaciones_correctas = false;
					break;
				}

				$titulo_marcacion = $titulo[ $indice ] ?? '';

				if ( empty( trim( $titulo_marcacion ) ) ) {
					$marcaciones_correctas = false;
					break;
				}

				$tiempos_segundos = vidchlog_convert_to_seconds( $tiempo_actual );

                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Inserción necesaria en la tabla propia del plugin.
				$consulta_marcacion = $wpdb->insert(
					$tabla_marcaciones,
					array(
						'idvideo' => $id_video,
						'tiempo'  => $tiempos_segundos,
						'titulo'  => $titulo_marcacion,
					),
					array(
						'%d',
						'%d',
						'%s',
					)
				);

				if ( false === $consulta_marcacion ) {
					$marcaciones_correctas = false;
					break;
				}
			}
		}

		if ( false !== $consulta && true === $marcaciones_correctas ) {
			echo '<div class="notice notice-success">
                <p>Video and markers saved successfully.</p>
            </div>';
		} else {
			echo '<div class="notice notice-error">
                <p>Error saving the video or markers.</p>
            </div>';
		}
	}
	?>

	<div class="wrap">

		<h1>Video Configuration</h1>

		<div style="text-align: right;">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=admin_videos' ) ); ?>">
				Stored Videos →
			</a>
		</div>

		<form method="post" enctype="multipart/form-data">

			<?php wp_nonce_field( 'vidchlog_guardar_video', 'vidchlog_nonce' ); ?>

			<h2>Add Video</h2>

			<div class="campo-form">
				<label for="video-archivo">Upload MP4 video:</label>
				<input
					type="file"
					id="video-archivo"
					name="archivo_video"
					accept="video/mp4,video/webm"
					required
				>
			</div>

			<div class="campo-form">
				<label for="logo-archivo">Upload Logo:</label>
				<input
					type="file"
					id="logo-archivo"
					name="archivo_logo"
					accept="image/png,image/jpeg"
					required
				>
			</div>

			<div class="campo-form">
				<label for="poster-archivo">Upload Poster:</label>
				<input
					type="file"
					id="poster-archivo"
					name="archivo_poster"
					accept="image/png,image/jpeg"
					required
				>
			</div>

			<hr>

			<div class="vidchlog-controls">

				<h2>Video Player Controls</h2>

				<label>
					<input
						type="checkbox"
						name="vidchlog_autoplay"
						value="1"
					>
					Autoplay
				</label>

				<label>
					<input
						type="checkbox"
						name="vidchlog_muted"
						value="1"
					>
					Muted
				</label>

				<label>
					<input
						type="checkbox"
						name="vidchlog_loop"
						value="1"
					>
					Loop
				</label>

			</div>

			<hr>

			<div class="vidchlog-markers">

				<h2>Markers</h2>

				<div id="vidchlog-markers-container">

					<div class="vidchlog-marker">
						<input
							type="text"
							name="marker_time[]"
							placeholder="00:00"
							required
						>

						<input
							type="text"
							name="marker_title[]"
							placeholder="Chapter Title"
							class="regular-text"
							required
						>
					</div>

				</div>

				<p>
					<button
						type="button"
						id="vidchlog-add-marker"
						class="button"
					>
						+ Add Markers
					</button>

					<button
						type="button"
						id="vidchlog-remove-marker"
						class="button"
					>
						- Remove
					</button>
				</p>

			</div>

			<hr>

			<button
				type="submit"
				name="vidchlog_guardar_video"
				class="button button-primary"
			>
				Save
			</button>

		</form>

	</div>

	<?php
}