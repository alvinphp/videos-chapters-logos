<?php

use PHPUnit\Framework\TestCase;

class IdTest extends TestCase
{
    public function test_generar_id(): void
    {
        $atts = array(
            'id' => '',
        );

        $video_count = 1;

        if ( empty( $atts['id'] ) ) {
            $atts['id'] = 'video_' . $video_count . '_' . uniqid();
        }

        $this->assertNotEmpty( $atts['id'] );

        $this->assertStringStartsWith(
            'video_1_',
            $atts['id']
        );
    }

    public function testConservaIdExistente(): void
    {
        $atts = array(
            'id' => 'mi-video',
        );

        $video_count = 1;

        if ( empty( $atts['id'] ) ) {
            $atts['id'] = 'video_' . $video_count . '_' . uniqid();
        }

        $this->assertEquals(
            'mi-video',
            $atts['id']
        );
    }
}