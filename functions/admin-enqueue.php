<?php
/**
 * Encolar estilos del panel de administración.
 *
 * @param string $hook Hook de la página actual del admin.
 */
function vidchlog_admin_assets( $hook ) {
    
    $paginas = array(
        'toplevel_page_videos-chapters-logos',
        'admin_page_admin_videos',
        'admin_page_panel',
    );

    if ( ! in_array( $hook, $paginas, true ) ) {
        return;
    }

    $plugin_url = plugin_dir_url( __DIR__ );

    wp_enqueue_style(
        'vidchlog-admin-panel',
        $plugin_url . 'assets/css/panel.css',
        array(),
        '1.0.1' 
    );

    wp_enqueue_script(
        'vidchlog-admin-js-panel',
        $plugin_url . 'assets/js/color-input.js',
        array(),
        '1.0.1' 
    );
}
add_action( 'admin_enqueue_scripts', 'vidchlog_admin_assets' );