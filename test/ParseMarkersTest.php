<?php

define( 'ABSPATH', true );

require_once __DIR__ . '/../functions/functions.php';

use PHPUnit\Framework\TestCase;

class ParseMarkersTest extends TestCase
{
    public function testParseaDosMarcadores(): void
    {
        $entrada = '0:39=Capítulo 1,5:50=Capítulo 2';

        $resultado = vidchlog_parse_markers_string( $entrada );

        $esperado = array(
            '0:39' => 'Capítulo 1',
            '5:50' => 'Capítulo 2',
        );

        $this->assertEquals( $esperado, $resultado );
    }

    public function testCadenaVaciaDevuelveArrayVacio(): void
    {
        $resultado = vidchlog_parse_markers_string( '' );

        $this->assertEquals( array(), $resultado );
    }

    public function testEliminaEspacios(): void
    {
        $entrada = '0:39 = Capítulo 1, 5:50 = Capítulo 2';

        $resultado = vidchlog_parse_markers_string( $entrada );

        $esperado = array(
            '0:39' => 'Capítulo 1',
            '5:50' => 'Capítulo 2',
        );

        $this->assertEquals( $esperado, $resultado );
    }

    public function testIgnoraMarcadoresSinIgual(): void
    {
        $entrada = '0:39=Capítulo 1,marcador-invalido,5:50=Capítulo 2';

        $resultado = vidchlog_parse_markers_string( $entrada );

        $esperado = array(
            '0:39' => 'Capítulo 1',
            '5:50' => 'Capítulo 2',
        );

        $this->assertEquals( $esperado, $resultado );
    }
}