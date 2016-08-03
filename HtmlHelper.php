<?php

namespace fphammerle\helpers;

class HtmlHelper
{
    public static function encode($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE);
    }

    public static function endTag($name)
    {
        if($name === null) {
            return null;
        } elseif(!is_string($name)) {
            throw new \TypeError(
                sprintf('expected string or null as name, %s given', gettype($name))
                );
        } else {
            return '</' . $name . '>';
        }
    }
}
