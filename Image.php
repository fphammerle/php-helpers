<?php

namespace fphammerle\helpers;

class Image
{
    use \fphammerle\helpers\PropertyAccessTrait;

    protected $_resource = null;

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
        $image = new self;
        switch(exif_imagetype($path)) {
            case IMAGETYPE_JPEG:
                $image->_resource = imagecreatefromjpeg($path);
                break;
            case IMAGETYPE_PNG:
                $image->_resource = imagecreatefrompng($path);
                break;
            default:
                throw new \InvalidArgumentException("type of '$path' is not supported");
        }
        return $image;
    }

    public function getWidth()
    {
        return imagesx($this->_resource);
    }

    public function getColorAt($x, $y)
    {
        $colors = imagecolorsforindex(
            $this->_resource,
            imagecolorat($this->_resource, $x, $y)
            );
        return new \fphammerle\helpers\colors\RGBA(
            $colors['red'] / 0xFF,
            $colors['green'] / 0xFF,
            $colors['blue'] / 0xFF,
            1 - $colors['alpha'] / 127
            );
    }

    /**
     * @param float $angle anticlockwise
     * @return void
     */
    public function rotate($angle)
    {
        $angle = fmod($angle, 360);
        if(abs($angle) > 1e-10) {
            $resource = imagerotate($this->_resource, $angle, 0);
            imagedestroy($this->_resource);
            $this->_resource = $resource;
        }
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
