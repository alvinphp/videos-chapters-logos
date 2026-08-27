<?php

use PHPUnit\Framework\TestCase;

class MarkersArrayTest extends TestCase
{
    public function testUnMarcador(): void
    {
        $markers = '0:39=Inicio';

        $markers_array = array();

        if ( ! empty( $markers ) ) {
            $markers_list = explode( ',', $markers );

            foreach ( $markers_list as $marker ) {
                if ( strpos( $marker, '=' ) !== false ) {
                    list( $time, $text ) = explode( '=', $marker, 2 );

                    $markers_array[] = array(
                        'time' => vidchlog_convert_to_seconds( trim( $time ) ),
                        'text' => trim( $text ),
                    );
                }
            }
        }

        $this->assertCount( 1, $markers_array );

        $this->assertEquals(
            39,
            $markers_array[0]['time']
        );

        $this->assertEquals(
            'Inicio',
            $markers_array[0]['text']
        );
    }

    public function testVariosMarcadores(): void
    {
        $markers = '0:39=Inicio,5:50=Capítulo 2,7:50=Capítulo 3';

        $markers_array = array();

        $markers_list = explode( ',', $markers );

        foreach ( $markers_list as $marker ) {
            if ( strpos( $marker, '=' ) !== false ) {
                list( $time, $text ) = explode( '=', $marker, 2 );

                $markers_array[] = array(
                    'time' => vidchlog_convert_to_seconds( trim( $time ) ),
                    'text' => trim( $text ),
                );
            }
        }

        $this->assertCount( 3, $markers_array );

        $this->assertEquals( 39, $markers_array[0]['time'] );
        $this->assertEquals( 350, $markers_array[1]['time'] );
        $this->assertEquals( 470, $markers_array[2]['time'] );
    }

    public function testMarcadorSinIgualSeIgnora(): void
    {
        $markers = '0:39=Inicio,marcador-invalido,5:50=Capítulo 2';

        $markers_array = array();

        $markers_list = explode( ',', $markers );

        foreach ( $markers_list as $marker ) {
            if ( strpos( $marker, '=' ) !== false ) {
                list( $time, $text ) = explode( '=', $marker, 2 );

                $markers_array[] = array(
                    'time' => vidchlog_convert_to_seconds( trim( $time ) ),
                    'text' => trim( $text ),
                );
            }
        }

        $this->assertCount( 2, $markers_array );
    }

    public function testMarkersVacio(): void
    {
        $markers = '';

        $markers_array = array();

        if ( ! empty( $markers ) ) {
            $markers_list = explode( ',', $markers );

            foreach ( $markers_list as $marker ) {
                if ( strpos( $marker, '=' ) !== false ) {
                    list( $time, $text ) = explode( '=', $marker, 2 );

                    $markers_array[] = array(
                        'time' => vidchlog_convert_to_seconds( trim( $time ) ),
                        'text' => trim( $text ),
                    );
                }
            }
        }

        $this->assertEmpty( $markers_array );
    }
}