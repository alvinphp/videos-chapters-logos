<?php
/**
 * Pasar lo datos de php a js.
 *
 * @package videos-chapters-logos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'wp_footer',
	function () {

		global $vidchlog_videos_data;

		if ( ! empty( $vidchlog_videos_data ) ) {

			wp_localize_script(
				'video-extends-init',
				'vidchlog_videoExtendsData',
				$vidchlog_videos_data
			);
		}
	}
);
