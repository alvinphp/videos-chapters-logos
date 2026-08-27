<?php
define('ASBPATH',true);
require_once __DIR__ . '/../functions/functions.php';
require_once __DIR__ . '/../videos-chapters-logos.php';

use PHPUnit\Framework\TestCase;

class ShortCodeTest extends TestCase{
    public function testVideoInexistente(): void {

    $video = 'video-que-no-existe.mp4';

    $ruta = __DIR__ . '/../assets/video/' . $video;

    $this->assertFalse(
        file_exists( $ruta )
    );
}
}