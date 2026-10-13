<?php
/**
 * Modal para actualizacion.
 *
 * @package Videos_Chapters_Logos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_script(
	'vidchlog-admin-script',
	false,
	array(),
	'1.0.4',
	true
);

wp_enqueue_script( 'vidchlog-admin-script' );

wp_add_inline_script(
	'vidchlog-admin-script',
	'
	document.addEventListener("DOMContentLoaded", function () {

		/*
		 * ==================================================
		 * ELEMENTOS PRINCIPALES
		 * ==================================================
		 */

		const modal =
			document.getElementById("myModal");

		const openButtons =
			document.querySelectorAll(".vidchlog-edit");

		const closeBtn =
			document.getElementById("closeModalBtn");


		console.log("Modal:", modal);

		console.log(
			"Botones editar:",
			openButtons.length
		);


		/*
		 * ==================================================
		 * VERIFICAR ELEMENTOS
		 * ==================================================
		 */

		if (!modal) {

			console.error(
				"No se encontró el elemento #myModal."
			);

			return;

		}


		if (!closeBtn) {

			console.error(
				"No se encontró el botón #closeModalBtn."
			);

			return;

		}


		/*
		 * ==================================================
		 * BOTONES EDITAR
		 * ==================================================
		 */

		openButtons.forEach(function (button) {

			button.addEventListener(
				"click",
				function () {


					/*
					 * ==========================================
					 * OBTENER DATOS DEL VIDEO
					 * ==========================================
					 */

					const idVideo =
						button.dataset.id;

					const video =
						button.dataset.video;

					const logo =
						button.dataset.logo;

					const poster =
						button.dataset.poster;

					const autoplay =
						button.dataset.autoplay;

					const muted =
						button.dataset.muted;

					const loop =
						button.dataset.loop;

					const style =
						button.dataset.style;


					/*
					 * ==========================================
					 * OBTENER MARKERS
					 * ==========================================
					 */

					let markers = [];


					try {

						if (
							button.dataset.markers
						) {

							markers = JSON.parse(
								button.dataset.markers
							);

						}

					} catch (error) {

						console.error(
							"Error al leer markers:",
							error
						);

					}


					/*
					 * ==========================================
					 * MOSTRAR DATOS EN CONSOLA
					 * ==========================================
					 */

					console.log(
						"ID:",
						idVideo
					);

					console.log(
						"Video:",
						video
					);

					console.log(
						"Logo:",
						logo
					);

					console.log(
						"Poster:",
						poster
					);

					console.log(
						"Autoplay:",
						autoplay
					);

					console.log(
						"Muted:",
						muted
					);

					console.log(
						"Loop:",
						loop
					);

					console.log(
						"Style:",
						style
					);

					console.log(
						"Markers:",
						markers
					);


					/*
					 * ==========================================
					 * ID DEL VIDEO
					 * ==========================================
					 */

					const idVideoInput =
						document.getElementById(
							"idvideo-update"
						);


					if (idVideoInput) {

						idVideoInput.value =
							idVideo;

					}


					/*
					 * ==========================================
					 * VIDEO ACTUAL
					 * ==========================================
					 */

					const videoActual =
						document.getElementById(
							"video-actual"
						);


					if (videoActual) {

						videoActual.textContent =
							video || "No disponible";

					}


					/*
					 * ==========================================
					 * POSTER ACTUAL
					 * ==========================================
					 */

					const posterActual =
						document.getElementById(
							"poster-actual"
						);


					if (posterActual) {

						posterActual.textContent =
							poster || "No disponible";

					}


					/*
					 * ==========================================
					 * LOGO
					 * ==========================================
					 */

					const logoCampo =
						document.getElementById(
							"logo-archivo"
						);

					const logoActual =
						document.getElementById(
							"logo-actual"
						);


					if (
						logo &&
						logo !== ""
					) {

						/*
						 * Mostrar campo.
						 */

						if (logoCampo) {

							logoCampo.style.display =
								"block";

						}


						/*
						 * Mostrar nombre.
						 */

						if (logoActual) {

							logoActual.textContent =
								logo;

						}

					} else {

						/*
						 * Ocultar campo.
						 */

						if (logoCampo) {

							logoCampo.style.display =
								"none";

						}


						/*
						 * Limpiar nombre.
						 */

						if (logoActual) {

							logoActual.textContent =
								"";

						}

					}


					/*
					 * ==========================================
					 * ESTILO
					 * ==========================================
					 */

					const estilo =
						document.getElementById(
							"estilo"
						);


					if (estilo) {

						estilo.value =
							style;

					}


					/*
					 * ==========================================
					 * AUTOPLAY
					 * ==========================================
					 */

					const autoplayInput =
						document.getElementById(
							"vidchlog-autoplay-update"
						);


					if (autoplayInput) {

						autoplayInput.checked =
							autoplay === "1";

					}


					/*
					 * ==========================================
					 * MUTED
					 * ==========================================
					 */

					const mutedInput =
						document.getElementById(
							"vidchlog-muted-update"
						);


					if (mutedInput) {

						mutedInput.checked =
							muted === "1";

					}


					/*
					 * ==========================================
					 * LOOP
					 * ==========================================
					 */

					const loopInput =
						document.getElementById(
							"vidchlog-loop-update"
						);


					if (loopInput) {

						loopInput.checked =
							loop === "1";

					}


					/*
					 * ==========================================
					 * CONTENEDOR DE MARKERS
					 * ==========================================
					 */

					const markersContainer =
						document.getElementById(
							"vidchlog-markers-container"
						);

					const markersSection =
						document.getElementById(
							"markers"
						);


					if (!markersContainer) {

						console.error(
							"No se encontró #vidchlog-markers-container."
						);

						return;

					}


					/*
					 * ==========================================
					 * LIMPIAR MARKERS ANTERIORES
					 * ==========================================
					 */

					markersContainer.innerHTML =
						"";


					/*
					 * ==========================================
					 * VERIFICAR SI EXISTEN MARKERS
					 * ==========================================
					 */

					if (
						Array.isArray(markers) &&
						markers.length > 0
					) {


						/*
						 * Mostrar sección.
						 */

						if (markersSection) {

							markersSection.style.display =
								"block";

						}


						/*
						 * ======================================
						 * CREAR CADA MARKER
						 * ======================================
						 */

						markers.forEach(
							function (marker) {

								/*
								 * Crear fila.
								 */

								const row =
									document.createElement(
										"div"
									);

								row.className =
									"vidchlog-marker-update";


								/*
								 * ==============================
								 * CAMPO TIEMPO
								 * ==============================
								 */

								const timeInput =
									document.createElement(
										"input"
									);

								timeInput.type =
									"text";

								timeInput.name =
									"marker_time[]";

								timeInput.value =
									marker.tiempo || "";

								timeInput.placeholder =
									"00:00";


								/*
								 * ==============================
								 * CAMPO TÍTULO
								 * ==============================
								 */

								const titleInput =
									document.createElement(
										"input"
									);

								titleInput.type =
									"text";

								titleInput.name =
									"marker_title[]";

								titleInput.value =
									marker.titulo || "";

								titleInput.placeholder =
									"Chapter Title";

								titleInput.className =
									"regular-text";


								/*
								 * ==============================
								 * AGREGAR INPUTS A LA FILA
								 * ==============================
								 */

								row.appendChild(
									timeInput
								);

								row.appendChild(
									titleInput
								);


								/*
								 * ==============================
								 * AGREGAR FILA AL CONTENEDOR
								 * ==============================
								 */

								markersContainer.appendChild(
									row
								);

							}
						);

					} else {


						/*
						 * ======================================
						 * SIN MARKERS
						 * ======================================
						 *
						 * No se crea ninguna fila.
						 * Se oculta toda la sección.
						 */

						if (markersSection) {

							markersSection.style.display =
								"none";

						}

					}


					/*
					 * ==========================================
					 * ABRIR MODAL
					 * ==========================================
					 */

					modal.style.display =
						"flex";

				}
			);

		});


		/*
		 * ==================================================
		 * CERRAR MODAL
		 * ==================================================
		 */

		closeBtn.addEventListener(
			"click",
			function () {

				modal.style.display =
					"none";

			}
		);


		/*
		 * ==================================================
		 * CERRAR AL HACER CLICK FUERA
		 * ==================================================
		 */

		window.addEventListener(
			"click",
			function (event) {

				if (
					event.target === modal
				) {

					modal.style.display =
						"none";

				}

			}
		);

	});
	',
	'after'
);
