<?php

namespace fphammerle\helpers;

class Image
{
    protected $resource = null;

    private function __construct()
    {
    }

    public function __destruct()
    {
        if($this->resource) {
            imagedestroy($this->resource);
        }
    }

    /**
     * @param string $path
     * @return Image
     */
    public static function fromFile($path)
    {
        switch(exif_imagetype($path)) {
            case IMAGETYPE_JPEG:
                $image = new self;
                $image->resource = imagecreatefromjpeg($path);
                return $image;
            default:
                throw new \InvalidArgumentException("type of '$path' is not supported");
        }
    }

    /**
     * @param float $angle
     * @return void
     */
    public function rotate($angle)
    {
        $resource = imagerotate($this->resource, $angle, 0);
        imagedestroy($this->resource);
        $this->resource = $resource;
    }

    /**
     * @return void
     */
    public function rotateLeft()
    {
        $this->rotate(90);
    }

    /**
     * @return void
     */
    public function rotateRight()
    {
        $this->rotate(270);
    }

    /**
     * @param string $path
     * @return void
     */
    public function saveJpeg($path)
    {
        imagejpeg($this->resource, $path);
    }
}
