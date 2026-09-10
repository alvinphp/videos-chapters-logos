<?php
/**
 * Widget para videos, logos y marcaciones.
 *
 * @package Videos_Chapters_Logos
 */

// Seguridad.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Clase para generar el widget.
 */
class Vidchlog_Video_Widget extends WP_Widget {
	/**
	 *  Constructor del widget.
	 */
	public function __construct() {
		parent::__construct(
			'widget_video',
			'Widget Logos & Markers'
		);
	}

	/**
	 * Config widget .
	 *
	 * @param array $args     Argumentos del widget.
	 * @param array $instance Configuración de la instancia del widget.
	 */
	public function widget( $args, $instance ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['before_widget'];

		$video_id = ! empty( $instance['video_id'] ) ? absint( $instance['video_id'] ) : 0;

		if ( $video_id > 0 ) {
			// Creamos una clave única basada en el ID del widget en el panel.
			$unique_key = sanitize_html_class( $args['widget_id'] ) . '_' . wp_rand( 100, 999 );
			echo do_shortcode( '[videos_chapters_logos id="' . $video_id . '" unique="' . $unique_key . '"]' );
		} else {
			echo '<p>' . esc_html__( 'Por favor, introduce un ID de video válido.', 'videos-chapters-logos' ) . '</p>';
		}
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['after_widget'];
	}

	/**
	 * Muestra el formulario de configuración.
	 *
	 * @param array $instance Configuración guardada del widget.
	 */
	public function form( $instance ) {
		$video_id = ! empty( $instance['video_id'] ) ? absint( $instance['video_id'] ) : 0;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'video_id' ) ); ?>"><?php esc_html_e( 'ID del Video:', 'videos-chapters-logos' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'video_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'video_id' ) ); ?>" type="number" value="<?php echo esc_attr( $video_id ); ?>" min="1" />
		</p>
		<p class="description"><?php esc_html_e( 'Escribe el ID numérico del video que deseas mostrar.', 'videos-chapters-logos' ); ?></p>
		<?php
	}

	/**
	 * Actualiza la configuración del widget.
	 *
	 * @param array $new_instance Nueva configuración del widget.
	 * @param array $old_instance Configuración anterior del widget.
	 * @return array Configuración actualizada.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance             = array();
		$instance['video_id'] = ( ! empty( $new_instance['video_id'] ) ) ? absint( $new_instance['video_id'] ) : 0;

		return $instance;
	}
}

// phpcs:ignore Universal.Files.SeparateFunctionsFromOO.Mixed
/**
 * Registra el widget.
 *
 * @return void
 */
add_action(
	'widgets_init',
	function () {
		register_widget( 'Vidchlog_Video_Widget' );
	}
);
