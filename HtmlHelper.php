<?php

namespace fphammerle\helpers;

class HtmlHelper
{
    public static function encode($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE);
    }
}
