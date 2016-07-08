<?php

namespace fphammerle\helpers\colors;

class HSV
{
    use \fphammerle\helpers\PropertyAccessTrait;

    const comparison_precision = 0.0001;

    private $_hue;
    private $_saturation;
    private $_value;

    public function __construct($hue = 0.0, $saturation = 0.0, $value = 0.0)
    {
        $this->setHue($hue);
        $this->setSaturation($saturation);
        $this->setValue($value);
    }

    public function getHue()
    {
        return $this->_hue;
    }

    public function setHue($hue)
    {
        $hue = (float)$hue;
        if($hue < 0 || $hue >= 2 * pi()) {
            throw new \UnexpectedValueException('value must be within [0, 2*pi)');
        }
        $this->_hue = $hue;
    }

    public function getSaturation()
    {
        return $this->_saturation;
    }

    public function setSaturation($saturation)
    {
        $saturation = (float)$saturation;
        if($saturation < 0 || $saturation > 1) {
            throw new \UnexpectedValueException('value must be within [0, 1]');
        }
        $this->_saturation = $saturation;
    }

    public function getValue()
    {
        return $this->_value;
    }

    public function setValue($value)
    {
        $value = (float)$value;
        if($value < 0 || $value > 1) {
            throw new \UnexpectedValueException('value must be within [0, 1]');
        }
        $this->_value = $value;
    }

    public function getTuple()
    {
        return [$this->hue, $this->saturation, $this->value];
    }

    public function equals(HSV $other)
    {
        return abs($this->hue - $other->hue) < self::comparison_precision
            && abs($this->saturation - $other->saturation) < self::comparison_precision
            && abs($this->value - $other->value) < self::comparison_precision;
    }
}
