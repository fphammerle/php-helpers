<?php

namespace fphammerle\helpers\tests;

use fphammerle\helpers\Image;

class ImageTest extends \PHPUnit_Framework_TestCase
{
    public function testSaveJpeg()
    {
        $img = Image::fromFile(__DIR__ . '/data/chainring.jpg');
        $tmp_path = tempnam(sys_get_temp_dir(), 'image');
        $img->saveJpeg($tmp_path);
        $this->assertFileEquals(__DIR__ . '/data/chainring-saved.jpg', $tmp_path);
        unlink($tmp_path);
    }
}
