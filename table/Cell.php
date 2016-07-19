<?php

namespace fphammerle\helpers\table;

use \fphammerle\helpers\StringHelper;

class Cell
{
    use \fphammerle\helpers\PropertyAccessTrait;

    private $_value = [];

    /**
     * @param mixed $value
     */
    public function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->_value = $value;
    }

    /**
     * @throws InvalidArgumentException
     * @return string
     */
    public function toCSV($delimiter = ',', $quotes = '"')
    {
        if(empty($delimiter)) {
            throw new \InvalidArgumentException('empty delimiter');
        } elseif(empty($quotes)) {
            throw new \InvalidArgumentException('empty quotes');
        } elseif($delimiter == $quotes) {
            throw new \InvalidArgumentException('delimiter equals quotes');
        }

        if($this->value === false) {
            return '0';
        } else {
            $csv = (string)$this->value;
            if(StringHelper::containsAny(["\n", "\r", $quotes, $delimiter], $csv)) {
                $csv = $quotes . str_replace($quotes, $quotes.$quotes, $csv) . $quotes;
            }
            return $csv;
        }
    }
}
