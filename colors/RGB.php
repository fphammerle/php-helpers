<?php

namespace fphammerle\helpers\colors;

class RGB
{
    use \fphammerle\helpers\PropertyAccessTrait;

    const comparison_precision = 0.00001;

    private $_red;
    private $_green;
    private $_blue;

    public function __construct($red = 0, $green = 0, $blue = 0)
    {
        $this->setRed($red);
        $this->setGreen($green);
        $this->setBlue($blue);
    }

    public function getRed()
    {
        return $this->_red;
    }

    public function setRed($red)
    {
        $red = (float)$red;
        if($red < 0 || $red > 1) {
            throw new \UnexpectedValueException('value must be within [0, 1]');
        }
        $this->_red = $red;
    }

    public function getGreen()
    {
        return $this->_green;
    }

    public function setGreen($green)
    {
        $green = (float)$green;
        if($green < 0 || $green > 1) {
            throw new \UnexpectedValueException('value must be within [0, 1]');
        }
        $this->_green = $green;
    }

    public function getBlue()
    {
        return $this->_blue;
    }

    public function setBlue($blue)
    {
        $blue = (float)$blue;
        if($blue < 0 || $blue > 1) {
            throw new \UnexpectedValueException('value must be within [0, 1]');
        }
        $this->_blue = $blue;
    }

    public function equals(RGB $other)
    {
        return abs($this->red   - $other->red)   < self::comparison_precision
            && abs($this->green - $other->green) < self::comparison_precision
            && abs($this->blue  - $other->blue)  < self::comparison_precision;
    }
}
