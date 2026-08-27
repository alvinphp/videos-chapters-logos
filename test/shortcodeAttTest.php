<?php
use PHPUnit\Framework\TestCase;

function shortcode_atts( $defaults, $atts ){
    return array_merge( $defaults, $atts );
}

class ShortCodeAttibuteTest extends Testcase{
    public function valoresdefectos():void{
        $entrada = array();

        $esperado = array(
			'video'    => 'Sintel.mp4',
			'logo'     => 'logo.png',
			'poster'   => 'sintel.jpg',
			'width'    => '640',
			'height'   => '360',
			'autoplay' => 'false',
			'loop'     => 'false',
			'markers'  => '',
			'id'       => '',
		);

        $resultado = shortocode_atts($esperad,$entrada);
        $this->assertEquals($esperado,$resultado);
    }
    public function testPuedeCambiarVideo():void{
        $defaults = array(
			'video'    => 'Sintel.mp4',
			'logo'     => 'logo.png',
			'poster'   => 'sintel.jpg',
			'width'    => '640',
			'height'   => '360',
			'autoplay' => 'false',
			'loop'     => 'false',
			'markers'  => '',
			'id'       => '',
		);
        $entrada = array(
            'video' => 'mi-video.mp4',
        );

        $resultado = shortcode_atts(
            $defaults,
            $entrada
        );

        $this->assertEquals(
			'mi-video.mp4',
			$resultado['video']
		);

		$this->assertEquals(
			'logo.png',
			$resultado['logo']
		);

    }

    public function testPuedeCambiarWidthYHeight(): void {

		$defaults = array(
			'video'    => 'Sintel.mp4',
			'logo'     => 'logo.png',
			'poster'   => 'sintel.jpg',
			'width'    => '640',
			'height'   => '360',
			'autoplay' => 'false',
			'loop'     => 'false',
			'markers'  => '',
			'id'       => '',
		);

		$entrada = array(
			'width'  => '800',
			'height' => '450',
		);

		$resultado = shortcode_atts(
			$defaults,
			$entrada
		);

		$this->assertEquals( '800', $resultado['width'] );
		$this->assertEquals( '450', $resultado['height'] );
	}

}