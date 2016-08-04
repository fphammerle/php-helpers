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
        if($this->_resource) {
            imagedestroy($this->_resource);
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
                $image->_resource = imagecreatefromjpeg($path);
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
        $resource = imagerotate($this->_resource, $angle, 0);
        imagedestroy($this->_resource);
        $this->_resource = $resource;
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
        imagejpeg($this->_resource, $path);
    }
}
