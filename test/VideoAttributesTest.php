<?php

use PHPUnit\Framework\TestCase;

class VideoAttributesTest extends TestCase
{
    public function testAutoplayAgregaAutoplayYMuted(): void
    {
        $autoplay = true;
        $loop = false;

        $muted = $autoplay;

        $video_attrs = '';
        $video_attrs .= $autoplay ? ' autoplay' : '';
        $video_attrs .= $muted ? ' muted' : '';
        $video_attrs .= $loop ? ' loop' : '';

        $this->assertStringContainsString(
            'autoplay',
            $video_attrs
        );

        $this->assertStringContainsString(
            'muted',
            $video_attrs
        );

        $this->assertStringNotContainsString(
            'loop',
            $video_attrs
        );
    }

    public function testLoopAgregaLoop(): void
    {
        $autoplay = false;
        $loop = true;

        $muted = $autoplay;

        $video_attrs = '';
        $video_attrs .= $autoplay ? ' autoplay' : '';
        $video_attrs .= $muted ? ' muted' : '';
        $video_attrs .= $loop ? ' loop' : '';

        $this->assertStringContainsString(
            'loop',
            $video_attrs
        );

        $this->assertStringNotContainsString(
            'autoplay',
            $video_attrs
        );

        $this->assertStringNotContainsString(
            'muted',
            $video_attrs
        );
    }

    public function testSinAutoplayNiLoopNoAgregaAtributos(): void
    {
        $autoplay = false;
        $loop = false;

        $muted = $autoplay;

        $video_attrs = '';
        $video_attrs .= $autoplay ? ' autoplay' : '';
        $video_attrs .= $muted ? ' muted' : '';
        $video_attrs .= $loop ? ' loop' : '';

        $this->assertEquals(
            '',
            $video_attrs
        );
    }
}