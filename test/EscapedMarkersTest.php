<?php

use PHPUnit\Framework\TestCase;

if ( ! function_exists( 'sanitize_text_field' ) ) {
    function sanitize_text_field( $text ) {
        return strip_tags( $text );
    }
}

if ( ! function_exists( 'esc_js' ) ) {
    function esc_js( $text ) {
        return addslashes( $text );
    }
}

class EscapedMarkersTest extends TestCase
{
    public function testLimpiaTextoPeligroso(): void
    {
        $marker = array(
            'time' => 30,
            'text' => '<script>alert("XSS")</script>',
        );

        $escaped_marker = array(
            'time' => intval( $marker['time'] ),
            'text' => esc_js(
                sanitize_text_field( $marker['text'] )
            ),
        );

        $this->assertStringNotContainsString(
            '<script>',
            $escaped_marker['text']
        );

        $this->assertStringNotContainsString(
            '</script>',
            $escaped_marker['text']
        );
    }

    public function testConservaElTiempoComoEntero(): void
    {
        $marker = array(
            'time' => '30',
            'text' => 'Inicio',
        );

        $escaped_marker = array(
            'time' => intval( $marker['time'] ),
            'text' => esc_js(
                sanitize_text_field( $marker['text'] )
            ),
        );

        $this->assertIsInt(
            $escaped_marker['time']
        );

        $this->assertEquals(
            30,
            $escaped_marker['time']
        );
    }

    public function testConservaTextoNormal(): void
    {
        $marker = array(
            'time' => 60,
            'text' => 'Capítulo 1',
        );

        $escaped_marker = array(
            'time' => intval( $marker['time'] ),
            'text' => esc_js(
                sanitize_text_field( $marker['text'] )
            ),
        );

        $this->assertEquals(
            'Capítulo 1',
            $escaped_marker['text']
        );
    }
}