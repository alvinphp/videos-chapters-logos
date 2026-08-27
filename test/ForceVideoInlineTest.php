<?php

define( 'ABSPATH', true );

require_once __DIR__ . '/../functions/functions.php';

use PHPUnit\Framework\TestCase;

class ForceVideoInlineTest extends TestCase
{
    public function testVideoQuedaPrimero(): void
    {
        $entrada =
            '[videos_chapters_logos logo="logo.png" video="Sintel.mp4" width="640"]';

        $resultado = vidchlog_force_video_inline( $entrada );

        $esperado =
            '[videos_chapters_logos video="Sintel.mp4" logo="logo.png" width="640"]';

        $this->assertEquals( $esperado, $resultado );
    }

    public function testVideoYaEstaPrimero(): void
    {
        $entrada =
            '[videos_chapters_logos video="Sintel.mp4" logo="logo.png"]';

        $resultado = vidchlog_force_video_inline( $entrada );

        $esperado =
            '[videos_chapters_logos video="Sintel.mp4" logo="logo.png"]';

        $this->assertEquals( $esperado, $resultado );
    }

    public function testSinVideoNoModificaShortcode(): void
    {
        $entrada =
            '[videos_chapters_logos logo="logo.png" width="640"]';

        $resultado = vidchlog_force_video_inline( $entrada );

        $this->assertEquals( $entrada, $resultado );
    }

    public function testVideoAlFinal(): void
    {
        $entrada =
            '[videos_chapters_logos logo="logo.png" width="640" video="movie.mp4"]';

        $resultado = vidchlog_force_video_inline( $entrada );

        $esperado =
            '[videos_chapters_logos video="movie.mp4" logo="logo.png" width="640"]';

        $this->assertEquals( $esperado, $resultado );
    }

    public function testConservaOtrosAtributos(): void
    {
        $entrada =
            '[videos_chapters_logos width="800" height="450" video="movie.mp4" loop="true"]';

        $resultado = vidchlog_force_video_inline( $entrada );

        $esperado =
            '[videos_chapters_logos video="movie.mp4" width="800" height="450" loop="true"]';

        $this->assertEquals( $esperado, $resultado );
    }
}