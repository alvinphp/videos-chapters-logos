<?php
/**
 * Administrador de videos.
 *
 * @package Videos_Chapters_Logos
 * @since 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once dirname( __DIR__ ) . '/include/modal-update-video.php';
require_once plugin_dir_path( __FILE__ ) . '../functions/database.php';
require_once plugin_dir_path( __FILE__ ) . '../functions/functions.php';
?>

<div class="wrap">

	<h1 style="font-weight: 700;color:#3858e9 ;">Video Catalog</h1>

	<div style="text-align: right;">
		<a
			href="<?php echo esc_url( admin_url( 'admin.php?page=videos-chapters-logos' ) ); ?>"
			class="button"
		>
			← Back
		</a>
	</div>

	<br>
	<?php
	// incluimos tabla con datatable.
	require_once dirname( __DIR__ ) . '/include/listado-videos.php';
	?>

<!--
====================================================
MODAL DE ACTUALIZACIÓN
====================================================
-->

<div
	class="modal"
	id="myModal"
>

	<div class="modal-content">

		<span
			class="close-btn"
			id="closeModalBtn"
		>
			&times;
		</span>


		<h2 style="font-weight: 700; color:#3858e9;">Update video</h2>


		<form
			method="post"
			action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
			enctype="multipart/form-data"
		>

			<!--
			================================================
			ACCIÓN
			================================================
			-->

			<input
				type="hidden"
				name="action"
				value="vidchlog_update_video"
			>


			<!--
			================================================
			ID DEL VIDEO
			================================================
			-->

			<input
				type="hidden"
				name="idvideo"
				id="idvideo-update"
				value=""
			>


			<?php
			wp_nonce_field(
				'vidchlog_update_video',
				'vidchlog_update_nonce'
			);
			?>


			<!--
			================================================
			VIDEO
			================================================
			-->

			<div class="campo-form">

				<label for="video-update">

					<strong>Update MP4 video:</strong>

				</label>

				<br>

			<p class="title" style="color: green;">
					<strong>Video:</strong>
					<span id="video-actual"></span>
				</p>

				<input
					type="file"
					id="video-update"
					name="archivo_video"
					accept="video/mp4,video/webm"
				>

			</div>


			<br>


			<!--
			================================================
			LOGO
			================================================
			-->

			<div
				class="campo-form"
				id="logo-archivo"
			>

			<p class="title" style="color: green;">
				<strong>Logo:</strong>
				<span id="logo-actual"></span>
				</p>

				<label for="logo-update">

					<strong>Update Logo:</strong>

				</label>

				<input
					type="file"
					id="logo-update"
					name="archivo_logo"
					accept="image/png,image/jpeg"
				>

			</div>


			<br>


			<!--
			================================================
			POSTER
			================================================
			-->

			<div class="campo-form">
				<p style="color: green;">
						<strong>Poster:</strong>
						<span id="poster-actual"></span>
					</p>

				<label for="poster-update">

					<strong>Update Poster:</strong>

				</label>

				<br>

				<input
					type="file"
					id="poster-update"
					name="archivo_poster"
					accept="image/png,image/jpeg"
				>

			</div>


			<hr>


			<!--
			================================================
			STYLE
			================================================
			-->

			<h2 style="font-weight: 700; color:#3858e9;">Style Skins</h2>

			<select
				name="estilo"
				id="estilo"
			>

				<?php
				$estilo = vidchlog_get_style();
				?>

				<?php foreach ( $estilo as $estilos ) : ?>

					<option
						value="<?php echo esc_attr( $estilos->id_estilo ); ?>"
					>

						<?php echo esc_html( $estilos->estilo ); ?>

					</option>

				<?php endforeach; ?>

			</select>


			<hr>


			<!--
			================================================
			CONTROLES DEL VIDEO
			================================================
			-->

			<div class="vidchlog-controls">

				<h2 style="font-weight: 700; color:#3858e9;">Video Player Controls</h2>


				<label>

					<input
						type="checkbox"
						id="vidchlog-autoplay-update"
						name="vidchlog_autoplay"
						value="1"
					>

					Autoplay

				</label>


				<br>


				<label>

					<input
						type="checkbox"
						id="vidchlog-muted-update"
						name="vidchlog_muted"
						value="1"
					>

					Muted

				</label>


				<br>


				<label>

					<input
						type="checkbox"
						id="vidchlog-loop-update"
						name="vidchlog_loop"
						value="1"
					>

					Loop

				</label>

			</div>


			<hr>


			<!--
			================================================
			MARKERS
			================================================
			-->
			   
				<div
				id="markers"
				class="vidchlog-markers"
				style="display: none;"
			>

				<h2 style="font-weight: 700; color:#3858e9;">Markers</h2>


				<div id="vidchlog-markers-container">
					
						

				</div>

			</div>
			   

			<hr>


			<!--
			================================================
			BOTÓN GUARDAR
			================================================
			-->

			<button
				type="submit"
				class="button button-primary"
			>

				Save changes

			</button>


		</form>

	</div>

</div>


