<?php

namespace fphammerle\helpers;

class ArrayHelper
{
    /**
     * @return array
     */
    public static function flatten(array $arr)
    {
        return iterator_to_array(
            new \RecursiveIteratorIterator(new \RecursiveArrayIterator($arr)),
            false
            );
    }
}
