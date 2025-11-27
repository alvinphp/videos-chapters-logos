<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<!-- Header of the page -->
<header class="headervideo-extends">
    <h1><?php esc_html_e( 'Videos Chapters & Logos Plugin Documentation', 'videos-chapters-logos' ); ?></h1>
</header>

<!-- Documentation Content -->
<div class="content">
    <section id="basic_usage">
        <h2><?php esc_html_e( 'Basic Usage of the Plugin', 'videos-chapters-logos' ); ?></h2>
        <pre>
[videos_chapters_logos video="Sintel.mp4" logo="logo.png" width="800" height="450" autoplay="true" loop="true"]
        </pre>
    </section>

    <section id="usage_with_markers">
        <h2><?php esc_html_e( 'Usage with Markers', 'videos-chapters-logos' ); ?></h2>
        <pre>
[videos_chapters_logos video="Sintel.mp4" logo="logo.png" width="800" height="450" autoplay="true" loop="true" markers="0:39=Intro,5:50=Chapter 2,7:50=Chapter 3,9:00=Chapter 4"]
        </pre>
    </section>

    <section id="file_locations">
        <h2><?php esc_html_e( 'File Locations (Videos and Logos)', 'videos-chapters-logos' ); ?></h2>
        <p><?php esc_html_e( 'For the plugin to work properly, you must place the video, logo, and poster files in the following folders within your plugin structure:', 'videos-chapters-logos' ); ?></p>
        <ul>
            <li><strong><?php esc_html_e( 'Videos:', 'videos-chapters-logos' ); ?></strong> <?php esc_html_e( 'Video files should be located in the', 'videos-chapters-logos' ); ?> <code><?php echo esc_html( 'assets/video/' ); ?></code> <?php esc_html_e( 'folder. Example:', 'videos-chapters-logos' ); ?> <code><?php echo esc_html( 'assets/video/Sintel.mp4' ); ?></code></li>
            <li><strong><?php esc_html_e( 'Logos:', 'videos-chapters-logos' ); ?></strong> <?php esc_html_e( 'Logo image files should be located in the', 'videos-chapters-logos' ); ?> <code><?php echo esc_html( 'assets/img/' ); ?></code> <?php esc_html_e( 'folder. Example:', 'videos-chapters-logos' ); ?> <code><?php echo esc_html( 'assets/img/logo.png' ); ?></code></li>
            <li><strong><?php esc_html_e( 'Posters:', 'videos-chapters-logos' ); ?></strong> <?php esc_html_e( 'Poster image files (optional) should be located in the', 'videos-chapters-logos' ); ?> <code><?php echo esc_html( 'assets/img/' ); ?></code> <?php esc_html_e( 'folder. Example:', 'videos-chapters-logos' ); ?> <code><?php echo esc_html( 'assets/img/poster.png' ); ?></code></li>
        </ul>
    </section>

    <br>

    <section>
       <p><?php esc_html_e( 'For more in-depth documentation, visit our', 'videos-chapters-logos' ); ?> <a href="<?php echo esc_url( 'https://github.com/alvinphp/documentation-video-extends/wiki' ); ?>"><?php esc_html_e( 'complete documentation', 'videos-chapters-logos' ); ?></a>.</p>

    </section>
</div>
