<?php

define( 'ABSPATH', true );

require_once __DIR__ . '/../functions/functions.php';

use PHPUnit\Framework\TestCase;

class CleanShortcodeFull extends TestCase
{
    public function testEliminarSaltosLinea(): void
    {
        $entrada = "[videos_chapters_logos\nvideo=\"Sintel.mp4\"]";

        $resultado = vidchlog_clean_shortcode_full( $entrada );

        $esperado = '[videos_chapters_logos video="Sintel.mp4"]';

        $this->assertEquals( $esperado, $resultado );
    }

    public function testEliminarTabulaciones(): void
    {
        $entrada = "[videos_chapters_logos\tvideo=\"Sintel.mp4\"]";

        $resultado = vidchlog_clean_shortcode_full( $entrada );

        $esperado = '[videos_chapters_logos video="Sintel.mp4"]';

        $this->assertEquals( $esperado, $resultado );
    }

    public function testReducirEspaciosMultiples(): void
    {
        $entrada = '[videos_chapters_logos    video="Sintel.mp4"]';

        $resultado = vidchlog_clean_shortcode_full( $entrada );

        $esperado = '[videos_chapters_logos video="Sintel.mp4"]';

        $this->assertEquals( $esperado, $resultado );
    }

    public function testEliminarBr(): void
    {
        $entrada = '[videos_chapters_logos]<br>';

        $resultado = vidchlog_clean_shortcode_full( $entrada );

        $esperado = '[videos_chapters_logos]';

        $this->assertEquals( $esperado, $resultado );
    }

    public function testEliminarEspaciosAlInicioYFinal(): void
    {
        $entrada = '   [videos_chapters_logos]   ';

        $resultado = vidchlog_clean_shortcode_full( $entrada );

        $esperado = '[videos_chapters_logos]';

        $this->assertEquals( $esperado, $resultado );
    }
}