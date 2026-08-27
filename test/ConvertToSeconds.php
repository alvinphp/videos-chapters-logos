<?php
define( 'ABSPATH', true );
require_once __DIR__ . '/../functions/functions.php';

use PHPUnit\Framework\TestCase;

class ConvertToSeconds extends TestCase{
    public function testminutosysegundos():void{
        $resultado = vidchlog_convert_to_seconds( '5:50' );

        $this->assertEquals(350, $resultado); 
    }
    //--------------------------------------------------------
    public function testunminuto():void{
         $resultado = vidchlog_convert_to_seconds( '1:00' );

        $this->assertEquals(60,$resultado);
    }
    //--------------------------------------------------------
    public function testsolosegundos():void{
        $resultado = vidchlog_convert_to_seconds( '30' );

        $this->assertEquals( 30, $resultado );
    }

}


