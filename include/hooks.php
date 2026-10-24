<?php
/**
 * Hooks principales de la aplicación.
 *
 * @package videos-chapters-logos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

register_activation_hook(
	__FILE__,
	'vidchlog_crear_tablas'
);

add_action(
	'admin_post_vidchlog_delete_video',
	'vidchlog_procesar_delete_video'
);

add_action(
	'admin_post_vidchlog_update_video',
	'vidchlog_procesar_update_video'
);
