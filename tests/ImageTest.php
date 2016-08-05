<?php

namespace fphammerle\helpers\tests;

use fphammerle\helpers\Image;
use fphammerle\helpers\colors;

class ImageTest extends \PHPUnit_Framework_TestCase
{
    public function getColorAtProvider()
    {
        return [
            [__DIR__ . '/data/color.png', 0, 0, new colors\RGBA(0,   1,   0,   1      )],
            [__DIR__ . '/data/color.png', 1, 0, new colors\RGBA(0,   0,   1,   1      )],
            [__DIR__ . '/data/color.png', 1, 1, new colors\RGBA(0,   0,   0,   1      )],
            [__DIR__ . '/data/color.png', 0, 1, new colors\RGBA(1,   0,   0,   1      )],
            [__DIR__ . '/data/color.png', 0, 2, new colors\RGBA(1,   0.2, 0,   1      )],
            [__DIR__ . '/data/color.png', 1, 2, new colors\RGBA(0,   1,   0.2, 1      )],
            [__DIR__ . '/data/color.png', 2, 2, new colors\RGBA(0.2, 0,   1,   1      )],
            [__DIR__ . '/data/color.png', 3, 2, new colors\RGBA(1,   1,   1,   1      )],
            [__DIR__ . '/data/color.png', 2, 0, new colors\RGBA(0.2, 0.4, 1,   1      )],
            [__DIR__ . '/data/color.png', 2, 1, new colors\RGBA(0.2, 0.4, 1,   102/127)],
            [__DIR__ . '/data/color.png', 3, 1, new colors\RGBA(1,   0.8, 0.2, 102/127)],
            [__DIR__ . '/data/color.png', 3, 0, new colors\RGBA(0,   0,   0,   0      )],
            ];
    }

    /**
     * @dataProvider getColorAtProvider
     */
    public function testGetColorAt($path, $x, $y, $e)
    {
        $img = Image::fromFile($path);
        $r = $img->getColorAt($x, $y);
        $this->assertTrue($e->equals($r), print_r($r->tuple, true));
    }

    public function rotateProvider()
    {
        return [
            [
                __DIR__ . '/data/color.png',
                0,
                [
                    [0, 0, new colors\RGBA(0, 1, 0, 1)],
                    [1, 0, new colors\RGBA(0, 0, 1, 1)],
                    [3, 0, new colors\RGBA(0, 0, 0, 0)],
                    [0, 1, new colors\RGBA(1, 0, 0, 1)],
                    [1, 1, new colors\RGBA(0, 0, 0, 1)],
                    [3, 2, new colors\RGBA(1, 1, 1, 1)],
                    ],
                ],
            [
                __DIR__ . '/data/color.png',
                90,
                [
                    [0, 3, new colors\RGBA(0, 1, 0, 1)],
                    [0, 2, new colors\RGBA(0, 0, 1, 1)],
                    [0, 0, new colors\RGBA(0, 0, 0, 0)],
                    [1, 3, new colors\RGBA(1, 0, 0, 1)],
                    [1, 2, new colors\RGBA(0, 0, 0, 1)],
                    [2, 0, new colors\RGBA(1, 1, 1, 1)],
                    ],
                ],
            [
                __DIR__ . '/data/color.png',
                180,
                [
                    [3, 2, new colors\RGBA(0, 1, 0, 1)],
                    [2, 2, new colors\RGBA(0, 0, 1, 1)],
                    [0, 2, new colors\RGBA(0, 0, 0, 0)],
                    [3, 1, new colors\RGBA(1, 0, 0, 1)],
                    [2, 1, new colors\RGBA(0, 0, 0, 1)],
                    [0, 0, new colors\RGBA(1, 1, 1, 1)],
                    ],
                ],
            [
                __DIR__ . '/data/color.png',
                270,
                [
                    [2, 0, new colors\RGBA(0, 1, 0, 1)],
                    [2, 1, new colors\RGBA(0, 0, 1, 1)],
                    [2, 3, new colors\RGBA(0, 0, 0, 0)],
                    [1, 0, new colors\RGBA(1, 0, 0, 1)],
                    [1, 1, new colors\RGBA(0, 0, 0, 1)],
                    [0, 3, new colors\RGBA(1, 1, 1, 1)],
                    ],
                ],
            [
                __DIR__ . '/data/color.png',
                360,
                [
                    [0, 0, new colors\RGBA(0, 1, 0, 1)],
                    [1, 0, new colors\RGBA(0, 0, 1, 1)],
                    [3, 0, new colors\RGBA(0, 0, 0, 0)],
                    [0, 1, new colors\RGBA(1, 0, 0, 1)],
                    [1, 1, new colors\RGBA(0, 0, 0, 1)],
                    [3, 2, new colors\RGBA(1, 1, 1, 1)],
                    ],
                ],
            [
                __DIR__ . '/data/color.png',
                360 + 90,
                [
                    [0, 3, new colors\RGBA(0, 1, 0, 1)],
                    [0, 2, new colors\RGBA(0, 0, 1, 1)],
                    [0, 0, new colors\RGBA(0, 0, 0, 0)],
                    [1, 3, new colors\RGBA(1, 0, 0, 1)],
                    [1, 2, new colors\RGBA(0, 0, 0, 1)],
                    [2, 0, new colors\RGBA(1, 1, 1, 1)],
                    ],
                ],
            ];
    }

    /**
     * @dataProvider rotateProvider
     */
    public function testRotate($source_path, $angle, $expected_pixels)
    {
        $img = Image::fromFile($source_path);
        $img->rotate($angle);
        foreach($expected_pixels as $px) {
            $this->assertTrue($px[2]->equals($img->getColorAt($px[0], $px[1])));
        }
    }

    public function rotateLeftProvider()
    {
        return [
            [
                __DIR__ . '/data/color.png',
                [
                    [0, 3, new colors\RGBA(0, 1, 0, 1)],
                    [0, 2, new colors\RGBA(0, 0, 1, 1)],
                    [0, 0, new colors\RGBA(0, 0, 0, 0)],
                    [1, 3, new colors\RGBA(1, 0, 0, 1)],
                    [1, 2, new colors\RGBA(0, 0, 0, 1)],
                    [2, 0, new colors\RGBA(1, 1, 1, 1)],
                    ],
                ],
            ];
    }

    /**
     * @dataProvider rotateLeftProvider
     */
    public function testRotateLeft($source_path, $expected_pixels)
    {
        $img = Image::fromFile($source_path);
        $img->rotateLeft();
        foreach($expected_pixels as $px) {
            $this->assertTrue($px[2]->equals($img->getColorAt($px[0], $px[1])));
        }
    }

    public function rotateRightProvider()
    {
        return [
            [
                __DIR__ . '/data/color.png',
                [
                    [2, 0, new colors\RGBA(0, 1, 0, 1)],
                    [2, 1, new colors\RGBA(0, 0, 1, 1)],
                    [2, 3, new colors\RGBA(0, 0, 0, 0)],
                    [1, 0, new colors\RGBA(1, 0, 0, 1)],
                    [1, 1, new colors\RGBA(0, 0, 0, 1)],
                    [0, 3, new colors\RGBA(1, 1, 1, 1)],
                    ],
                ],
            ];
    }

    /**
     * @dataProvider rotateRightProvider
     */
    public function testRotateRight($source_path, $expected_pixels)
    {
        $img = Image::fromFile($source_path);
        $img->rotateRight();
        foreach($expected_pixels as $px) {
            $this->assertTrue($px[2]->equals($img->getColorAt($px[0], $px[1])));
        }
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
