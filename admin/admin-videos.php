<?php
/**
 * Administrador de videos.
 *
 * @package Videos_Chapters_Logos
 * @since 1.2.0
 */

require_once plugin_dir_path( __FILE__ ) . '../functions/database.php';

$datos = vidchlog_get_video();
?>

<div class="wrap">
	<h1>Video Catalog</h1>

	<div style="text-align: right;">
		<a
			href="<?php echo esc_url( admin_url( 'admin.php?page=videos-chapters-logos' ) ); ?>"
			class="button"
		>
			← Back
		</a>
	</div>

	<br>

	<table class="wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th scope="col" class="manage-column column-name">Id</th>
				<th scope="col" class="manage-column column-position">Video</th>
				<th scope="col" class="manage-column column-office">Logo</th>
				<th scope="col" class="manage-column column-age">Poster</th>
				<th scope="col" class="manage-column column-age">Autoplay</th>
				<th scope="col" class="manage-column column-age">Muted</th>
				<th scope="col" class="manage-column column-age">Loop</th>
				<th scope="col" class="manage-column column-age">Shortcode</th>
				<th scope="col" class="manage-column column-age">Actions</th>
			</tr>
		</thead>

		<tbody>
			<?php foreach ( $datos as $videos ) : ?>
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
						<?php
						echo esc_html(
							'[videos_chapters_logos id="' . $videos->idvideo . '"]'
						);
						?>
					</td>

					<td>
						<a
							href="
							<?php
							echo esc_url(
								wp_nonce_url(
									admin_url(
										'admin-post.php?action=vidchlog_delete_video&id=' .
										$videos->idvideo
									),
									'vidchlog_delete_video_' . $videos->idvideo
								)
							);
							?>
							"
							class="button"
							onclick="return confirm('¿Seguro que deseas eliminar este video?');"
						>
							Delete 
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
