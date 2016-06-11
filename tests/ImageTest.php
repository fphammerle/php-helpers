<?php

namespace fphammerle\helpers\tests;

use fphammerle\helpers\Image;

class ImageTest extends \PHPUnit_Framework_TestCase
{
    public function rotateProvider()
    {
        return [
            [__DIR__ . '/data/chainring.jpg', 90, __DIR__ . '/data/chainring-rotated-left.jpg'],
            [__DIR__ . '/data/chainring.jpg', 270, __DIR__ . '/data/chainring-rotated-right.jpg'],
            ];
    }

    /**
     * @dataProvider rotateProvider
     */
    public function testRotate($source_path, $angle, $expected_path)
    {
        $img = Image::fromFile($source_path);
        $tmp_path = tempnam(sys_get_temp_dir(), 'image');
        $img->rotate($angle);
        $img->saveJpeg($tmp_path);
        $this->assertFileEquals($expected_path, $tmp_path);
        unlink($tmp_path);
    }

    public function testSaveJpeg()
    {
        $img = Image::fromFile(__DIR__ . '/data/chainring.jpg');
        $tmp_path = tempnam(sys_get_temp_dir(), 'image');
        $img->saveJpeg($tmp_path);
        $this->assertFileEquals(__DIR__ . '/data/chainring-saved.jpg', $tmp_path);
        unlink($tmp_path);
    }
}
