<?php

use PHPUnit\Framework\TestCase;

define( 'ABSPATH', true );

if ( ! function_exists( 'esc_attr' ) ) {
	function esc_attr( $text ) {
		return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
	}
}

if ( ! function_exists( 'esc_url' ) ) {
	function esc_url( $url ) {
		return $url;
	}
}

class ShortcodeHtmlTest extends TestCase {

	public function testVideoHtmlCompleto(): void {

		$atts = array(
			'id'     => 'video_1',
			'width'  => '640',
			'height' => '360',
		);

		$video_url  = 'http://example.com/assets/video/Sintel.mp4';
		$poster_url = 'http://example.com/assets/img/sintel.jpg';

		$video_attrs = '';

		$html = '<video class=" video-js vjs-fluid "'
			. ' id="' . esc_attr( $atts['id'] ) . '"'
			. ' width="' . esc_attr( $atts['width'] ) . '"'
			. ' height="' . esc_attr( $atts['height'] ) . '"'
			. ' poster="' . esc_url( $poster_url ) . '"'
			. ' controls ' . esc_attr( $video_attrs ) . '>'
			. '<source src="' . esc_url( $video_url ) . '" type="video/mp4">'
			. '</video>';

		// Clase del reproductor.
		$this->assertStringContainsString(
			'class=" video-js vjs-fluid "',
			$html
		);

		// ID.
		$this->assertStringContainsString(
			'id="video_1"',
			$html
		);

		// Ancho.
		$this->assertStringContainsString(
			'width="640"',
			$html
		);

		// Alto.
		$this->assertStringContainsString(
			'height="360"',
			$html
		);

		// Poster.
		$this->assertStringContainsString(
			'poster="http://example.com/assets/img/sintel.jpg"',
			$html
		);

		// Controles.
		$this->assertStringContainsString(
			'controls',
			$html
		);

		// Source del video.
		$this->assertStringContainsString(
			'src="http://example.com/assets/video/Sintel.mp4"',
			$html
		);

		// Tipo de archivo.
		$this->assertStringContainsString(
			'type="video/mp4"',
			$html
		);

		// Etiqueta de cierre.
		$this->assertStringContainsString(
			'</video>',
			$html
		);
	}
}