<?php
/**
 * Listado de videos almacenados.
 *
 * @package Videos_Chapters_Logos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;

/*
 * Obtener todos los videos y sus estilos.
 */
// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Los nombres de las tablas proceden de constantes del plugin.
require_once plugin_dir_path( __FILE__ ) . '../functions/database.php';
$datos = vidchlog_get_video();
?>
<div id="vidchlog-listado-videos" class="vidchlog-listado-videos">

	<table id="tabla-videos" class="wp-list-table widefat fixed striped">

		<thead>
			<tr>
				<th scope="col">Id</th>
				<th scope="col">Video</th>
				<th scope="col">Logo</th>
				<th scope="col">Poster</th>
				<th scope="col">Autoplay</th>
				<th scope="col">Muted</th>
				<th scope="col">Loop</th>
				<th scope="col">Style</th>
				<th scope="col">Shortcode</th>
				<th scope="col">Delete</th>
				<th scope="col">Edit</th>
			</tr>
		</thead>

		<tbody>

			<?php if ( ! empty( $datos ) ) : ?>

				<?php foreach ( $datos as $videos ) : ?>

					<?php
					$markers = vidchlog_get_markers_by_video(
						$videos->idvideo
					);
					?>

					<tr>
						<td>
							<?php echo esc_html( $videos->idvideo ); ?>
						</td>

						<td>
							<?php echo esc_html( $videos->video ); ?>
						</td>

						<td>
							<?php echo esc_html( $videos->logo ); ?>
						</td>

						<td>
							<?php echo esc_html( $videos->poster ); ?>
						</td>

						<td>
							<?php echo 1 === (int) $videos->autoplay ? 'Sí' : 'No'; ?>
						</td>

						<td>
							<?php echo 1 === (int) $videos->muted ? 'Sí' : 'No'; ?>
						</td>

						<td>
							<?php echo 1 === (int) $videos->loop_video ? 'Sí' : 'No'; ?>
						</td>

						<td>
							<?php echo esc_html( $videos->estilo ?? '' ); ?>
						</td>

						<td>
							<?php
							echo esc_html(
								'[videos_chapters_logos id="' .
								$videos->idvideo .
								'"]'
							);
							?>
						</td>

						<!-- <td>
							<a
								href="
								<?php
								echo esc_url(
									wp_nonce_url(
										admin_url(
											'admin-post.php?action=vidchlog_delete_video&id=' .
											absint( $videos->idvideo )
										),
										'vidchlog_delete_video_' .
										absint( $videos->idvideo )
									)
								);
								?>
										"
								class="button"
								onclick="return confirm('¿Seguro que deseas eliminar este video?');"
								aria-label="
								<?php
								echo esc_attr(
									sprintf(
										'Eliminar video %d',
										absint( $videos->idvideo )
									)
								);
								?>
											"
							>
								<span class="dashicons dashicons-trash"></span>
							</a>
						</td> -->

						<td>
	<button
		type="button"
		class="button vidchlog-eliminar-video"
		data-id="<?php echo esc_attr( absint( $videos->idvideo ) ); ?>"
		aria-label="
					<?php
					echo esc_attr(
						sprintf(
							'Eliminar video %d',
							absint( $videos->idvideo )
						)
					);
					?>
					"
	>
		<span class="dashicons dashicons-trash"></span>
	</button>
</td>

						<td>
							<button
								type="button"
								class="button vidchlog-edit"
								data-id="<?php echo esc_attr( $videos->idvideo ); ?>"
								data-video="<?php echo esc_attr( $videos->video ); ?>"
								<?php if ( ! empty( $videos->logo ) ) : ?>
									data-logo="<?php echo esc_attr( $videos->logo ); ?>"
								<?php endif; ?>
								data-poster="<?php echo esc_attr( $videos->poster ); ?>"
								data-autoplay="<?php echo esc_attr( $videos->autoplay ); ?>"
								data-muted="<?php echo esc_attr( $videos->muted ); ?>"
								data-loop="<?php echo esc_attr( $videos->loop_video ); ?>"
								data-style="<?php echo esc_attr( $videos->id_estilo ); ?>"
								<?php if ( ! empty( $markers ) ) : ?>
									<?php
									$markers_data = array();

									foreach ( $markers as $marker ) {
										$markers_data[] = array(
											'tiempo' => vidchlog_convert_to_minute_seconds(
												$marker['tiempo']
											),
											'titulo' => $marker['titulo'],
										);
									}
									?>
									data-markers="<?php echo esc_attr( wp_json_encode( $markers_data ) ); ?>"
								<?php endif; ?>
							>
								<span class="dashicons dashicons-edit"></span>
							</button>
						</td>
					</tr>

				<?php endforeach; ?>

			<?php else : ?>

				<tr>
					<td colspan="11">
						No hay videos registrados.
					</td>
				</tr>

			<?php endif; ?>

		</tbody>

	</table>
	<?php
	/*
	*Encolando css y js de la libreria datatable.
	*/
	wp_enqueue_style(
		'datatables-css',
		'https://cdn.datatables.net/2.3.8/css/dataTables.dataTables.min.css',
		array(),
		'2.3.8'
	);
	wp_enqueue_script(
		'datatables-script',
		'https://cdn.datatables.net/2.3.8/js/dataTables.min.js',
		array( 'jquery' ),
		'2.3.8',
		true
	);

	$datatable_init = "
    jQuery(document).ready(function($) {
        if ($('#tabla-videos').length > 0) {
            new DataTable('#tabla-videos', {
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    search: 'Buscar:',
                    lengthMenu: 'Mostrar _MENU_ registros',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    infoEmpty: 'No hay registros disponibles',
                    infoFiltered: '(filtrado de _MAX_ registros)',
                    zeroRecords: 'No se encontraron resultados',
                    paginate: {
                        first: 'Primero',
                        last: 'Último',
                        next: 'Siguiente',
                        previous: 'Anterior'
                    }
                }
            });
        }
    });
";

	wp_add_inline_script( 'datatables-script', $datatable_init );

	?>
</div>
