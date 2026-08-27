<?php

define( 'ABSPATH', true );

use PHPUnit\Framework\TestCase;

if ( ! function_exists( 'add_action' ) ) {
    function add_action( $hook, $callback ) {
        // Simulación para PHPUnit.
    }
}

if ( ! function_exists( 'plugin_dir_url' ) ) {
    function plugin_dir_url( $file ) {
        return 'http://example.com/wp-content/plugins/videos-chapters-logos/';
    }
}

if ( ! function_exists( 'wp_enqueue_style' ) ) {
    function wp_enqueue_style(
        $handle,
        $src = '',
        $deps = array(),
        $ver = false
    ) {
        global $test_styles;

        $test_styles[] = array(
            'handle' => $handle,
            'src'    => $src,
            'deps'   => $deps,
            'ver'    => $ver,
        );
    }
}

require_once __DIR__ . '/../functions/enqueue.php';

class EnqueueAssetsTest extends TestCase
{
    protected function setUp(): void
    {
        global $test_styles;

        $test_styles = array();
    }

    public function testEncolaTresEstilos(): void
    {
        vidchlog_enqueue_assets();

        global $test_styles;

        $this->assertCount( 3, $test_styles );
    }

    public function testEncolaVideoJsCss(): void
    {
        vidchlog_enqueue_assets();

        global $test_styles;

        $this->assertEquals(
            'video-js-css',
            $test_styles[0]['handle']
        );
    }

    public function testEncolaVideoLogoCss(): void
    {
        vidchlog_enqueue_assets();

        global $test_styles;

        $this->assertEquals(
            'video-logo-css',
            $test_styles[1]['handle']
        );
    }

    public function testEncolaVideoMarkersCss(): void
    {
        vidchlog_enqueue_assets();

        global $test_styles;

        $this->assertEquals(
            'video-markers-css',
            $test_styles[2]['handle']
        );
    }
}