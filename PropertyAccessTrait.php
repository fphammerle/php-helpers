<?php

namespace fphammerle\helpers;

trait PropertyAccessTrait
{
    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        $getter_name = 'get' . $name;
        if(!method_exists($this, $getter_name)) {
            throw new \Exception('unknown property ' . $name);
        } else {
            return $this->$getter_name();
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $setter_name = 'set' . $name;
        if(!method_exists($this, $setter_name)) {
            throw new \Exception('unknown property ' . $name);
        } else {
            $this->$setter_name($value);
        }
    }

    /**
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        $getter_name = 'get' . $name;
        return method_exists($this, $getter_name) && ($this->$getter_name() !== null);
    }
}
