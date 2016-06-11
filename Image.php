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
     * @param string $path
     * @return void
     */
    public function saveJpeg($path)
    {
        imagejpeg($this->resource, $path);
    }
}
