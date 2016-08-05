<?php

namespace fphammerle\helpers\colors;

class RGBA extends RGB
{
    private $_alpha;

    public function __construct($red = 0, $green = 0, $blue = 0, $alpha = 1)
    {
        $this->setRed($red);
        $this->setGreen($green);
        $this->setBlue($blue);
        $this->setAlpha($alpha);
    }

    public function getAlpha()
    {
        return $this->_alpha;
    }

    /**
     * alpha 0 => 100% transparency
     * alpha 1 => 0% transparency, opaque
     */
    public function setAlpha($alpha)
    {
        $alpha = (float)$alpha;
        if($alpha < 0 || $alpha > 1) {
            throw new \UnexpectedValueException('value must be within [0, 1]');
        }
        $this->_alpha = $alpha;
    }

    public function equals(RGB $other)
    {
        return parent::equals($other)
            && abs($this->alpha - $other->alpha)  < self::comparison_precision;
    }

    public function getTuple()
    {
        return [$this->red, $this->green, $this->blue, $this->alpha];
    }
}
